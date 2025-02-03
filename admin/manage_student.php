<?php /*
include('../database/models/dbconnect.php');
session_start();
?>
<?php
$sql = "SELECT s.student_id, s.school_id, s.name, s.email, s.year_level, s.image, 
d.department_name, sec.section_name, ss.is_regular
FROM tblstudent s
LEFT JOIN `tbldepartment` d ON s.department_id = d.department_id
LEFT JOIN `tblstudent_section` ss ON s.student_id = ss.student_id
LEFT JOIN `tblsection` sec ON ss.section_id = sec.section_id";

// Check if search term exists and modify the query accordingly
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql .= " WHERE s.name LIKE '%$searchTerm%' OR s.school_id LIKE '%$searchTerm%'";
}

$result = $conn->query($sql);*/

include('../database/models/dbconnect.php');
session_start();

// Pagination settings
$results_per_page = 10;
if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
$start_from = ($page - 1) * $results_per_page;

$sql = "SELECT s.student_id, s.school_id, s.name, s.email, s.year_level, s.image, 
        d.department_name, sec.section_name, ss.is_regular
        FROM tblstudent s
        LEFT JOIN `tbldepartment` d ON s.department_id = d.department_id
        LEFT JOIN `tblstudent_section` ss ON s.student_id = ss.student_id
        LEFT JOIN `tblsection` sec ON ss.section_id = sec.section_id";

// Search functionality with pagination
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql .= " WHERE s.name LIKE '%$searchTerm%' OR s.school_id LIKE '%$searchTerm%'";
}

$sql_with_limit = $sql . " LIMIT $start_from, $results_per_page"; // Add LIMIT clause for pagination
$result = $conn->query($sql_with_limit);

// Get total number of results for pagination
$total_results = $conn->query($sql);
$total_pages = ceil($total_results->num_rows / $results_per_page);
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Table</title>

</head>

