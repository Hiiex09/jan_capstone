<?php
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

$result = $conn->query($sql);
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
      <!-- You can open the modal using ID.showModal() method -->
      <button class="btn btn-neutral btn-outline" onclick="my_modal_4.showModal()">Add Student</button>
      <dialog id="my_modal_4" class="modal">
        <div class="modal-box w-11/12 max-w-5xl bg-slate-900">
          <h3 class="text-lg text-white font-bold">Student Details</h3>
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
                    <div>
                      <span>
                        <img src="../admin/tools/Images/id.svg" alt="School ID"
                          class="w-7 h-7">
                      </span>
                    </div>
                  </div>
                  <div>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border-s-4 shadow text-black border-blue-900 rounded-sm"
                      placeholder="School ID"
                      minlength="7" maxlength="7" name="school_id"
                      autocomplete="off" value="<?php echo isset($schoolId); ?>">
                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-white">First Name</label>
                    </div>
                    <div>
                      <span>
                        <img src="../admin/tools/Images/user.svg" alt="School ID"
                          class="w-7 h-7">
                      </span>
                    </div>
                  </div>
                  <div>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border-s-4 shadow text-black border-blue-900 rounded-sm"
                      placeholder="First Name"
                      name="fname" autocomplete="off">
                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-lg text-white">Last Name</label>
                    </div>
                    <div>
                      <span>
                        <img src="../admin/tools/Images/user.svg" alt="School ID"
                          class="w-7 h-7">
                      </span>
                    </div>
                  </div>
                  <div>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border-s-4 shadow text-black border-blue-900 rounded-sm"
                      placeholder="Last Name"
                      name="lname" autocomplete="off">
                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-lg text-white">Email</label>
                    </div>
                    <div>
                      <span>
                        <img src="../admin/tools/Images/email.svg" alt="School ID"
                          class="w-7 h-7">
                      </span>
                    </div>
                  </div>
                  <div>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border-s-4 shadow text-black border-blue-900 rounded-sm"
                      placeholder="Email"
                      name="email" autocomplete="off">
                    <input
                      class="w-full px-3 py-2 border-s-4 shadow text-black border-blue-900 rounded-sm"
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
                    <div>
                      <span>
                        <img src="../admin/tools/Images/number.svg" alt="School ID"
                          class="w-7 h-7">
                      </span>
                    </div>
                  </div>
                  <div class="m-1 flex justify-start items-center">
                    <input
                      type="text"
                      class="w-full text-black px-3 py-2 border-s-4 shadow border-blue-900 rounded-sm"
                      placeholder="Year"
                      name="year">
                  </div>
                  <div class="mt-2 flex flex-col justify-center items-startw-full">
                    <div class="m-1 flex justify-between items-center">
                      <div>
                        <label class="text-lg text-white">Department</label>
                      </div>
                      <div>
                        <span>
                          <img src="../admin/tools/Images/department.svg" alt="School ID"
                            class="w-7 h-7">
                        </span>
                      </div>
                    </div>
                    <div>
                      <select
                        name="department_id"
                        required
                        class="px-3 py-2 w-full border-s-4 shadow border-blue-900 text-black rounded-sm">
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
                      <div>
                        <span>
                          <img src="../admin/tools/Images/section.svg" alt="School ID"
                            class="w-7 h-7">
                        </span>
                      </div>
                    </div>
                    <div>
                      <select name="section_id" id="section" class="w-full text-black px-3 py-2 
                      border-s-4 border-blue-900 rounded-sm" required>
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
                        class="px-8 py-2 w-full text-lg text-white
                         bg-blue-600 rounded-md mb-1 relative hover:border-s-4 border-white">
                        Submit
                        <img src="../admin/tools/Images/send.svg" alt="School ID"
                          class="w-7 h-7 absolute top-2 left-16">
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
                    class="px-8 py-3 rounded-md cursor-pointer bg-blue-900 text-white">
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
            <tr>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">School ID</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Profile</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Name</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Email</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Department</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Section</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Year</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Is_Regular</th>
              <th class="text-center bg-blue-900 text-white text-sm text-black font-bold">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-100 cursor-pointer">
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['school_id']); ?></td>
                  <td class="px-4 py-2 text-center border">
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
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['name']); ?></td>
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['email']); ?></td>
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['department_name']); ?></td>
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['section_name']); ?></td>
                  <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['year_level']); ?></td>
                  <td class="px-4 py-2 text-center border"><?php echo $row['is_regular'] ? 'Yes' : 'No'; ?></td>
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
  </div>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    const btnModal = document.querySelector('.js-modal');
    const modal = document.querySelector('.modal');
    const btnClose = document.querySelectorAll('.js-close');

    btnModal.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });


    btnClose.forEach((button) => {
      button.addEventListener('click', () => {
        modal.classList.add('hidden'); // Nya diri mawala ang modal
      });
    });


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