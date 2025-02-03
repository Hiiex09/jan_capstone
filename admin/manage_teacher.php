<?php
include('../database/models/dbconnect.php');
session_start();
// include('../admin/aside.php');
?>

<?php
$sql = "SELECT t.teacher_id, t.school_id, t.name, t.image, 
d.department_name, sec.section_name
FROM tblteacher t
LEFT JOIN tbldepartment d ON t.department_id = d.department_id
LEFT JOIN tblteacher_section ts ON t.teacher_id = ts.teacher_id
LEFT JOIN tblsection sec ON ts.section_id = sec.section_id";

// Check if search term exists and modify the query accordingly
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql .= " WHERE t.name LIKE '%$searchTerm%' OR t.school_id LIKE '%$searchTerm%'";
}

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


  <div class="m-5">
    <section class="m-5">
      <!-- You can open the modal using ID.showModal() method -->
      <button class="btn btn-neutral btn-outline" onclick="my_modal_4.showModal()">Add Teacher</button>
      <dialog id="my_modal_4" class="modal">
        <div class="modal-box w-11/12 max-w-5xl bg-secondary-content">
          <h3 class="text-lg font-bold text-white">Teacher Profile</h3>
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
                    <div>
                      <span>
                        <img src="../admin/tools/Images/id.svg" alt="School ID"
                          class="w-9 h-9">
                      </span>
                    </div>
                  </div>
                  <div>
                    <input
                      type="text"
                      class="w-full px-3 py-2 border-s-8 text-black border-blue-700 rounded-sm"
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
                      class="w-full px-3 py-2 border-s-8 text-black border-blue-700 rounded-sm"
                      placeholder="First Name"
                      name="fname" autocomplete="off">
                  </div>
                  <div class="m-1 flex justify-between items-center">
                    <div>
                      <label class="text-white">Last Name</label>
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
                      class="w-full px-3 py-2 border-s-8 text-black border-blue-700 rounded-sm"
                      placeholder="Last Name"
                      name="lname" autocomplete="off">
                  </div>
                  <div class="hidden m-1 flex justify-center items-center">

                  </div>
                  <!-- <div>
                  <input
                    type="text"
                    class="hidden w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    placeholder="Email"
                    name="email" autocomplete="off">
                  <input
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    type="hidden"
                    name="password"
                    autocomplete="off" value="<?php echo isset($schoolId); ?>" readonly>
                </div> -->
                </div>
                <div class="mt-5">
                  <!-- <div class="hidden m-1 flex justify-start items-center">
                  <label>Year Level</label>
                </div>
                <div class="m-1 flex justify-start items-center">
                  <input
                    type="text"
                    class="hidden w-full text-black px-3 py-2 border-s-4 border-blue-900 rounded-sm"
                    placeholder="Year Level"
                    name="year" autocomplete="off">
                </div> -->
                  <div class="mt-3 flex flex-col justify-center items-start">
                    <div class="m-1 w-full flex justify-between items-start">
                      <div>
                        <label class="text-white text-lg">Department</label>
                      </div>
                      <div>
                        <span>
                          <img src="../admin/tools/Images/department.svg" alt="School ID"
                            class="w-9 h-9">
                        </span>
                      </div>
                    </div>
                    <div>
                      <select
                        name="department_id"
                        required
                        class="px-3 py-2 w-full border-s-8 border-blue-700 text-black rounded-sm cursor-pointer">
                        <option value="" disabled selected>Select Department</option>
                        <?php
                        $department = $conn->query("SELECT * FROM tbldepartment");
                        while ($row = $department->fetch_assoc()): ?>
                          <option value="<?php echo $row['department_id']; ?>"><?php echo htmlspecialchars($row['department_name']); ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>

                    <!-- <div class="hidden m-1 flex justify-start items-center">
                    <label>Section</label>
                  </div>
                  <div>
                    <select name="section_id" id="section" class="hidden w-full text-black px-3 py-2 
                      border-s-4 border-blue-900 rounded-sm cursor-pointer" required>
                      <option value="" disabled selected>Select Section</option>
                      <?php
                      $section = $conn->query("SELECT * FROM tblsection");
                      while ($row = $section->fetch_assoc()): ?>
                        <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div> -->

                    <!-- <div class="hidden m-1 flex justify-start items-center">
                    <label class="text-lg">Is Regular: </label>
                    <div class="mx-2 mt-1">
                      <input
                        type="checkbox"
                        class="hidden w-full text-black border-s-4 border-blue-900 rounded-sm cursor-pointer"
                        placeholder="Section"
                        name="is_regular"
                        value="1">
                    </div>
                  </div> -->

                  </div>
                  <div class="mt-8">

                    <div>
                      <button type="submit" name="submit"
                        class="w-full relative text-center text-white btn btn-sm btn-outline btn-primary mt-3 rounded-md hover:border-s-4 border-white">
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
                    class="px-8 py-3 rounded-md cursor-pointer bg-blue-900 text-white">
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </section>

    <section>
      <div class="overflow-x-auto m-3">
        <table class="table-auto w-full border shadow">
          <thead class=" bg-blue-900 text-white">
            <tr>
              <th class="px-4 py-2 text-center">School ID</th>
              <th class="px-4 py-2 text-center">Profile</th>
              <th class="px-4 py-2 text-center">Name</th>
              <th class="px-4 py-2 text-center">Department</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-pink-50">
                  <td class="text-center text-black border"><?php echo htmlspecialchars($row['school_id']); ?></td>
                  <td class="text-center text-black border">
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
                  <td class="text-center text-black border"><?php echo htmlspecialchars($row['name']); ?></td>
                  <td class="text-center text-black border"><?php echo htmlspecialchars($row['department_name']); ?></td>
                  <!-- <td class="text-center text-black border hidden"><?php echo htmlspecialchars($row['section_name']); ?></td> -->
                  <td class="text-center text-black border">
                    <div class="w-full flex justify-center items-center">
                      <div class="m-1 w-full">
                        <a href="manage_teacher_ratings.php?teacher_id=<?php echo htmlspecialchars($row['teacher_id']); ?>">
                          <img src="../admin/tools/Images/view.svg" alt="School ID"
                            class="w-full h-8 px-2 rounded-md py-1 bg-green-900 hover:bg-green-500 top-1 left-8">
                        </a>
                      </div>
                      <div class="m-1 w-full">
                        <a href="#">
                          <img src="../admin/tools/Images/update.svg" alt="School ID"
                            class="w-full h-8 px-2 rounded-md py-1 bg-blue-900 hover:bg-blue-500 top-1 left-8">
                        </a>
                      </div>
                      <div class="m-1 w-full">
                        <a href="../admin/delete.php?deleteId=<?php echo $row['teacher_id']; ?>">
                          <img src="../admin/tools/Images/delete.svg" alt="Delete Teacher"
                            class="w-full h-8 px-2 rounded-md py-1 bg-red-900 hover:bg-red-500 top-1 left-8">
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