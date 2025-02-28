<?php
include('../database/models/dbconnect.php');
session_start();

// Fetch all admins
$sql = "SELECT * FROM `admin` WHERE `deleted_at` IS NULL";
$result = mysqli_query($conn, $sql);

// Handle admin creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = mysqli_real_escape_string($conn, $_POST["name"]);
  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $password = mysqli_real_escape_string($conn, $_POST["password"]);

  // Hash the password before storing it
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Set default image path
  $default_image = "pic/pics/Student.png";
  $image_path = $default_image;

  // Check if an image is uploaded
  if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
    $imgname = $_FILES['image']['name'];
    $imgsize = $_FILES['image']['size'];
    $imgtmp = $_FILES['image']['tmp_name'];

    // Validate image extension
    $valid_extensions = ['jpeg', 'jpg', 'png', 'svg'];
    $imgEx = strtolower(pathinfo($imgname, PATHINFO_EXTENSION));

    // Validate extension and size
    if (!in_array($imgEx, $valid_extensions)) {
      echo "<script>alert('Invalid extension. Allowed: jpeg, jpg, png, svg');</script>";
      exit();
    } elseif ($imgsize > 1000000) { // 1MB max size
      echo "<script>alert('Image is too large. Max: 1MB');</script>";
      exit();
    }

    // Create a unique filename and move the file
    $newimg = uniqid() . '.' . $imgEx;
    $image_path = "upload/pics/$newimg";

    if (!move_uploaded_file($imgtmp, "../$image_path")) {
      echo "<script>alert('Image upload failed');</script>";
      exit();
    }
  }

  // Insert into database
  $stmt = $conn->prepare("INSERT INTO `admin` (name, username, email, password, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $username, $email, $hashed_password, $image_path);

  if ($stmt->execute()) {
    header('Location: ../admin/manage_admin.php');
    exit();
  } else {
    echo "<script>alert('Error: Cannot create admin');</script>";
  }
  $stmt->close();
}

// Soft delete admin
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $stmt = $conn->prepare("UPDATE admin SET deleted_at = NOW() WHERE id = ?");
  $stmt->bind_param("i", $delete_id);

  if ($stmt->execute()) {
    echo "<script>window.location.href='manage_admin.php';</script>";
  } else {
    echo "<script>alert('Error deleting admin.');</script>";
  }
  $stmt->close();
}
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Table</title>
  <link rel="stylesheet" href="../css/style.css"> <!-- Ensure CSS file is linked -->
</head>

