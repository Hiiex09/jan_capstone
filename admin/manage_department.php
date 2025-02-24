<?php
include("../database/models/dbconnect.php");
// include('../admin/aside.php');
session_start();

$selectedDept = "";
$deptId = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $action = $_POST['action'];
  $department = $_POST['department'];
  $deptId = $_POST['id'];

  // Create action
  if ($action == "create" && !empty($department)) {
    createDepartment($department);
    header('Location:../admin/manage_department.php'); // Redirect to the same page after creation
    exit();
  }

  // Update action
  if ($action == "update" && !empty($deptId) && !empty($department)) {
    updateDepartment($deptId, $department);
    // header('Location: ../admin/department.php'); // Redirect to the same page after updating
    // echo " window.location.href='../admin/department.php'";
  }

  // Delete action
  if ($action == "delete" && !empty($deptId)) {
    deleteCriteria($deptId);
    // header('Location:../admin/department.php'); // Redirect to the same page after deletion
  }
}

// Get department list for display
$departmentList = displayDepartment();

// Check if department is selected for editing
if (isset($_GET['edit'])) {
  $deptId = $_GET['edit'];
  // Assuming displayDepartment returns an array with id and name
  foreach ($departmentList as $departmentItem) {
    if ($departmentItem['department_id'] == $deptId) {
      $selectedDept = $departmentItem['department_name'];
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $deptId = $_GET['delete'];
  deleteDepartment($deptId);
  // header('Location: ../admin/department.php'); // Redirect to the same page after deletion
  echo "<script>
        window.location.href='../admin/manage_department.php'; 
        </script>";
  exit();
}
?>
<?php
include('../admin/header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Department</title>
</head>

<body>
  <div class="p-5">
    <div>
      <h1 class="text-3xl mb-3">Manage Department</h1>
    </div>

    <div class="flex justify-end items-end p-3">
      <form
        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
        method="post"
        class="flex justify-center items-center gap-3">
        <div>
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($deptId); ?>">
          <input type="hidden" name="action" value="<?php echo $deptId ? 'update' : 'create'; ?>">
          <input type="text"
            name="department"
            placeholder="Enter a department"

            autocomplete="off"
            value="<?php echo htmlspecialchars($selectedDept); ?>" required
            class="input input-bordered w-full max-w-xs" />
        </div>
        <div>
          <input
            type="submit"
            value="Submit"
            class="btn btn-md btn-outline rounded-md cursor-pointer w-full max-w-xs" />
        </div>
      </form>
    </div>

    <!-- Where the department will be displayed -->
    <div id="departmentlist">
      <?php if (count($departmentList) > 0): ?>
        <div class="grid sm:grid-cols-1 md:grid-cols-3 gap-2">
          <?php foreach ($departmentList as $index => $listDepartment): ?>
            <div class="p-3 rounded-lg bg-base-300 skeleton">
              <div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                  class="animate-bounce">
                  <path d="M8 21h8M12 17v4M7 3h10v4a5 5 0 0 1-10 0V3z" />
                  <path d="M3 9a5 5 0 0 0 4-4M21 9a5 5 0 0 1-4-4" />
                </svg>
                <h1 class="text-2xl"><?php echo htmlspecialchars($listDepartment['department_name']); ?></h1>
              </div>
              <!-- Using links to handle editing and deleting -->
              <div class="flex justify-start items-center gap-1 mt-3">
                <a class="btn btn-sm btn-outline btn-success" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $listDepartment['department_id']; ?>">Update</a>
                <a class="btn btn-sm btn-outline btn-error" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $listDepartment['department_id']; ?>" onclick="return confirm(`Are you sure you want to delete this department ?`);">
                  Remove
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-department">No Department Available</div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>


<?php

function createDepartment($department)
{
  global $conn; // Access the $conn variable from the global scope
  try {

    $csql = "SELECT * FROM tbldepartment WHERE department_name =?";
    $stmtc = $conn->prepare($csql);
    $stmtc->bind_param("s", $department);
    $stmtc->execute();
    $stmtc->store_result();

    if ($stmtc->num_rows() > 0) {
      echo "<script>
              // alert('Department already exists.');
              window.location.href='../admin/manage_department.php';
            </script>";
    } else {
      $sql = "INSERT INTO tbldepartment (department_name) VALUES (?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $department);
      if ($stmt->execute()) {
        // Success message
        echo "<script>
             alert('Department successfully created.');
              window.location.href='../admin/manage_department.php';
            </script>";
      } else {
        // Handle the failure
        echo "Error: Unable to insert department.";
      }
      // Close the statement
      $stmt->close();
    }
    $stmtc->close();
  } catch (mysqli_sql_exception $e) {
    // Log error and display a generic message
    error_log("Insert Failed: " . $e->getMessage());
    echo "Error during department creation.";
  }
}


function displayDepartment()
{
  global $conn; // Access the $conn variable from the global scope
  try {
    $sql = "SELECT department_id, department_name FROM tbldepartment";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $departmentList = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $departmentList[] = $row; // Assuming 'department' is the column name
      }
    }
    return $departmentList;
  } catch (mysqli_sql_exception $e) {
    error_log("Error fetching department: " . $e->getMessage());
    return [];
  }
}

function updateDepartment($deptId, $newDept)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE tbldepartment SET department_name = ? WHERE department_id = ?");
  $stmt->bind_param("si", $newDept, $deptId); // "si" means string and integer types

  if ($stmt->execute()) {
    return true; // Update was successful
  } else {
    return false; // Update failed
  }
}

function deleteDepartment($deptId)
{
  global $conn;

  $stmt = $conn->prepare("DELETE FROM tbldepartment WHERE department_id = ?");
  $stmt->bind_param("i", $deptId); // "i" means integer type

  if ($stmt->execute()) {
    return true; // Deletion was successful
  } else {
    return false; // Deletion failed
  }
}

?>