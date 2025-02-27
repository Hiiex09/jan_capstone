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
    <a href="archive.php" class="text-blue-600 mt-4 inline-block">View Deleted Admin</a>
    <h1 class="text-3xl">Admin Table</h1>
  </div>

  <div class="p-5">
    <button class="m-2 px-4 py-2 bg-blue-900 text-lg hover:bg-blue-500 text-white rounded-md" onclick="my_modal_2.showModal()">Add Admin</button>
    <dialog id="my_modal_2" class="modal">
      <div class="modal-box max-w-2xl">
        <form action="" method="post" enctype="multipart/form-data">
          <h1 class="text-2xl">Personal Info</h1>
          <div>
            <label>Name:</label>
            <input type="text" name="name" required>
          </div>
          <div>
            <label>Email:</label>
            <input type="email" name="email" required>
          </div>
          <div>
            <label>Username:</label>
            <input type="text" name="username" required>
          </div>
          <div>
            <label>Password:</label>
            <input type="password" name="password" required>
          </div>
          <div>
            <label>Upload Image:</label>
            <input type="file" name="image" accept=".jpeg, .jpg, .png, .svg">
          </div>
          <button type="submit">Add Admin</button>
        </form>
      </div>
      <button onclick="my_modal_2.close()">Close</button>
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

      <table class="table">
        <thead class="bg-blue-900 text-white">
          <tr>
            <th class="px-4 py-2 text-center border">ID</th>
            <th class="px-4 py-2 text-center border">Image</th>
            <th class="px-4 py-2 text-center border">Name</th>
            <th class="px-4 py-2 text-center border">Username</th>
            <th class="px-4 py-2 text-center border">Email</th>
            <th class="px-4 py-2 text-center border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td class="text-center text-black border"><?php echo htmlspecialchars($row['id']); ?></td>
                <td class="text-center border">
                  <div class="flex justify-center items-center">
                    <div class="avatar">
                      <div class="mask mask-squircle h-12 w-12">
                        <img src="../<?php echo htmlspecialchars($row['image']); ?>"
                          alt="Admin Image" class="object-cover w-12 h-12">
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center text-black border"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="text-center text-black border"><?php echo htmlspecialchars($row['username']); ?></td>
                <td class="text-center text-black border"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="text-center text-black border">
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