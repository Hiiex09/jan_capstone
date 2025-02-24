<?php
// Start output buffering to prevent "headers already sent" issue
ob_start();

// Include necessary files
include("../database/models/dbconnect.php");
include("./manage_subject_config.php");
session_start();

// Initialize variables
$selectedSubject = "";
$subjectId = "";
$subjectType = "";
$subjectDepartmentId = "";

// Fetch departments from the database
$departments = getDepartments(); // Function to fetch departments

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $action = isset($_POST['action']) ? $_POST['action'] : '';
  $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
  $subjectType = isset($_POST['subject_type']) ? $_POST['subject_type'] : 'Minor'; // Default to Minor
  $subjectId = isset($_POST['id']) ? $_POST['id'] : '';
  $subjectDepartmentId = isset($_POST['department_id']) ? $_POST['department_id'] : ''; // Get department

  // Create action
  if ($action == "create" && !empty($subject) && !empty($subjectDepartmentId)) {
    createSubject($subject, $subjectType, $subjectDepartmentId); // Include department
    header('Location: manage_subject.php');
    exit(); // Always call exit after header redirect
  }

  // Update action
  if ($action == "update" && !empty($subjectId) && !empty($subject) && !empty($subjectDepartmentId)) {
    updateSubject($subjectId, $subject, $subjectType, $subjectDepartmentId); // Include department
    header('Location: manage_subject.php');
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($subjectId)) {
    deleteSubject($subjectId); // Correct function name
    header('Location: manage_subject.php');
    exit();
  }
}

// Get subject list for display
$subjectList = displaySubject(); // Correct function name

// Check if subject is selected for editing
if (isset($_GET['edit'])) {
  $subjectId = $_GET['edit'];
  foreach ($subjectList as $subjectItem) {
    if ($subjectItem['subject_id'] == $subjectId) {
      $selectedSubject = $subjectItem['subject_name'];
      $subjectType = $subjectItem['subject_type']; // Fetch subject type
      $subjectDepartmentId = $subjectItem['department_id']; // Fetch subject department
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $subjectId = $_GET['delete'];
  deleteSubject($subjectId); // Correct function name
  header('Location: manage_subject.php');
  exit();
}

// Function to fetch departments from the database
function getDepartments()
{
  global $conn;
  $query = "SELECT department_id, department_name FROM tbldepartment";
  $result = mysqli_query($conn, $query);
  $departments = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $departments[] = $row;
  }
  return $departments;
}

// Function to get department name based on department ID
function getDepartmentName($departmentId)
{
  global $conn;
  $query = "SELECT department_name FROM `tbldepartment` WHERE department_id = '$departmentId'";
  $result = mysqli_query($conn, $query);
  return mysqli_fetch_assoc($result);
}

?>
<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Display subject</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>


<body>
  <div class="z-10 p-5 rounded-md shadow border m-10">
    <div class="mt-5 mb-4">
      <h1 class="text-3xl">Create Subject</h1>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($subjectId); ?>">
      <input type="hidden" name="action" value="<?php echo $subjectId ? 'update' : 'create'; ?>">

      <div>
        <input
          type="text"
          name="subject"
          placeholder="Enter a subject"
          value="<?php echo htmlspecialchars($selectedSubject); ?>"
          class="input input-bordered w-full max-w-sm"
          required />

        <select name="subject_type" class="select select-bordered w-full max-w-xs" required>
          <option value="Major" <?php echo ($subjectType == 'Major') ? 'selected' : ''; ?>>Major</option>
          <option value="Minor" <?php echo ($subjectType == 'Minor') ? 'selected' : ''; ?>>Minor</option>
        </select>

        <select name="department_id" class="select select-bordered w-full max-w-xs" required>
          <option disabled selected>Select Department</option>
          <?php foreach ($departments as $department): ?>
            <option value="<?php echo htmlspecialchars($department['department_id']); ?>"
              <?php echo ($department['department_id'] == $subjectDepartmentId) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($department['department_name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input
          type="submit"
          value="Submit"
          class="btn btn-md btn-neutral btn-outline cursor-pointer">
      </div>
    </form>

    <!-- Where the subject will be displayed -->
    <div id="subjectlist" class="overflow-x-auto mt-4">
      <?php if (count($subjectList) > 0): ?>
        <div class="overflow-x-auto">
          <table class="table">
            <!-- head -->
            <thead>
              <tr>
                <th>Subject Name</th>
                <th class="text-center">Type</th>
                <th class="text-center">Department</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($subjectList as $listsubject):
                $department = getDepartmentName($listsubject['department_id']);
              ?>
                <tr class="hover">
                  <td><?php echo htmlspecialchars($listsubject['subject_name']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($listsubject['subject_type']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($department['department_name']); ?></td>
                  <td class="text-center">
                    <a href="?edit=<?php echo $listsubject['subject_id']; ?>"
                      class="btn btn-sm btn-success btn-outline" title='Update'> Edit
                    </a>
                    <a href="?delete=<?php echo $listsubject['subject_id']; ?>"
                      onclick="return confirm('Are you sure you want to delete this subject?');"
                      class="btn btn-sm btn-error btn-outline" title='Delete'> Delete

                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="text-center text-lg text-gray-500 mt-6">No subject Available</div>
        <?php endif; ?>
        </div>
    </div>
  </div>
</body>

</html>

<?php
// End output buffering and flush the output
ob_end_flush();
?>