<?php
include '../conn.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user_id = $_SESSION['tid']; // Doctor ID
$chat_messages = [];
$selected_patient_id = null;
$show_chat_interface = false;

// Handle sending a new message
if (isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $current_user_id, $receiver_id, $message);
        $stmt->execute();
        $selected_patient_id = $receiver_id;
    }
}

// Fetch enrolled patients from enroll table
$patient_query = "SELECT e.`eid`, e.`sid`, e.`doctorid`, p.name, p.email, p.phone 
                  FROM `enroll` e
                  INNER JOIN patient p ON e.sid = p.sid
                  WHERE e.doctorid = ?
                  ORDER BY p.name ASC";
$patient_stmt = $conn->prepare($patient_query);
$patient_stmt->bind_param("i", $current_user_id);
$patient_stmt->execute();
$patients_result = $patient_stmt->get_result();
$enrolled_patients = [];

while ($patient = $patients_result->fetch_assoc()) {
    $enrolled_patients[] = $patient;
}

// Fetch messages if a patient is selected
if (isset($_POST['start_chat']) || isset($_POST['send_message'])) {
    $selected_patient_id = $_POST['receiver_id'];

    $msg_query = "SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
    OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC";

    $msg_stmt = $conn->prepare($msg_query);
    $msg_stmt->bind_param("iiii", $current_user_id, $selected_patient_id, $selected_patient_id, $current_user_id);
    $msg_stmt->execute();
    $msg_result = $msg_stmt->get_result();

    if ($msg_result) {
        while ($row = mysqli_fetch_assoc($msg_result)) {
            $chat_messages[] = $row;
        }
        $show_chat_interface = true;
    }

    // If no messages but patient selected, still show chat interface
    if (isset($_POST['start_chat'])) {
        $show_chat_interface = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Chat & Video Consultation</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root {
--primary-color: #3498db;
--secondary-color: #2ecc71;
--dark-color: #2c3e50;
--accent-color: #e74c3c;
}

body {
font-family: 'Poppins', sans-serif;
background-color: #f8f9fa;
}

.chat-container {
max-width: 1000px;
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
flex: 1;
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

.select-patient-section {
background-color: #f8f9fa;
padding: 20px;
border-bottom: 1px solid #ddd;
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

.no-messages {
text-align: center;
padding: 50px;
color: #6c757d;
}

.chat-input {
background-color: #f8f9fa;
border-top: 1px solid #ddd;
padding: 15px;
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
background-color: rgba(0,0,0,0.9);
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
max-width: 900px;
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

/* Incoming Call Modal */
.incoming-call-modal {
display: none;
position: fixed;
z-index: 10000;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0,0,0,0.9);
}

.incoming-call-modal.show {
display: flex;
align-items: center;
justify-content: center;
}

.incoming-call-box {
background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
border-radius: 20px;
padding: 40px;
text-align: center;
color: white;
max-width: 500px;
box-shadow: 0 20px 60px rgba(0,0,0,0.7);
animation: pulse 1.5s infinite;
}

@keyframes pulse {
0%, 100% {
transform: scale(1);
}
50% {
transform: scale(1.05);
}
}

.incoming-call-box h2 {
margin-bottom: 20px;
font-size: 1.8rem;
}

.incoming-call-box .patient-name {
font-size: 1.5rem;
margin-bottom: 30px;
font-weight: 600;
}

.call-action-buttons {
display: flex;
gap: 20px;
justify-content: center;
margin-top: 30px;
}

.accept-btn {
background-color: var(--secondary-color);
padding: 15px 40px;
border: none;
border-radius: 25px;
color: white;
font-weight: 600;
cursor: pointer;
transition: all 0.3s ease;
}

.accept-btn:hover {
background-color: #27ae60;
transform: scale(1.05);
}

.reject-btn {
background-color: var(--accent-color);
padding: 15px 40px;
border: none;
border-radius: 25px;
color: white;
font-weight: 600;
cursor: pointer;
transition: all 0.3s ease;
}

.reject-btn:hover {
background-color: #c0392b;
transform: scale(1.05);
}

.ringing-icon {
font-size: 3rem;
margin-bottom: 20px;
animation: ring 1s infinite;
}

@keyframes ring {
0%, 100% {
transform: rotate(0deg);
}
25% {
transform: rotate(10deg);
}
75% {
transform: rotate(-10deg);
}
}
</style>
</head>
<body>

<div class="container-fluid">
<div class="row">
<div class="col-md-2">
<?php include 'sidebar.php'; ?>   
</div>

<div class="col-md-10" style="margin-top: 80px;">
<div class="chat-container">
<!-- Select Patient Section -->
<div class="select-patient-section">
<h5><i class="fas fa-user-injured me-2"> </i>  Appointments Detail</h5>
<br>
<a href="appointment_status.php" class="btn btn-outline-primary btn-sm">
<i class="fas fa-calendar-check me-1"></i> Manage Appointments
</a>
<br>
<br>
<?php if (!empty($enrolled_patients)) : ?>
<div class="row">
<?php foreach ($enrolled_patients as $patient) : ?>
<div class="col-md-6 mb-3">
<div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #3498db; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
<div style="display: flex; justify-content: space-between; align-items: center;">
<div>
<h6 style="margin: 0 0 5px 0; color: #2c3e50;"><i class="fas fa-user me-2"> </i> <?php echo htmlspecialchars($patient['name']); ?></h6>
<small style="color: #7f8c8d;"><i class="fas fa-envelope me-1"> </i> <?php echo htmlspecialchars($patient['email']); ?></small><br>
<small style="color: #7f8c8d;"><i class="fas fa-phone me-1"> </i> <?php echo htmlspecialchars($patient['phone']); ?></small>
</div>
<form method="POST" action="" style="margin: 0;">
<input type="hidden" name="receiver_id" value="<?php echo $patient['sid']; ?>">
<button type="submit" name="start_chat" class="btn btn-primary btn-sm">
<i class="fas fa-comments me-1"></i> Chat
</button>
</form>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php else : ?>
<div style="background: #fff3cd; padding: 20px; border-radius: 8px; text-align: center; color: #856404;">
<i class="fas fa-info-circle me-2"></i>
<strong>No Apointments</strong>
<p style="margin: 10px 0 0 0;">You don't have any patients enrolled yet. Patients will appear here once they book an appointment.</p>
</div>
<?php endif; ?>

<!-- Chat Interface (shown only when a patient is selected) -->
<?php if ($show_chat_interface) : ?>
<div class="chat-header">
<h4><i class="fas fa-comments me-2"></i>Consultation with Patient</h4>
<button class="video-call-btn" onclick="initiateCall()">
<i class="fas fa-video me-2"></i>Start Video Call
</button>
</div>

<div class="chat-messages">
<?php if (!empty($chat_messages)) : ?>
<?php foreach ($chat_messages as $msg) : ?>
<div class="message <?php echo ($msg['sender_id'] == $current_user_id) ? 'sent' : 'received'; ?>">
<?php echo htmlspecialchars($msg['message']); ?>
<span class="message-time">
<?php echo date('d-m-Y H:i', strtotime($msg['created_at'])); ?>
<?php echo ($msg['sender_id'] == $current_user_id) ? ' (You)' : ''; ?>
</span>
</div>
<?php endforeach; ?>
<?php else : ?>
<div class="no-messages">
<i class="fas fa-comment-slash fa-3x mb-3"></i>
<h5>No messages yet</h5>
<p>Start the consultation conversation!</p>
</div>
<?php endif; ?>
</div>

<!-- Send New Message -->
<div class="chat-input">
<form method="POST" action="">
<input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($selected_patient_id); ?>">
<div class="input-group">
<input type="text" name="message" class="form-control" placeholder="Type your message..." required>
<button type="submit" name="send_message" class="btn btn-primary">
<i class="fas fa-paper-plane me-2"></i>Send
</button>
</div>
</form>
</div>
<?php endif; ?>
</div>

<br><br>
</div>
</div>
</div>

<!-- Incoming Call Modal -->
<div id="incomingCallModal" class="incoming-call-modal">
<div class="incoming-call-box">
<div class="ringing-icon">
<i class="fas fa-phone-volume"></i>
</div>
<h2>Incoming Call</h2>
<div class="patient-name" id="incomingPatientName">Patient Name</div>
<div style="font-size: 1.1rem; margin-bottom: 20px; opacity: 0.9;">
<i class="fas fa-phone me-2"></i>Video Consultation Request
</div>
<div class="call-action-buttons">
<button class="accept-btn" onclick="acceptCall()">
<i class="fas fa-phone me-2"></i>Accept
</button>
<button class="reject-btn" onclick="rejectCall()">
<i class="fas fa-phone-slash me-2"></i>Reject
</button>
</div>
</div>
</div>

<!-- Video Call Modal -->
<div id="videoModal" class="video-modal">
<div class="video-modal-content">
<div class="calling-status" id="callingStatus">Connecting...</div>

<div class="video-grid">
<div class="video-container">
<video id="localVideo" autoplay muted playsinline></video>
<div class="video-label">You (Doctor)</div>
</div>
<div class="video-container">
<video id="remoteVideo" autoplay playsinline></video>
<div class="video-label" id="remoteLabel">Patient</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let localStream;
let peerConnection;
let isAudioEnabled = true;
let isVideoEnabled = true;
let callActive = false;
const doctor_id = <?php echo $current_user_id; ?>;
let patient_id = null;

const configuration = {
iceServers: [
{ urls: ['stun:stun.l.google.com:19302', 'stun:stun1.l.google.com:19302'] }
]
};

// Simulate incoming call (in production, use WebSocket)
function simulateIncomingCall(patientName, patientId) {
patient_id = patientId;
document.getElementById('incomingPatientName').textContent = patientName;
document.getElementById('incomingCallModal').classList.add('show');
}

async function acceptCall() {
document.getElementById('incomingCallModal').classList.remove('show');
const videoModal = document.getElementById('videoModal');
videoModal.classList.add('show');
callActive = true;

try {
// Get user media
localStream = await navigator.mediaDevices.getUserMedia({ 
audio: true, 
video: { width: 640, height: 480 } 
});

document.getElementById('localVideo').srcObject = localStream;
document.getElementById('callingStatus').textContent = 'Call Connected';

// Create peer connection
peerConnection = new RTCPeerConnection(configuration);

// Add local tracks
localStream.getTracks().forEach(track => {
peerConnection.addTrack(track, localStream);
});

// Handle remote stream
peerConnection.ontrack = (event) => {
document.getElementById('remoteVideo').srcObject = event.streams[0];
};

// Handle ICE candidates
peerConnection.onicecandidate = (event) => {
if (event.candidate) {
console.log('ICE Candidate:', event.candidate);
}
};

} catch (error) {
console.error('Error accepting call:', error);
alert('Failed to start video call: ' + error.message);
endCall();
}
}

function rejectCall() {
document.getElementById('incomingCallModal').classList.remove('show');
patient_id = null;
callActive = false;
}

async function initiateCall() {
const selectElement = document.querySelector('select[name="receiver_id"]');
patient_id = parseInt(selectElement.value);

if (!patient_id) {
alert('Please select a patient first');
return;
}

const videoModal = document.getElementById('videoModal');
videoModal.classList.add('show');
callActive = true;

try {
// Get user media
localStream = await navigator.mediaDevices.getUserMedia({ 
audio: true, 
video: { width: 640, height: 480 } 
});

document.getElementById('localVideo').srcObject = localStream;
document.getElementById('callingStatus').textContent = 'Calling patient...';

// Create peer connection
peerConnection = new RTCPeerConnection(configuration);

// Add local tracks
localStream.getTracks().forEach(track => {
peerConnection.addTrack(track, localStream);
});

// Handle remote stream
peerConnection.ontrack = (event) => {
document.getElementById('remoteVideo').srcObject = event.streams[0];
document.getElementById('callingStatus').textContent = 'Call Connected';
};

// Create and send offer
const offer = await peerConnection.createOffer();
await peerConnection.setLocalDescription(offer);

// Send offer to signaling server
sendSignaling({
type: 'offer',
offer: offer,
from: 'doctor',
doctor_id: doctor_id,
to_patient_id: patient_id
});

} catch (error) {
console.error('Error initiating call:', error);
alert('Failed to start video call: ' + error.message);
closeVideoModal();
}
}

function toggleAudio() {
isAudioEnabled = !isAudioEnabled;
if (localStream) {
localStream.getAudioTracks().forEach(track => {
track.enabled = isAudioEnabled;
});
}

const btn = document.getElementById('toggleAudio');
btn.innerHTML = isAudioEnabled 
? '<i class="fas fa-microphone me-2"></i>Mute' 
: '<i class="fas fa-microphone-slash me-2"></i>Unmute';
}

function toggleVideo() {
isVideoEnabled = !isVideoEnabled;
if (localStream) {
localStream.getVideoTracks().forEach(track => {
track.enabled = isVideoEnabled;
});
}

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
callActive = false;
patient_id = null;
}

function closeVideoModal() {
document.getElementById('videoModal').classList.remove('show');
document.getElementById('localVideo').srcObject = null;
document.getElementById('remoteVideo').srcObject = null;
}

function sendSignaling(data) {
// Send data to signaling server
console.log('Signaling data:', data);
// In production: use WebSocket to send this to a signaling server
// websocket.send(JSON.stringify(data));
}

// Auto-scroll to bottom of chat
window.addEventListener('load', function() {
const chatMessages = document.querySelector('.chat-messages');
if (chatMessages) {
chatMessages.scrollTop = chatMessages.scrollHeight;
}
});

// Clean up on page unload
window.addEventListener('beforeunload', function() {
if (callActive) {
endCall();
}
});

// Listen for visibility changes
document.addEventListener('visibilitychange', () => {
if (document.hidden && callActive) {
endCall();
}
});

// Test: Uncomment to test incoming call
// setTimeout(() => {
//     simulateIncomingCall('John Doe', 1);
// }, 2000);
</script>
</body>
</html>

