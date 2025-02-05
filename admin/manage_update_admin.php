<?php
include('../database/models/dbconnect.php'); // Include database connection
session_start();

if (isset($_GET['adminid'])) {
  $adminID = $_GET['adminid'];
} else {
  echo 'Error: adminid not set.';
  exit;
}

$sql = "SELECT * FROM `admin` WHERE id = $adminID";
$result = mysqli_query($conn, $sql);

if (!$result) {
  die('Error: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$fullname = $row['name'];
$email = $row['email'];
$password = $row['password'];
$username = $row['username'];
$image = $row['image'];

if (isset($_POST['update'])) {
  $fullname = $_POST['name'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit;
  }

  // Check if the username is unique
  $checkUsernameSql = "SELECT * FROM `admin` WHERE username = '$username' AND id != $adminID";
  $checkUsernameResult = mysqli_query($conn, $checkUsernameSql);
  if (mysqli_num_rows($checkUsernameResult) > 0) {
    echo "Username already exists";
    exit;
  }

  // Check if a file was uploaded
  if (isset($_FILES['hen']) && $_FILES['hen']['error'] === 0) {
    // Get the file details
    $image = $_FILES['hen']['name'];
    $imageTmpName = $_FILES['hen']['tmp_name'];
    $imageDestination = '../upload/pics/' . $image;

    // Move the uploaded file to the desired folder
    if (move_uploaded_file($imageTmpName, $imageDestination)) {
      // Update the database with the new image file name
      $sql = "UPDATE `admin` SET name='$fullname', email='$email', username='$username', password='$password', image='$image' WHERE id = $adminID";
    } else {
      echo "Failed to upload the image.";
      exit;
    }
  } else {
    // Update the database without changing the image
    $sql = "UPDATE `admin` SET name='$fullname', email='$email', username='$username', password='$password' WHERE id = $adminID";
  }

  $result = mysqli_query($conn, $sql);

  if ($result) {
    // Set a success flag for JavaScript to handle
    $success = true;
  } else {
    die(mysqli_error($conn));
  }
}
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Update</title>
</head>

<body>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?adminid=' . $adminID; ?>" method="post" enctype="multipart/form-data" class="p-20">
    <div>
      <h1 class="text-2xl m-2">Personal Info</h1>
    </div>
    <div class="grid grid-cols-2 gap-3 rounded-md border-2">
      <div class="h-full p-5">
        <img id="image-preview" src="../<?php echo htmlspecialchars($row['image']); ?>" alt="Image Preview" class="w-64 h-64 object-cover mt-4 rounded-md border p-2">

        <div class="mt-7">
          <label for="image" class="px-20 py-3 border rounded cursor-pointer shadow relative">Upload Image</label>
          <input type="file" id="image" class="hidden w-full" accept="image/*" onchange="previewImage(event)" name="hen">
        </div>
      </div>
      <div class="h-full p-5">
        <label class="input input-bordered flex items-center gap-2 mt-5">
          <input type="text" class="grow" placeholder="Name" name="name" value="<?php echo htmlspecialchars($fullname); ?>" required />
        </label>

        <label class="input input-bordered flex items-center gap-2 mt-5">
          <input type="email" class="grow" placeholder="Email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
        </label>

        <label class="input input-bordered flex items-center gap-2 mt-5">
          <input type="text" class="grow" placeholder="Username" name="username" value="<?php echo htmlspecialchars($username); ?>" required />
        </label>

        <label class="input input-bordered flex items-center gap-2 mt-5">
          <input type="password" class="grow" name="password" placeholder="Required New Password" required />
        </label>

        <div class="mt-5">
          <input type="submit" name="update" class="btn btn-neutral btn-outline rounded-md btn-sm w-full">
        </div>
      </div>
    </div>
  </form>

  <script>
    function previewImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = function() {
        const preview = document.getElementById('image-preview');
        preview.src = reader.result;
      }
      if (file) {
        reader.readAsDataURL(file);
      }
    }

    // Redirect to manage_admin.php after successful update
    <?php if (isset($success) && $success): ?>
      window.location.href = "../admin/manage_admin.php";
    <?php endif; ?>
    if (file) {
      reader.readAsDataURL(file);
    }
  </script>
</body>

</html>