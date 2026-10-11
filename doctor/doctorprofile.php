<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <?php include 'sidebar.php'; ?>   
    </div>

    <div class="col-md-10" style="margin-top: 100px;">
      <style>
        .col-md-10 {
          font-size: 13px !important;
        }
        .col-md-10 .table {
          font-size: 13px !important;
        }
        .col-md-10 table td,
        .col-md-10 table th {
          font-size: 13px !important;
          padding: 8px !important;
        }
        .col-md-10 h5 {
          font-size: 15px !important;
          font-weight: 600;
        }
      </style>
      <h5 class="text-center"> Profile Detail </h5>
      <br>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Picture</th>
              <th>Name</th>
              <th>Email</th>
              <th>Qualification</th>
              <th>Specialty</th>
              <th>Experience</th>
              <th>Availability</th>
              <th>Schedule</th>
              <th>Address</th>
              <th>Mobile</th>
              <th>Fee</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $t_id=$_SESSION['tid'];
            $res = mysqli_query($conn, "SELECT * FROM doctor WHERE id='$t_id'");
            $i = 1;
            while ($row = mysqli_fetch_assoc($res)) {
              echo "<tr>
                <td>{$i}</td>
                <td><img src='../admin/uploads/{$row['picture']}' width='50' height='50' style='border-radius: 5px;'></td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['qualification']}</td>
                <td>{$row['specialty']}</td>
                <td>{$row['experience']}</td>
                <td>{$row['availability']}</td>
                <td>{$row['schedule']}</td>
                <td>{$row['address']}</td>
                <td>{$row['mobile']}</td>
                <td>PKR {$row['fee']}</td>
                <td>
                  <button class='btn btn-warning btn-sm editBtn' 
                    data-id='{$row['id']}'
                    data-name='{$row['name']}'
                    data-email='{$row['email']}'
                    data-password='{$row['password']}'
                    data-qualification='{$row['qualification']}'
                    data-specialty='{$row['specialty']}'
                    data-experience='{$row['experience']}'
                    data-availability='{$row['availability']}'
                    data-schedule='{$row['schedule']}'
                    data-picture='{$row['picture']}'
                    data-address='{$row['address']}'
                    data-mobile='{$row['mobile']}'
                    data-fee='{$row['fee']}'
                    >Edit</button>
                </td>
              </tr>";
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="col-md-6">
          <label>Name</label>
          <input type="text" class="form-control" name="edit_name" id="edit_name" required>
          <label>Email</label>
          <input type="email" class="form-control" name="edit_email" id="edit_email" required>
          <label>Password</label>
          <input type="text" class="form-control" name="edit_password" id="edit_password" required>
          <label>Qualification</label>
          <input type="text" class="form-control" name="edit_qualification" id="edit_qualification" required>
          <label>Specialty</label>
          <input type="text" class="form-control" name="edit_specialty" id="edit_specialty" required>
          <label>Experience</label>
          <input type="text" class="form-control" name="edit_experience" id="edit_experience" required>
        </div>
        <div class="col-md-6">
          <label>Availability</label>
          <select class="form-control" name="edit_availability" id="edit_availability" required>
            <option value="">Select Availability</option>
            <option value="Monday-Friday">Monday-Friday</option>
            <option value="Monday-Saturday">Monday-Saturday</option>
            <option value="Monday-Sunday">Monday-Sunday (Including Weekends)</option>
            <option value="Custom">Custom Schedule</option>
          </select>
          
          <label>Schedule Details</label>
          <input type="text" class="form-control" name="edit_schedule" id="edit_schedule" placeholder="e.g., 9:00 AM - 5:00 PM" required>
          
          <label>Current Picture</label><br>
          <img id="old_picture" src="" width="100" class="mb-2" style="border-radius: 5px;">
          <input type="file" class="form-control" name="edit_picture" accept="image/*">
          
          <label>Address</label>
          <textarea class="form-control" name="edit_address" id="edit_address" required></textarea>
          <label>Mobile</label>
          <input type="text" class="form-control" name="edit_mobile" id="edit_mobile" required>
          <label>Fee (PKR/hr)</label>
          <input type="text" class="form-control" name="edit_fee" id="edit_fee" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.editBtn').click(function(){
    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_email').val($(this).data('email'));
    $('#edit_password').val($(this).data('password'));
    $('#edit_qualification').val($(this).data('qualification'));
    $('#edit_specialty').val($(this).data('specialty'));
    $('#edit_experience').val($(this).data('experience'));
    $('#edit_availability').val($(this).data('availability'));
    $('#edit_schedule').val($(this).data('schedule'));
    $('#edit_address').val($(this).data('address'));
    $('#edit_mobile').val($(this).data('mobile'));
    $('#edit_fee').val($(this).data('fee'));

    const picture = $(this).data('picture');
    $('#old_picture').attr('src', '../admin/uploads/' + picture);

    $('#editModal').modal('show');
  });
});
</script>

<?php

// UPDATE
if (isset($_POST['update'])) {
  $id = $_POST['edit_id'];
  $name = $_POST['edit_name'];
  $email = $_POST['edit_email'];
  $password = $_POST['edit_password'];
  $qualification = $_POST['edit_qualification'];
  $specialty = $_POST['edit_specialty'];
  $experience = $_POST['edit_experience'];
  $availability = $_POST['edit_availability'];
  $schedule = $_POST['edit_schedule'];
  $address = $_POST['edit_address'];
  $mobile = $_POST['edit_mobile'];
  $fee = $_POST['edit_fee'];

  // Initialize variable for picture name
  $pic_name = '';

  // Handle picture upload if new file is provided
  if (!empty($_FILES['edit_picture']['name'])) {
    $pic_name = $_FILES['edit_picture']['name'];
    $pic_tmp = $_FILES['edit_picture']['tmp_name'];
    move_uploaded_file($pic_tmp, "../admin/uploads/$pic_name");
  }

  // Build the update query
  $query = "UPDATE doctor SET 
            name='$name', 
            email='$email', 
            password='$password',
            qualification='$qualification', 
            specialty='$specialty',
            experience='$experience', 
            availability='$availability',
            schedule='$schedule',
            address='$address', 
            mobile='$mobile', 
            fee='$fee'";
  
  // Add picture to query if updated
  if (!empty($pic_name)) {
    $query .= ", picture='$pic_name'";
  }
  
  $query .= " WHERE id=$id";

  mysqli_query($conn, $query);
  echo "<script>window.location.href='';</script>";
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>