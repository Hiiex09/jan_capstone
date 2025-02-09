<?php
include('../database/models/dbconnect.php');
session_start();

$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$totalQuery = "SELECT COUNT(*) as total FROM tblteacher";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Modify SQL query to include LIMIT and OFFSET
$sql = "SELECT t.teacher_id, t.school_id, t.name, t.image, d.department_name
FROM tblteacher t
LEFT JOIN tbldepartment d ON t.department_id = d.department_id
LEFT JOIN tblteacher_section ts ON t.teacher_id = ts.teacher_id
LEFT JOIN tblsection sec ON ts.section_id = sec.section_id";

// Check if search term exists
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql .= " WHERE t.name LIKE '%$searchTerm%' OR t.school_id LIKE '%$searchTerm%'";
}

// Add pagination
$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>


  <div class="p-5 m-5 bg-base-300 rounded-md">
    <section class="m-5">
      <!-- Open the modal using ID.showModal() method -->
      <button class="btn btn-sm btn-neutral" onclick="my_modal_2.showModal()">Add Teacher</button>
      <dialog id="my_modal_2" class="modal">
        <div class="modal-box w-11/12 max-w-5xl bg-primary-content">
          <h3 class="text-lg font-bold text-white">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 16 16"
              fill="currentColor"
              class="h-8 w-8 opacity-70 animate-bounce">
              <path
                d="M8 .5a1 1 0 0 1 .515.14l5 3A1 1 0 0 1 14 4.5v3a7.5 7.5 0 0 1-6.22 7.426 1 1 0 0 1-.56 0A7.5 7.5 0 0 1 1 7.5v-3a1 1 0 0 1 .485-.86l5-3A1 1 0 0 1 8 .5Zm0 1.234L3 4.18V7.5a6.5 6.5 0 0 0 5 6.326A6.5 6.5 0 0 0 13 7.5V4.18l-5-2.446Z" />
            </svg>
            Teacher Profile
          </h3>
          <form
            action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            method="post"
            enctype="multipart/form-data">
            <div class="flex justify-evenly items-center">
              <div class="mt-4">
                <div class="m-1 flex justify-between items-center">
                  <div>
                    <h1 class="text-white text-2xl">Image Preview</h1>
                  </div>
                </div>
                <div>
                  <img id="image-preview"
                    src="../admin/tools/Images/def_logo.png"
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
                      <label class="text-white">School ID</label>
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
                      <input
                        type="text"
                        class="w-full"
                        placeholder="School ID"
                        minlength="7" maxlength="7" name="school_id"
                        autocomplete="off" value="<?php echo isset($schoolId); ?>"
                        pattern="\d{7}"
                        oninput="validateSchoolId(this)">
                    </label>

                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-white">First Name</label>
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
                      <input type="text" class="grow" placeholder="First Name" name="fname" autocomplete="off" />
                    </label>
                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-white">Last Name</label>
                    </div>

                  </div>
                  <div>

                    <!-- Last Name -->
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
                      <input type="text" class="grow" placeholder="Last Name" name="lname" autocomplete="off" />
                    </label>

                  </div>

                </div>
                <div class="mt-9">
                  <div class="m-1 w-full flex justify-between items-start">
                    <div>
                      <label class="text-white text-lg">Department</label>
                    </div>
                  </div>
                  <div>
                    <select class="select select-bordered w-full"
                      name="department_id"
                      required>
                      <option value="" disabled selected class="text-white">Select Department</option>
                      <?php
                      $department = $conn->query("SELECT * FROM tbldepartment");
                      while ($row = $department->fetch_assoc()): ?>
                        <option value="<?php echo $row['department_id']; ?>"><?php echo htmlspecialchars($row['department_name']); ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                  <div class="mt-8">
                    <div>
                      <button type="submit" name="submit"
                        class="w-full btn btn-md btn-outline btn-primary mt-3 rounded-md">
                        Submit
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="modal-action">
            <form method="dialog">
              <button class="btn btn-neutral">Close</button>
            </form>
          </div>
        </div>
        <form method="dialog" class="modal-backdrop">
          <button>close</button>
        </form>
      </dialog>
    </section>

    <section>
      <div class="m-5">
        <div class="flex justify-between items-center">
          <div class="flex justify-start items-center">
            <div>
              <h1 class="font-semibold float-left sm:text-4xl md:text-4xl">Teacher Table</h1>
            </div>
          </div>
          <div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
              <div class="flex justify-center item-center w-full mt-6 mb-2">
                <div class="w-full">
                  <input type="text" name="search"
                    placeholder="Search teachers name, school id..."
                    autocomplete="off" value="<?php echo isset($_GET['search']) ?
                                                htmlspecialchars($_GET['search']) : ''; ?>"
                    class="input input-bordered w-full">
                </div>
                <div class="mx-1">
                  <input
                    type="submit"
                    name="enter"
                    value="Search"
                    class="px-8 py-3 rounded-md cursor-pointer btn btn-md btn-outline">
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </section>

    <section>
      <div class=" m-3">
        <table class="table-auto w-full border shadow">
          <thead>
            <tr class="text-center h-10">
              <th class=" border">School ID</th>
              <th class=" border">Profile</th>
              <th class=" border">Name</th>
              <th class=" border">Department</th>
              <th class=" border">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="text-center">
                  <td class="border border-2"><?php echo htmlspecialchars($row['school_id']); ?></td>
                  <td class="border border-2">
                    <div class="flex justify-center item-center">
                      <div class="m-1">
                        <?php if ($row['image']): ?>
                          <img src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>"
                            alt="Student Image" class="w-10 h-10 rounded-md">
                        <?php else: ?>
                          No Image
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td class="border border-2"><?php echo htmlspecialchars($row['name']); ?></td>
                  <td class="border border-2"><?php echo htmlspecialchars($row['department_name']); ?></td>
                  <!-- <td class="text-center text-black border border-2 hidden"><?php echo htmlspecialchars($row['section_name']); ?></td> -->
                  <td class="border border-2">
                    <div class="w-full flex justify-between items-center gap-2 p-1">
                      <a href="manage_teacher_ratings.php?teacher_id=<?php echo htmlspecialchars($row['teacher_id']); ?>"
                        class="btn btn-sm btn-success flex-1">
                        View
                      </a>
                      <a href="../admin/manage_update_teacher.php?teacher_id=<?php echo htmlspecialchars($row['teacher_id']); ?>"
                        class="btn btn-sm btn-primary flex-1">
                        Update
                      </a>
                      <a href="../admin/delete.php?deleteId=<?php echo $row['teacher_id']; ?>"
                        class="btn btn-sm btn-error flex-1">
                        Delete
                      </a>
                    </div>
      </div>
      </td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="8">No students found.</td>
    </tr>
  <?php endif; ?>
  </tbody>
  </table>
  <div class="flex justify-center mt-4">
    <ul class="flex space-x-2">
      <?php if ($page > 1): ?>
        <li>
          <a href="?page=<?php echo ($page - 1); ?>" class="btn btn-sm  border rounded bg-gray-200 hover:bg-gray-300">Previous</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li>
          <a href="?page=<?php echo $i; ?>" class="btn btn-sm  border rounded <?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <li>
          <a href="?page=<?php echo ($page + 1); ?>" class="btn btn-sm  border rounded bg-gray-200 hover:bg-gray-300">Next</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>

  </div>
  </section>
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




    function validateSchoolId(input) {
      input.value = input.value.replace(/[^0-9]/g, "");
      if (input.value.length > 7) {
        input.value = input.value.slice(0, 7);
      }
    }
  </script>
