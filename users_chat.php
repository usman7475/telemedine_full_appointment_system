
<?php
include 'navbar.php';

// Ensure patient is logged in
if (!isset($_SESSION['sid'])) {
    header('Location: index.php');
    exit();
}

$current_user_id = $_SESSION['sid']; // Current patient ID
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

if ($doctor_id === 0) {
    die("Invalid doctor ID");
}

// Check appointment status
$status_check_query = "SELECT status FROM enroll WHERE sid = '$current_user_id' AND doctorid = '$doctor_id'";
$status_check_result = mysqli_query($conn, $status_check_query);

if (mysqli_num_rows($status_check_result) > 0) {
    $status_row = mysqli_fetch_array($status_check_result);
    $appointment_status = $status_row['status'];
    
    // If appointment is cancelled, redirect with message
    if ($appointment_status === 'cancelled') {
        header('location:mydoctors.php?error=appointment_cancelled');
        exit;
    }
} else {
    // No appointment found
    die("No appointment found with this doctor");
}

// Handle sending a new message
if (isset($_POST['send_message'])) {
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message)
         VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $current_user_id, $doctor_id, $message);
        
        if ($stmt->execute()) {
            // Refresh to show the new message
            header("Location: users_chat.php?doctor_id=".$doctor_id);
            exit();
        } else {
            echo "<script>alert('Error sending message');</script>";
        }
    }
}

// Fetch doctor details from doctor table
$doctor_query = "SELECT id, name FROM doctor WHERE id = ?";
$doctor_stmt = $conn->prepare($doctor_query);
$doctor_stmt->bind_param("i", $doctor_id);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();
$doctor = $doctor_result->fetch_assoc();

if (!$doctor) {
    die("Doctor not found");
}

// Fetch chat messages
$chat_messages = [];
$msg_query = "SELECT m.*, 
              p.name as sender_name, 
              d.name as receiver_name 
              FROM messages m
              LEFT JOIN patient p ON m.sender_id = p.sid AND m.sender_id = ?
              LEFT JOIN doctor d ON m.receiver_id = d.id AND m.receiver_id = ?
              WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
              ORDER BY created_at ASC";

$msg_stmt = $conn->prepare($msg_query);
$msg_stmt->bind_param("iiiiii", $current_user_id, $doctor_id, $current_user_id, $doctor_id, $doctor_id, $current_user_id);
$msg_stmt->execute();
$msg_result = $msg_stmt->get_result();