<body>
  <div class="p-5">
    <h1 class="text-3xl">Admin Table</h1>
    <!-- <a href="archive.php" class="btn btn-sm btn-primary mt-4">View Deleted Admin</a> -->
  </div>

  <div class="p-5 m-3">
    <button class="btn btn-sm btn-primary" onclick="my_modal_2.showModal()">Add Admin</button>
    <dialog id="my_modal_2" class="modal">
      <div class="modal-box w-11/12 max-w-5xl bg-primary-content">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-8 w-8 opacity-70 animate-bounce">
            <path d="M8 .5a1 1 0 0 1 .515.14l5 3A1 1 0 0 1 14 4.5v3a7.5 7.5 0 0 1-6.22 7.426 1 1 0 0 1-.56 0A7.5 7.5 0 0 1 1 7.5v-3a1 1 0 0 1 .485-.86l5-3A1 1 0 0 1 8 .5Zm0 1.234L3 4.18V7.5a6.5 6.5 0 0 0 5 6.326A6.5 6.5 0 0 0 13 7.5V4.18l-5-2.446Z" />
          </svg>
          Personal Info
        </h1>
        <form action="" method="post" enctype="multipart/form-data" class="flex justify-evenly items-center text-white">
          <div>
            <div class="flex flex-col justify-center items-center">
              <h1 class="text-lg font-semibold">Image Preview</h1>
              <img id="image-preview" src="../admin/tools/Images/def_logo.png" alt="Image Preview" class="w-64 h-64 object-cover mt-4 rounded-md border shadow-md">
              <label for="hen" class="btn btn-md btn-outline btn-primary w-full cursor-pointer mt-4">Upload Image</label>
              <input type="file" id="hen" class="hidden" accept="image/*" onchange="previewImage(event)" required name="image" value="" accept=".jpeg, .jpg, .png, .svg">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold mb-1">Name</label>
              <input type="text" class="input input-bordered w-full" placeholder="Name" name="name" autocomplete="off">
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Email</label>
              <input type="text" class="input input-bordered w-full" placeholder="Email" name="email" autocomplete="off" required>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Username</label>
              <input type="text" class="input input-bordered w-full" placeholder="Username" name="username" autocomplete="off" required>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Password</label>
              <input type="password" class="input input-bordered w-full" placeholder="Password" name="password" autocomplete="off" required>
            </div>

            <button type="submit" class="btn btn-md btn-primary w-full">Add Admin</button>
          </div>
        </form>
        <div class="modal-action">
          <button class="btn btn-md btn-secondary" onclick="my_modal_2.close()">Close</button>
        </div>
      </div>
    </dialog>
  </div>


  <section>
    <div class="overflow-x-auto p-5 shadow-lg m-5">
      <?php


      // Set number of records per page
      $records_per_page = 5;

      // Get the current page number from the query string, default to 1
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      if ($page < 1) {
        $page = 1;
      }

      // Calculate the offset for SQL query
      $offset = ($page - 1) * $records_per_page;

      // Fetch total number of records (excluding soft deleted ones)
      $total_sql = "SELECT COUNT(*) AS total FROM `admin` WHERE `deleted_at` IS NULL";
      $total_result = mysqli_query($conn, $total_sql);
      $total_row = mysqli_fetch_assoc($total_result);
      $total_records = $total_row['total'];
      $total_pages = ceil($total_records / $records_per_page);

      // Fetch only required records with pagination
      $sql = "SELECT * FROM `admin` WHERE `deleted_at` IS NULL LIMIT $records_per_page OFFSET $offset";
      $result = mysqli_query($conn, $sql);
      ?>

      <section>
        <div class="overflow-y-auto- m-3">
          <table class="table"
            <thead class="bg-blue-900 text-white">
            <tr>
              <!-- <th>ID</th> -->
              <th class="text-center">Image</th>
              <th class="text-center">Name</th>
              <th class="text-center">Username</th>
              <th class="text-center">Email</th>
              <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                  <tr>
                    <!-- <td><//?php echo htmlspecialchars($row['id']); ?></td> -->
                    <td class="text-center">
                      <div class="flex justify-center items-center">
                        <div class="avatar">
                          <div class="mask mask-squircle h-12 w-12">
                            <img src="../<?php echo htmlspecialchars($row['image']); ?>"
                              alt="Admin Image" class="object-cover w-12 h-12">
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['username']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="text-center">
                      <a href="../admin/manage_update_admin.php?adminid=<?php echo $row['id']; ?>"
                        class="btn btn-xs btn-warning" title="Edit Admin">Edit</a>
                      <a href="?delete_id=<?php echo $row['id']; ?>"
                        class="btn btn-xs btn-error"
                        title="Delete Admin"
                        onclick="return confirm('Are you sure you want to delete this admin?');">
                        Delete
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr>
                  <td colspan="6" class="text-center text-2xl py-4 font-bold">No admin available</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </section>


      <!-- Pagination Controls -->
      <div class="flex justify-center mt-5">
        <?php if ($total_pages > 1) { ?>
          <div class="join">
            <?php if ($page > 1) { ?>
              <a href="?page=<?php echo $page - 1; ?>" class="btn btn-sm mx-1">Previous</a>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
              <a href="?page=<?php echo $i; ?>"
                class="btn btn-sm mx-2 <?php echo ($i == $page) ? 'btn-active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php } ?>

            <?php if ($page < $total_pages) { ?>
              <a href="?page=<?php echo $page + 1; ?>" class="btn btn-sm mx-1">Next</a>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>


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
        preview.src = ""; // Clear preview
      }
    }
  </script>
</body>

</html>