<body>


  <div class="m-5 p-4">
    <section class="m-5">
      <!-- Open the modal using ID.showModal() method -->
      <button class="btn btn-sm btn-neutral" onclick="my_modal_2.showModal()">Add Student</button>
      <dialog id="my_modal_2" class="modal">
        <div class="modal-box w-11/12 max-w-5xl bg-primary-content">
          <h3 class="text-lg text-white font-bold">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 16 16"
              fill="currentColor"
              class="h-8 w-8 opacity-70 animate-bounce">
              <path
                d="M8 .5a1 1 0 0 1 .515.14l5 3A1 1 0 0 1 14 4.5v3a7.5 7.5 0 0 1-6.22 7.426 1 1 0 0 1-.56 0A7.5 7.5 0 0 1 1 7.5v-3a1 1 0 0 1 .485-.86l5-3A1 1 0 0 1 8 .5Zm0 1.234L3 4.18V7.5a6.5 6.5 0 0 0 5 6.326A6.5 6.5 0 0 0 13 7.5V4.18l-5-2.446Z" />
            </svg>
            Student Details
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
                    class="px-20 py-3 border text-white rounded cursor-pointer shadow relative">
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
                      <input type="text" class="grow" placeholder="School ID" minlength="7" maxlength="7" name="school_id"
                        autocomplete="off" value="<?php echo isset($schoolId); ?>" />
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
                      <label class="text-lg text-white">Last Name</label>
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
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-lg text-white">Email</label>
                    </div>
                  </div>
                  <div>
                    <!-- Email -->
                    <label class="input input-bordered flex items-center gap-2 mt-3">
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
                      <input type="text" class="grow" placeholder="Email" name="email" autocomplete="off" />
                    </label>
                    <input
                      class="w-full px-3 py-2 border-s-4 shadow text-white border-blue-900 rounded-sm"
                      type="hidden"
                      name="password"
                      autocomplete="off" value="<?php echo isset($schoolId); ?>" readonly>
                  </div>
                </div>
                <div class="mt-8">
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-lg text-white">Year</label>
                    </div>
                  </div>
                  <!-- Year -->
                  <label class="input input-bordered flex items-center gap-2 mt-3">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 16 16"
                      fill="currentColor"
                      class="h-4 w-4 opacity-70">
                      <path
                        d="M3 0a1 1 0 0 1 1 1v1h8V1a1 1 0 0 1 2 0v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1V1a1 1 0 0 1 1-1zm0 3v10h10V3H3z" />
                    </svg>
                    <input type="text" class="grow" placeholder="Year" name="year" />
                  </label>
                  <div class="mt-2 flex flex-col justify-center items-startw-full">
                    <div class="m-1 flex justify-between items-center">
                      <div>
                        <label class="text-lg text-white">Department</label>
                      </div>

                    </div>
                    <div>
                      <select class="select select-bordered w-full max-w-xs"
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
                    <div class="m-1 flex justify-between items-center">
                      <div>
                        <label class="text-lg text-white">Section</label>
                      </div>
                    </div>
                    <div>
                      <select class="select select-bordered w-full max-w-xs" name="section_id" id="section">
                        <option value="" disabled selected>Select Section</option>
                        <?php
                        $section = $conn->query("SELECT * FROM tblsection");
                        while ($row = $section->fetch_assoc()): ?>
                          <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="m-1 flex justify-start items-center">
                      <div>
                        <label class="text-lg text-white">Is Regular</label>
                      </div>
                      <div class="mt-2 mx-3">
                        <input
                          type="checkbox"
                          class="w-full rounded-sm"
                          placeholder="Section"
                          name="is_regular"
                          value="1">
                      </div>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div>
                      <button type="submit" name="submit"
                        class="px-8 py-2 w-full btn  btn-primary rounded-md">
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
              <button class="btn">Close</button>
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
              <h1 class="font-semibold float-left sm:text-4xl md:text-4xl">Student Table</h1>
            </div>
          </div>
          <div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
              <div class="flex justify-evenly items-center gap-3">
                <div>
                  <input
                    type="text"
                    name="search"
                    placeholder="Search Teacher"
                    class="input input-bordered w-full max-w-xs" />
                </div>
                <div>
                  <input
                    type="submit"
                    value="Search"
                    name="enter"
                    class="px-8 py-3 rounded-md cursor-pointer btn btn-md btn-outline">
                </div>
              </div>
            </form>

          </div>
        </div>

      </div>
    </section>
    <section>
      <div class="overflow-y-auto m-3">
        <table class="table border border-2 shadow">
          <thead>
            <tr class="text-center">
              <th>School ID</th>
              <th>Profile</th>
              <th>Name</th>
              <th>Email</th>
              <th>Department</th>
              <th>Section</th>
              <th>Year</th>
              <th>Is_Regular</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-100 cursor-pointer text-center">
                  <td class="border"><?php echo htmlspecialchars($row['school_id']); ?></td>
                  <td class="border">
                    <div class="flex justify-center items-center">
                      <div>
                        <?php if ($row['image']): ?>
                          <img src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>" alt="Student Image"
                            class="w-10 h-10 rounded-md">
                        <?php else: ?>
                          No Image
                        <?php endif; ?>
                      </div>

                    </div>
                  </td>
                  <td class="border"><?php echo htmlspecialchars($row['name']); ?></td>
                  <td class="border"><?php echo htmlspecialchars($row['email']); ?></td>
                  <td class="border"><?php echo htmlspecialchars($row['department_name']); ?></td>
                  <td class="border"><?php echo htmlspecialchars($row['section_name']); ?></td>
                  <td class="border"><?php echo htmlspecialchars($row['year_level']); ?></td>
                  <td class="border"><?php echo $row['is_regular'] ? 'Yes' : 'No'; ?></td>
                  <td class="px-4 py-2 text-center border hover:bg-red-900 hover:text-lg hover:text-white">
                    <div class="flex justify-center items-center gap-3">
                      <!-- <div class="w-full">
                        <a href="#view">
                          <img src="../admin/tools/Images/update.svg" alt="School ID"
                            class="h-8 px-2 rounded-md py-1 bg-green-900 hover:bg-green-500 top-1 left-8 w-full">
                        </a>
                      </div>
                      <div class="w-full">
                        <a href="#update">
                          <img src="../admin/tools/Images/update.svg" alt="School ID"
                            class="h-8 px-2 rounded-md py-1 bg-blue-900 hover:bg-blue-500 top-1 left-8 w-full">
                        </a>
                      </div> -->
                      <div>
                        <a href=" #delete">Delete</a>
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
      </div>
    </section>
    <div class="m-5 p-4">
      <section>
        <div class="m-5">
          <div class="flex justify-between items-center">
          </div>
        </div>
      </section>

      <section>
        <div class="overflow-y-auto m-3">
          <table class="table border border-2 shadow">
            <thead>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr class="border-b hover:bg-gray-100 cursor-pointer">
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <!-- <tr>
                  <td colspan="9">No students found.</td>
                </tr> -->
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <div class="flex justify-center mt-4">
        <div class="btn-group">
          <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php if (isset($_GET['search'])) echo '&search=' . $_GET['search']; ?>" class="btn">«</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php if (isset($_GET['search'])) echo '&search=' . $_GET['search']; ?>" class="btn <?php if ($i == $page) echo 'btn-active'; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php if (isset($_GET['search'])) echo '&search=' . $_GET['search']; ?>" class="btn">»</a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.tailwindcss.com"></script>
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

    document.addEventListener("DOMContentLoaded", function() {
      const sectionSelect = document.getElementById("section");
      const isRegularCheckbox = document.querySelector('input[name="is_regular"]');

      // Hide the section dropdown if the student is irregular
      function toggleSectionVisibility() {
        if (isRegularCheckbox.checked) {
          sectionSelect.disabled = false; // Enable section selection if regular
        } else {
          sectionSelect.disabled = true; // Disable section selection if irregular
        }
      }

      // Initialize visibility based on the checkbox state
      toggleSectionVisibility();

      // Listen for changes to the checkbox
      isRegularCheckbox.addEventListener('change', toggleSectionVisibility);
    });
  </script>
