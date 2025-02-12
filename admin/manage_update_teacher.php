<?php
include('../database/models/dbconnect.php');
session_start();

if (!isset($_GET['teacher_id']) || empty($_GET['teacher_id'])) {
  die("Invalid request: teacher_id is missing.");
}

$update_id = intval($_GET['teacher_id']); // Ensure it's an integer

$sql = "SELECT * FROM `tblteacher` WHERE teacher_id = $update_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
  die("Error: No teacher found with ID $update_id.");
}

$row = mysqli_fetch_assoc($result);

// Use null coalescing operator to prevent undefined index warnings
$school_id = $row['teacher_id'] ?? '';
$fullname = $row['name'] ?? '';
$department = $row['department_id'] ?? '';
$image = $row['image'] ?? 'default.png'; // Set a default image if none is found

if (isset($_POST['update'])) {
  $school_id = $_POST['school_id'];
  $department = $_POST['department_id'];

  // Handle image upload safely
  if (!empty($_FILES['hen']['name'])) {
    $image = $_FILES['hen']['name'];
    $target = "../upload/pics/" . basename($image);
    move_uploaded_file($_FILES['hen']['tmp_name'], $target);
  }

  $sql = "UPDATE `tblteacher` SET name = '$fullname', department_id = '$department', image = '$image' WHERE teacher_id = $update_id";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo "<script> window.location.href='manage_teacher.php';</script>";
    echo "<div class='text-center bg-green-900'><h1 class='text-2xl'>Updated Successfully</h1></div>";
  } else {
    echo "<div class='text-center bg-red-900'><h1 class='text-2xl'>Cannot update user</h1></div>";
  }
}
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Teacher</title>
</head>

<body>
  <form
    action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>"
    method="post"
    enctype="multipart/form-data">
    <div class="flex justify-evenly items-center">
      <div class="mt-4">
        <div class="m-1 flex justify-between items-center">
          <div>
            <h1 class=" text-2xl">Image Preview</h1>
          </div>
        </div>
        <div>
          <img id="image-preview"
            src="../upload/pics/<?php echo $image; ?>"
            alt="Image Preview"
            class="w-64 h-64 object-cover 
                      mt-4 rounded-md">
        </div>
        <div class="mt-7">
          <label for="hen"
            class="px-20 py-3 border rounded cursor-pointer bg-base-100 relative 
                  shadow-blue-400 hover:shadow-blue-500 hover:shadow-lg 
                  transition duration-300 ease-in-out">
            Upload Image
          </label>

          <input
            type="file"
            id="hen"
            class="hidden w-full"
            accept="image/*"
            onchange="previewImage(event)"
            required
            name="hen"
            value="" accept=".jpeg, .jpg, .png, .svg">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-6">
        <div class="mt-8">
          <div class="m-1 flex justify-between items-center">
            <div>
              <label class="">School ID</label>
            </div>

          </div>
          <div>
            <!-- School ID -->
            <label class="input input-bordered flex items-center gap-2 mt-3">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-4 w-4 opacity-70"
                id="id-icon">
                <path
                  d="M1 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3z" />
                <path
                  d="M4 6h8v2H4V6zm0 4h5v2H4v-2z" />
              </svg>
              <input type="text" class="grow" placeholder="School ID" minlength="7" maxlength="7" name="school_id"
                autocomplete="off" value="<?php echo $school_id; ?>" />
            </label>

          </div>
          <div class="m-1 flex justify-between items-center">
            <div>
              <label class="">First Name</label>
            </div>

          </div>
          <div>
            <!-- First Name -->
            <label class="input input-bordered flex items-center gap-2 mt-3">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-4 w-4 opacity-70"
                id="notebook-icon">
                <path
                  d="M3 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H3zM4 2h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
                <path
                  d="M6 3v10h4V3H6z" />
              </svg>
              <input type="text" class="grow" placeholder="First Name" name="fname" autocomplete="off" value="<?php echo $fullname ?>" />
            </label>
          </div>
        </div>
        <div class="mt-5">

          <div class="mt-3 flex flex-col justify-center items-start">
            <div class="m-1 w-full flex justify-between items-start">
              <div>
                <label class=" text-lg">Department</label>
              </div>
            </div>
            <select class="select select-bordered w-full"
              name="department_id"
              required>
              <option value="" disabled selected>Select Department</option>
              <?php
              $department = $conn->query("SELECT * FROM tbldepartment");
              while ($row = $department->fetch_assoc()): ?>
                <option value="<?php echo $row['department_id']; ?>"><?php echo htmlspecialchars($row['department_name']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mt-8">
            <div>
              <button type="submit" name="update"
                class=" btn btn-md btn-outline btn-primary mt-3 rounded-md w-full">
                Submit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

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
  </script>
</body>

</html>