while ($row = $msg_result->fetch_assoc()) {
    $chat_messages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat & Video Call with <?php echo htmlspecialchars($doctor['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --accent-color: #e74c3c;
        }

        .chat-container {
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .chat-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header h4 {
            margin: 0;
        }

        .video-call-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .video-call-btn:hover {
            background-color: #27ae60;
            transform: scale(1.05);
        }

        .video-call-btn:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }

        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
            position: relative;
        }

        .sent {
            background-color: #d4edda;
            margin-left: auto;
            text-align: right;
        }

        .received {
            background-color: #e9ecef;
            margin-right: auto;
            text-align: left;
        }

        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            display: block;
            margin-top: 5px;
        }

        .message-sender {
            font-weight: bold;
            font-size: 0.85rem;
            margin-bottom: 3px;
        }

        .no-messages {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }

        .chat-input {
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
        }

        /* Video Call Styles */
        .video-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }

        .video-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-modal-content {
            background-color: #000;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }

        .video-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .video-container {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 16/9;
            position: relative;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .video-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .control-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        .control-btn.danger {
            background-color: var(--accent-color);
        }

        .control-btn.danger:hover {
            background-color: #c0392b;
        }

        .calling-status {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .no-video-message {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: white;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-top: 80px;">
                <div class="chat-container">
                    <div class="chat-header">
                        <h4><i class="fas fa-comments me-2"></i>Chat with Dr. <?php echo htmlspecialchars($doctor['name']); ?></h4>
                        <button class="video-call-btn" onclick="initiateCall()">
                            <i class="fas fa-video me-2"></i>Video Call
                        </button>
                    </div>
                    
                    <div class="chat-messages">
                        <?php if (!empty($chat_messages)) : ?>
                            <?php foreach ($chat_messages as $msg) : ?>
                                <div class="message <?php echo ($msg['sender_id'] == $current_user_id) ? 'sent' : 'received'; ?>">
                                    <?php if ($msg['sender_id'] != $current_user_id) : ?>
                                        <div class="message-sender"><?php echo htmlspecialchars($msg['receiver_name']); ?></div>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                    <span class="message-time">
                                        <?php echo date('d M Y, h:i A', strtotime($msg['created_at'])); ?>
                                        <?php echo ($msg['sender_id'] == $current_user_id) ? ' · You' : ''; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-messages">
                                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                <h5>No messages yet</h5>
                                <p>Start the conversation with Dr. <?php echo htmlspecialchars($doctor['name']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="chat-input p-3">
                        <form method="POST" action="">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                                <button type="submit" name="send_message" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <br><br>
            </div>
        </div>
    </div>

    <!-- Video Call Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <div class="calling-status" id="callingStatus">Initializing video call...</div>
            
            <div class="video-grid">
                <div class="video-container">
                    <video id="localVideo" autoplay muted playsinline></video>
                    <div class="video-label">You</div>
                </div>
                <div class="video-container">
                    <video id="remoteVideo" autoplay playsinline></video>
                    <div class="video-label">Dr. <?php echo htmlspecialchars($doctor['name']); ?></div>
                </div>
            </div>

            <div class="video-controls">
                <button class="control-btn" id="toggleAudio" onclick="toggleAudio()">
                    <i class="fas fa-microphone me-2"></i>Mute
                </button>
                <button class="control-btn" id="toggleVideo" onclick="toggleVideo()">
                    <i class="fas fa-video me-2"></i>Stop Video
                </button>
                <button class="control-btn danger" onclick="endCall()">
                    <i class="fas fa-phone-slash me-2"></i>End Call
                </button>
            </div>
        </div>
    </div>

    
    <script>
        let localStream;
        let peerConnection;
        let isAudioEnabled = true;
        let isVideoEnabled = true;
        const doctor_id = <?php echo $doctor_id; ?>;
        const patient_id = <?php echo $current_user_id; ?>;

        const configuration = {
            iceServers: [
                { urls: ['stun:stun.l.google.com:19302', 'stun:stun1.l.google.com:19302'] }
            ]
        };

        async function initiateCall() {
            const videoModal = document.getElementById('videoModal');
            videoModal.classList.add('show');
            
            try {
                // Get user media
                localStream = await navigator.mediaDevices.getUserMedia({ 
                    audio: true, 
                    video: { width: 640, height: 480 } 
                });
                
                document.getElementById('localVideo').srcObject = localStream;
                document.getElementById('callingStatus').textContent = 'Press Video Call button on doctor side to connect';
                
                // Create peer connection
                peerConnection = new RTCPeerConnection(configuration);
                
                // Add local tracks
                localStream.getTracks().forEach(track => {
                    peerConnection.addTrack(track, localStream);
                });
                
                // Handle remote stream
                peerConnection.ontrack = (event) => {
                    document.getElementById('remoteVideo').srcObject = event.streams[0];
                    document.getElementById('callingStatus').textContent = 'Connected';
                };
                
                // Create and send offer
                const offer = await peerConnection.createOffer();
                await peerConnection.setLocalDescription(offer);
                
                // Send offer to signaling server
                sendSignaling({
                    type: 'offer',
                    offer: offer,
                    from: 'patient',
                    to_doctor_id: doctor_id
                });
                
            } catch (error) {
                console.error('Error initiating call:', error);
                alert('Failed to start video call: ' + error.message);
                closeVideoModal();
            }
        }

        function toggleAudio() {
            isAudioEnabled = !isAudioEnabled;
            localStream.getAudioTracks().forEach(track => {
                track.enabled = isAudioEnabled;
            });
            
            const btn = document.getElementById('toggleAudio');
            btn.innerHTML = isAudioEnabled 
                ? '<i class="fas fa-microphone me-2"></i>Mute' 
                : '<i class="fas fa-microphone-slash me-2"></i>Unmute';
        }

        function toggleVideo() {
            isVideoEnabled = !isVideoEnabled;
            localStream.getVideoTracks().forEach(track => {
                track.enabled = isVideoEnabled;
            });
            
            const btn = document.getElementById('toggleVideo');
            btn.innerHTML = isVideoEnabled 
                ? '<i class="fas fa-video me-2"></i>Stop Video' 
                : '<i class="fas fa-video-slash me-2"></i>Start Video';
        }

        function endCall() {
            if (peerConnection) {
                peerConnection.close();
            }
            
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            
            closeVideoModal();
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.remove('show');
            document.getElementById('localVideo').srcObject = null;
            document.getElementById('remoteVideo').srcObject = null;
        }

        function sendSignaling(data) {
            // This would send data to a signaling server
            // For now, this is a placeholder
            // In production, you would use WebSocket or similar
            console.log('Signaling data:', data);
            // Implement your signaling mechanism here
        }

        // Auto-scroll to bottom of chat
        window.onload = function() {
            const chatMessages = document.querySelector('.chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        };

        // Listen for peer connection events
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && peerConnection) {
                endCall();
            }
        });
    </script>
</body>
</html>

<?php include 'footer.php'; ?>