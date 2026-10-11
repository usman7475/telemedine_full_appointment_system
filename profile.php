<?php include 'navbar.php'; ?>

<?php
if (!isset($_SESSION['semail'])) {
header('Location: index.php');
exit;
}

$suser_id = $_SESSION['sid'];
$query = "SELECT * FROM patient WHERE sid = '$suser_id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_array($result)) {
$id = $row['sid'];
$name = $row['name'];
$email = $row['email'];
$phone = $row['phone'];
$password = $row['password'];
$age = $row['age'];
$gender = $row['gender'];
$contact = $row['contact'];
$medical_history = $row['medical_history'];
}
?>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow rounded-3">
<div class="card-header text-center bg-light">
<h4 class="mb-0">Account Information</h4>
</div>
<div class="card-body">
<form method="POST" action="">
<div class="mb-3">
<label class="form-label"><strong>Name</strong></label>
<input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label"><strong>Email</strong></label>
<input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label"><strong>Phone</strong></label>
<input type="number" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label"><strong>Password</strong></label>
<input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label"><strong>Age</strong></label>
<input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" class="form-control" min="1" max="120">
</div>

<div class="mb-3">
<label class="form-label"><strong>Gender</strong></label>
<select name="gender" class="form-control">
<option value="">Select Gender</option>
<option value="Male" <?php if($gender === 'Male') echo 'selected'; ?>>Male</option>
<option value="Female" <?php if($gender === 'Female') echo 'selected'; ?>>Female</option>
<option value="Other" <?php if($gender === 'Other') echo 'selected'; ?>>Other</option>
</select>
</div>

<div class="mb-3">
<label class="form-label"><strong>Contact Number (Additional)</strong></label>
<input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>" class="form-control">
</div>

<div class="mb-3">
<label class="form-label"><strong>Medical History</strong></label>
<textarea name="medical_history" class="form-control" rows="4" placeholder="List any medical conditions, allergies, or relevant health information..."><?php echo htmlspecialchars($medical_history); ?></textarea>
</div>

<button type="submit" name="submit" class="btn btn-success">Update Profile</button>

</form>
</div>
</div>

<?php
if (isset($_POST['submit'])) {
$uname = $_POST['name'];
$uemail = $_POST['email'];
$uphone = $_POST['phone'];
$upassword = $_POST['password'];
$uage = isset($_POST['age']) ? $_POST['age'] : '';
$ugender = isset($_POST['gender']) ? $_POST['gender'] : '';
$ucontact = isset($_POST['contact']) ? $_POST['contact'] : '';
$umedical_history = isset($_POST['medical_history']) ? $_POST['medical_history'] : '';

$updateQuery = "UPDATE patient SET 
name = '$uname', 
email = '$uemail', 
phone = '$uphone', 
password = '$upassword',
age = '$uage',
gender = '$ugender',
contact = '$ucontact',
medical_history = '$umedical_history'
WHERE sid = '$suser_id'";

if (mysqli_query($conn, $updateQuery)) {
echo "<script>
alert('Successfully updated your profile!');
window.location.href='profile.php';
</script>";
} else {
echo "<div class='alert alert-danger mt-3'>Update failed. Please try again.</div>";
}
}
?>
</div>
</div>
</div>
<br>
<br>
<br>
<?php include 'footer.php'; ?>
