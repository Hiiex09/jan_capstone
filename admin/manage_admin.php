<?php
include('../database/models/dbconnect.php');
session_start();


$sql = "SELECT * FROM `admin`";
$result = mysqli_query($conn, $sql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $username = $_POST['username'];
  $email = $_POST["email"];
  $password = $_POST['password'];

  // Check if an image is uploaded
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imgname = $_FILES['image']['name'];
    $imgsize = $_FILES['image']['size'];
    $imgtmp = $_FILES['image']['tmp_name'];

    // Validate image extension
    $imgvalid = ['jpeg', 'jpg', 'png', 'svg'];
    $imgEx = strtolower(pathinfo($imgname, PATHINFO_EXTENSION));

    // Validate extension and size
    if (!in_array($imgEx, $imgvalid)) {
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
  } else {
    $image_path = "upload/pics/default.png"; // Default image
  }

  // Insert into database
  $sql = "INSERT INTO `admin` (name, username, email, password, image) VALUES ('$name', '$username', '$email', '$password', '$image_path')";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    header('location: ../admin/manage_admin.php');
  } else {
    echo "<div>Can not create admin</div>";
  }
}
?>



<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Table</title>
</head>

<body>
  <div class=" p-5">
    <h1 class="text-2xl font-bold">Admin Table</h1>
  </div>
  <div class="p-5">
    <!-- Open the modal using ID.showModal() method -->
    <button class="btn btn-sm btn-neutral" onclick="my_modal_2.showModal()">Add Admin</button>
    <dialog id="my_modal_2" class="modal">
      <div class="modal-box max-w-2xl">
        <form
          action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
          method="post"
          enctype="multipart/form-data">
          <div>
            <h1 class="text-2xl">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-8 w-8 opacity-70 animate-bounce">
                <path
                  d="M8 .5a1 1 0 0 1 .515.14l5 3A1 1 0 0 1 14 4.5v3a7.5 7.5 0 0 1-6.22 7.426 1 1 0 0 1-.56 0A7.5 7.5 0 0 1 1 7.5v-3a1 1 0 0 1 .485-.86l5-3A1 1 0 0 1 8 .5Zm0 1.234L3 4.18V7.5a6.5 6.5 0 0 0 5 6.326A6.5 6.5 0 0 0 13 7.5V4.18l-5-2.446Z" />
              </svg>
              Personal Info
            </h1>
          </div>
          <div class="grid grid-cols-2 gap-3 rounded-md border-2">
            <div class="h-full p-5">
              <div class="m-1 flex justify-between items-center">
                <div>
                  <h1 class="text-2xl">Image Preview</h1>
                </div>
              </div>
              <div>
                <img id="image-preview"
                  src="../admin/tools/Images/def_logo.png"
                  alt="Image Preview"
                  class="w-64 h-64 object-cover 
              mt-4 rounded-md border p-2">
              </div>
              <div class="mt-7">
                <label for="image"
                  class="px-20 py-3 border rounded cursor-pointer shadow relative">
                  Upload Image
                </label>
                <input
                  type="file"
                  id="image"
                  class="hidden w-full"
                  accept="image/*"
                  onchange="previewImage(event)"
                  required
                  name="image"
                  accept=".jpeg, .jpg, .png, .svg">
              </div>
            </div>
            <div class="h-full p-5">
              <!-- Name -->
              <label class="input input-bordered flex items-center gap-2 mt-5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="h-4 w-4 opacity-70">
                  <path
                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.5 6a5.5 5.5 0 1 1 11 0H2.5Z" />
                </svg>
                <input type="text" class="grow" placeholder="Name" name="name" required />
              </label>

              <!-- Email -->
              <label class="input input-bordered flex items-center gap-2 mt-5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="h-4 w-4 opacity-70">
                  <path
                    d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                  <path
                    d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
                </svg>
                <input type="email" class="grow" placeholder="Email" name="email" required />
              </label>

              <!-- Username -->
              <label class="input input-bordered flex items-center gap-2 mt-5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="h-4 w-4 opacity-70">
                  <path
                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
                </svg>
                <input type="text" class="grow" placeholder="Username" name="username" required />
              </label>

              <!-- Password -->
              <label class="input input-bordered flex items-center gap-2 mt-5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 16 16"
                  fill="currentColor"
                  class="h-4 w-4 opacity-70">
                  <path
                    fill-rule="evenodd"
                    d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                    clip-rule="evenodd" />
                </svg>
                <input type="password" class="grow" placeholder="Password" name="password" required />
              </label>

              <div class="mt-5">
                <input type="submit" class="btn btn-neutral btn-sm w-full">
              </div>
            </div>
          </div>
        </form>

      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>

  <section>
    <div class="overflow-x-auto p-5 shadow-lg m-5">
      <?php
      include('../database/models/dbconnect.php');

      // Fetch all admins from the database
      $sql = "SELECT * FROM `admin`";
      $result = mysqli_query($conn, $sql);
      ?>

      <?php
      include('../database/models/dbconnect.php');

      // Set number of records per page
      $records_per_page = 5;

      // Get the current page number from the query string, default to 1
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      if ($page < 1) {
        $page = 1;
      }

      // Calculate the offset for SQL query
      $offset = ($page - 1) * $records_per_page;

      // Fetch total number of records
      $total_sql = "SELECT COUNT(*) AS total FROM `admin`";
      $total_result = mysqli_query($conn, $total_sql);
      $total_row = mysqli_fetch_assoc($total_result);
      $total_records = $total_row['total'];
      $total_pages = ceil($total_records / $records_per_page);

      // Fetch only required records
      $sql = "SELECT * FROM `admin` LIMIT $records_per_page OFFSET $offset";
      $result = mysqli_query($conn, $sql);
      ?>

      <table class="table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td>
                  <div class="flex items-center gap-3">
                    <div class="avatar">
                      <div class="mask mask-squircle h-12 w-12">
                        <img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="Admin Image" class="object-cover w-12 h-12">
                      </div>
                    </div>
                  </div>
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                  <button class="btn btn-xs btn-warning">
                    <a href="../admin/manage_update_admin.php?adminid=<?php echo $row['id']; ?>">Edit</a>
                  </button>
                  <button class="btn btn-xs btn-error">Delete</button>
                </td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="5" class="text-center text-2xl py-4 font-bold">No admin available</td>
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
              <a href="?page=<?php echo $i; ?>" class="btn btn-sm mx-2 <?php echo ($i == $page) ? 'btn-active' : ''; ?>">
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
        preview.src = ""; // Clear the preview if no file is selected
      }
    }
  </script>
</body>

</html>