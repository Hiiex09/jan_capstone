<?php
include('../database/models/dbconnect.php'); // Include database connection
$student_id = $_GET['update_student_id'];

$sql = "SELECT * FROM `tblstudent` WHERE school_id = $student_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$fullname = $row['name'];
$email = $row['email'];
$image = $row['image'];

if (isset($_POST['update'])) {
  // Check if a file was uploaded
  if (isset($_FILES['hen']) && $_FILES['hen']['error'] === 0) {
    // Get the file details
    $image = $_FILES['hen']['name'];
    $imageTmpName = $_FILES['hen']['tmp_name'];
    $imageDestination = '../upload/pics/' . $image;

    // Move the uploaded file to the desired folder
    if (move_uploaded_file($imageTmpName, $imageDestination)) {
      // Update the database with the new image file name
      $sql = "UPDATE `tblstudent` SET image='$image' WHERE school_id = $student_id";
      $result = mysqli_query($conn, $sql);

      if ($result) {
        // Set a success flag for JavaScript to handle
        $success = true;
      } else {
        die(mysqli_error($conn));
      }
    } else {
      echo "Failed to upload the image.";
    }
  }
}
?>

<!-- HTML and JavaScript -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.6/daisyui.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <div class="hero bg-base-200 min-h-screen">
    <div class="hero-content flex-col lg:flex-row-reverse">
      <div class="text-center lg:text-left">
        <h1 class="text-5xl font-bold">Updating Profile</h1>
        <p class="py-6">
          Welcome! Please take a moment to update your profile information. Make sure your details are current and accurate.
        </p>
      </div>
      <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="flex justify-center items-center shadow p-5 rounded-lg">
          <img id="image-preview"
            src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>"
            alt="Image Preview"
            class="w-full h-full object-cover mt-4 rounded-md ">
        </div>
        <div class="card-body p-8">
          <div class="flex flex-col justify-center items-center">
            <div class="text-center">
              <p class="text-2xl font-semibold"><?php echo $fullname; ?></p>
              <h1 class="text-lg">Student Name</h1>
            </div>
          </div>
          <fieldset class="fieldset">
            <form method="POST" enctype="multipart/form-data">
              <div class="m-2">
                <label for="hen" class="block cursor-pointer btn btn-md p-2 btn-neutral btn-outline text-center w-full">
                  Insert Updated Image
                </label>
                <input
                  type="file"
                  id="hen"
                  class="hidden"
                  accept="image/*"
                  onchange="previewImage(event)"
                  required
                  name="hen" />
              </div>
              <input type="submit" name="update" value="Update Profile"
                class="btn btn-md btn-neutral w-full mt-2">
            </form>
          </fieldset>
        </div>
      </div>
    </div>
  </div>

  <script>
    function previewImage(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('image-preview');

      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          preview.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = ""; // Clear the preview if no file is selected
      }
    }

    <?php if (isset($success) && $success) { ?>
      setTimeout(function() {
        alert('Update Successfully');
        window.location.href = 'student_dashboard.php'; // Redirect to student dashboard after alert
      }, 1500);
    <?php } ?>
  </script>
</body>

</html>