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
    header('Location: subject_create.php');
    exit(); // Always call exit after header redirect
  }

  // Update action
  if ($action == "update" && !empty($subjectId) && !empty($subject) && !empty($subjectDepartmentId)) {
    updateSubject($subjectId, $subject, $subjectType, $subjectDepartmentId); // Include department
    header('Location: subject_create.php');
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($subjectId)) {
    deleteSubject($subjectId); // Correct function name
    header('Location: subject_create.php');
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
  header('Location: subject_create.php');
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
  $query = "SELECT department_name FROM tbldepartment WHERE department_id = '$departmentId'";
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
  <div class="z-10 px-20">
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
          class="border px-4 py-3 w-[400px] rounded-md text-lg"
          required>

        <select name="subject_type" class="border px-4 py-3 w-[150px] rounded-md text-lg">
          <option value="Major" <?php echo ($subjectType == 'Major') ? 'selected' : ''; ?>>Major</option>
          <option value="Minor" <?php echo ($subjectType == 'Minor') ? 'selected' : ''; ?>>Minor</option>
        </select>

        <select name="department_id" class="border px-4 py-3 w-[150px] rounded-md text-lg" required>
          <option value="">Select Department</option>
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
          class="px-4 py-3 rounded-md text-white text-lg bg-blue-900 hover:bg-blue-500 cursor-pointer">


      </div>
    </form>

    <!-- Where the subject will be displayed -->
    <div id="subjectlist" class="overflow-x-auto mt-4">
      <?php if (count($subjectList) > 0): ?>
        <table class="table-auto w-full border shadow">
          <thead class="border bg-blue-900">
            <tr class="bg-gray-100 text-left">
              <th class="px-4 py-2 text-start">Subject Name</th>
              <th class="px-4 py-2 text-start">Type</th>
              <th class="px-4 py-2 text-start">Department</th>
              <th class="px-4 py-2 text-start">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subjectList as $listsubject):
              $department = getDepartmentName($listsubject['department_id']);
            ?>
              <tr class="border-b hover:bg-pink-50">
                <td class="px-4 py-2 text-start border"><?php echo htmlspecialchars($listsubject['subject_name']); ?></td>
                <td class="px-4 py-2 text-start border"><?php echo htmlspecialchars($listsubject['subject_type']); ?></td>
                <td class="px-4 py-2 text-start border"><?php echo htmlspecialchars($department['department_name']); ?></td>
                <td class="px-4 py-2 text-start border">
                  <div class="flex justify-start gap-4">
                    <a href="?edit=<?php echo $listsubject['subject_id']; ?>"
                      class="inline-flex items-center justify-center bg-blue-900 hover:bg-blue-500 text-white px-4 py-2 rounded-md" title='Update'>
                      <img src="../admin/Images/update.svg" alt="Update" class="w-5 h-5" title="Edit">

                    </a>
                    <a href="?delete=<?php echo $listsubject['subject_id']; ?>"
                      onclick="return confirm('Are you sure you want to delete this subject?');"
                      class="inline-flex items-center justify-center bg-red-900 hover:bg-red-500 text-white px-4 py-2 rounded-md" title='Delete'>
                      <img src="../admin/Images/delete.svg" alt="Delete" class="w-5 h-5" title="Delete">
                    </a>
                  </div>
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
</body>

</html>

<?php
// End output buffering and flush the output
ob_end_flush();
?>