</body>

</html>


<?php
function addTeacher($conn, $school_id, $fname, $lname, $department_id, $file)
{
  // Combine first and last names
  $name = $fname . " " . $lname;

  // Check if an image file is uploaded
  if ($file['error'] === 4) {
    echo "<script>
  alert('Image not exist');
</script>";
    return;
  }

  // Handle file data
  $imgname = $file['name'];
  $imgsize = $file['size'];
  $imgtmp = $file['tmp_name'];

  // Validate image extension
  $imgvalid = ['jpeg', 'jpg', 'png', 'svg'];
  $imgEx = strtolower(pathinfo($imgname, PATHINFO_EXTENSION));

  // Validate extension and size
  if (!in_array($imgEx, $imgvalid)) {
    echo "<script>
  alert('Invalid extension');
</script>";
    return;
  } elseif ($imgsize > 1000000) {
    echo "<script>
  alert('Image is too large');
</script>";
    return;
  }

  // Create a unique filename and move the file
  $newimg = uniqid() . '.' . $imgEx;
  if (!move_uploaded_file($imgtmp, "../upload/pics/$newimg")) {
    echo "<script>
  alert('Image upload failed');
</script>";
    return;
  }

  // Prepared statement to insert teacher data (without section assignment)
  $sql = "INSERT INTO tblteacher (school_id, name, department_id, image)
VALUES (?, ?, ?, ?)";

  if ($stmt = $conn->prepare($sql)) {
    // Bind parameters to the prepared statement
    $stmt->bind_param('ssss', $school_id, $name, $department_id, $newimg);

    // Execute the prepared statement
    if ($stmt->execute()) {
      echo "<script>
            window.location.href='../admin/manage_teacher.php'; 
            </script>";
    } else {
      echo "<script>
  alert('Error creating teacher: " . $stmt->error . "');
</script>";
    }

    // Close the statement
    $stmt->close();
  } else {
    echo "<script>
  alert('Error: Could not prepare query.');
</script>";
  }
}


// Call the function with form data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  addTeacher(
    $conn,
    $_POST['school_id'],
    $_POST['fname'],
    $_POST['lname'],
    $_POST['department_id'],
    $_FILES['hen']
  );
}

?>