</body>

</html>
<?php

if (isset($_POST['section_id'])) {
  $section_id = $_POST['section_id'];
} else {
  // Handle the case where section_id is not set
  $section_id = null; // Or set to a default value, or handle the error
}

function addStudent($conn, $school_id, $fname, $lname, $email, $department_id, $year, $section_id, $is_regular, $password, $file)
{
  // Combine first and last names
  $name = $fname . " " . $lname;

  // Set default password as school_id
  $default_password = $school_id;

  // Password verification
  if ($password === $default_password) {
    echo 'Your password is ' . $default_password;
    return;
  }

  // Check if an image file is uploaded
  if ($file['error'] === 4) {
    echo "<script>alert('Image not exist');</script>";
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
    echo "<script>alert('Invalid extension');</script>";
    return;
  } elseif ($imgsize > 1000000) {
    echo "<script>alert('Image is too large');</script>";
    return;
  }

  // Create a unique filename and move the file
  $newimg = uniqid() . '.' . $imgEx;
  if (!move_uploaded_file($imgtmp, "../upload/pics/$newimg")) {
    echo "<script>alert('Image upload failed');</script>";
    return;
  }

  // Prepared statement to insert student data
  $sql = "INSERT INTO tblstudent (school_id, name, email, password, department_id, year_level, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

  if ($stmt = $conn->prepare($sql)) {
    // Bind parameters to the prepared statement
    $stmt->bind_param('sssssss', $school_id, $name, $email, $default_password, $department_id, $year, $newimg);

    // Execute the prepared statement
    if ($stmt->execute()) {
      // Get the student_id of the newly inserted student
      $student_id = $stmt->insert_id;

      // Handle irregular students (if no section)
      if ($is_regular) {
        // Insert into tblstudent_section if regular student and section is selected
        if (isset($section_id) && !empty($section_id)) {
          $stmt_section = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, is_regular) VALUES (?, ?, ?)");
          $stmt_section->bind_param("iii", $student_id, $section_id, $is_regular);

          if ($stmt_section->execute()) {
            echo "<script>alert('Student created successfully!');</script>";
            echo "<script>
                  window.location.href='../admin/manage_student.php'; 
                  </script>";
          } else {
            echo "Error: " . $stmt_section->error;
          }

          // Close the stmt_section
          $stmt_section->close();
        } else {
          echo "<script>alert('Section is required for regular students.');</script>";
        }
      } else {
        // If the student is irregular, insert into tblstudent_section with section_id = 0 and is_regular = 0
        $section_id = 0;  // Irregular student doesn't have a section
        $stmt_section = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, is_regular) VALUES (?, ?, ?)");
        $stmt_section->bind_param("iii", $student_id, $section_id, $is_regular);

        if ($stmt_section->execute()) {
          echo "<script>
                window.location.href='../admin/manage_student.php'; 
                </script>";

          echo " <div class='alert alert-success'>
                <span>Irregular student added successfully without section!</span>
              </div>";
        } else {
          echo "Error: " . $stmt_section->error;
        }
        // Close the stmt_section
        $stmt_section->close();
      }
    } else {
      echo "Error: " . $stmt->error;
    }
    // Close the statement
    $stmt->close();
  } else {
    echo "<script>alert('Error: Could not prepare query.');</script>";
  }
}


// Call the function with form data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : null; // Check if section_id is provided
  addStudent(
    $conn,
    $_POST['school_id'],
    $_POST['fname'],
    $_POST['lname'],
    $_POST['email'],
    $_POST['department_id'],
    $_POST['year'],
    $section_id,
    isset($_POST['is_regular']) ? 1 : 0,
    $_POST['password'],
    $_FILES['hen']

  );
}
?>