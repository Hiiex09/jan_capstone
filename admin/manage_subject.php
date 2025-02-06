<?php
include("../database/models/dbconnect.php");
session_start();

$selectedSubject = "";
$subjectId = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Check if the keys exist before accessing them
  $action = isset($_POST['action']) ? $_POST['action'] : '';
  $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
  $subjectId = isset($_POST['id']) ? $_POST['id'] : '';

  // Create action
  if ($action == "create" && !empty($subject)) {
    createsubject($subject);
    header('Location: manage_subject.php'); // Redirect to the same page after creation
    exit();
  }

  // Update action
  if ($action == "update" && !empty($subjectId) && !empty($subject)) {
    updatesubject($subjectId, $subject);
    header('Location: manage_subject.php'); // Redirect to the same page after updating
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($subjectId)) {
    deleteCriteria($subjectId);
    header('Location: manage_subject.php'); // Redirect to the same page after deletion
    exit();
  }
}

// Get subject list for display
$subjectList = displaysubject();

// Check if subject is selected for editing
if (isset($_GET['edit'])) {
  $subjectId = $_GET['edit'];
  // Assuming displaysubject returns an array with id and name
  foreach ($subjectList as $subjectItem) {
    if ($subjectItem['subject_id'] == $subjectId) {
      $selectedSubject = $subjectItem['subject_name'];
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $subjectId = $_GET['delete'];
  deletesubject($subjectId);
  header('Location: manage_subject.php'); // Redirect to the same page after deletion
  exit();
}
?>

<?php

function createSubject($subject)
{
  global $conn; // Access the $conn variable from the global scope
  try {

    $csql = "SELECT * FROM tblsubject WHERE subject_name =?";
    $stmtc = $conn->prepare($csql);
    $stmtc->bind_param("s", $subject);
    $stmtc->execute();
    $stmtc->store_result();

    if ($stmtc->num_rows() > 0) {
      echo "<script>
              alert('subject already exists.');
              window.location.href='manage_subject.php';
            </script>";
    } else {
      $sql = "INSERT INTO tblsubject (subject_name) VALUES (?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $subject);
      if ($stmt->execute()) {
        // Success message
        echo "<script>
             alert('subject successfully created.');
              window.location.href='manage_subject.php';
            </script>";
      } else {
        // Handle the failure
        echo "Error: Unable to insert subject.";
      }
      // Close the statement
      $stmt->close();
    }
    $stmtc->close();
  } catch (mysqli_sql_exception $e) {
    // Log error and display a generic message
    error_log("Insert Failed: " . $e->getMessage());
    echo "Error during subject creation.";
  }
}


function displaySubject()
{
  global $conn; // Access the $conn variable from the global scope
  try {
    $sql = "SELECT subject_id, subject_name FROM tblsubject";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjectList = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $subjectList[] = $row; // Assuming 'subject' is the column name
      }
    }
    return $subjectList;
  } catch (mysqli_sql_exception $e) {
    error_log("Error fetching subject: " . $e->getMessage());
    return [];
  }
}

function updateSubject($deptId, $newDept)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE tblsubject SET subject_name = ? WHERE subject_id = ?");
  $stmt->bind_param("si", $newDept, $deptId); // "si" means string and integer types

  if ($stmt->execute()) {
    return true; // Update was successful
  } else {
    return false; // Update failed
  }
}

function deleteSubject($deptId)
{
  global $conn;

  $stmt = $conn->prepare("DELETE FROM tblsubject WHERE subject_id = ?");
  $stmt->bind_param("i", $deptId); // "i" means integer type

  if ($stmt->execute()) {
    return true; // Deletion was successful
  } else {
    return false; // Deletion failed
  }
}

?>

<?php include('../admin/header.php'); ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Subject</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="p-3 m-3">
    <div class="p-3 m-3">
      <h1 class="text-4xl">Create subject</h1>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="p-3 m-3">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($subjectId); ?>">
      <input type="hidden" name="action" value="<?php echo $subjectId ? 'update' : 'create'; ?>">
      <div class="flex gap-3">
        <input type="text"
          name="subject"
          placeholder="Enter a subject"
          value="<?php echo htmlspecialchars($selectedSubject); ?>"
          required class="input input-bordered rounded-md w-full max-w-xs" />
        <input
          type="submit"
          value="Submit"
          class="btn btn-md btn-outline rounded-md" /> <!-- Combined submit button -->
      </div>
    </form>
    <!-- Where the subject will be displayed -->
    <div id="subjectlist" class="overflow-x-auto p-5">
      <?php if (count($subjectList) > 0): ?>
        <table class="table">
          <thead>
            <tr class="text-center">
              <th class="text-start">Subject Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subjectList as $index => $listsubject): ?>
              <tr class="hover">
                <td class="text-start"><?php echo htmlspecialchars($listsubject['subject_name']); ?></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline btn-success" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $listsubject['subject_id']; ?>">Update</a>
                  <a class="btn btn-sm btn-outline btn-error" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $listsubject['subject_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this subject?');">Remove</a>
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