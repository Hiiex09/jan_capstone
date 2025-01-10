<?php
include("../database/models/dbconnect.php");
session_start();

$selectedCriteria = "";
$criteriaId = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
  $criteria = filter_input(INPUT_POST, 'criteria', FILTER_SANITIZE_STRING);
  $criteriaId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

  if ($action === "create" && !empty($criteria)) {
    createCriteria($criteria);
  } elseif ($action === "update" && !empty($criteriaId) && !empty($criteria)) {
    updateCriteria($criteriaId, $criteria);
  } elseif ($action === "delete" && !empty($criteriaId)) {
    deleteCriteria($criteriaId);
  }
  header('Location: criteria.php');
  exit();
}

// Handle GET actions (edit/delete)
$criteriaId = filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_STRING);
if ($criteriaId) {
  $criteriaList = displayCriteria();
  foreach ($criteriaList as $item) {
    if ($item['criteria_id'] == $criteriaId) {
      $selectedCriteria = $item['criteria'];
      break;
    }
  }
} elseif ($deleteId = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING)) {
  deleteCriteria($deleteId);
  header('Location: criteria.php');
  exit();
}

$criteriaList = displayCriteria(); // Always fetch updated list
?>

<?php

function createCriteria($criteria)
{
  global $conn; // Access the $conn variable from the global scope
  try {

    $csql = "SELECT * FROM tblcriteria WHERE criteria =?";
    $stmtc = $conn->prepare($csql);
    $stmtc->bind_param("s", $criteria);
    $stmtc->execute();
    $stmtc->store_result();

    if ($stmtc->num_rows() > 0) {
      echo "<script>
              alert('Criteria already exists.');
              window.location.href='criteria.php';
            </script>";
    } else {
      $sql = "INSERT INTO tblcriteria (criteria) VALUES (?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $criteria);
      if ($stmt->execute()) {
        // Success message
        echo "<script>
             alert('Criteria successfully created.');
              window.location.href='criteria.php';
            </script>";
      } else {
        // Handle the failure
        echo "Error: Unable to insert criteria.";
      }
      // Close the statement
      $stmt->close();
    }
    $stmtc->close();
  } catch (mysqli_sql_exception $e) {
    // Log error and display a generic message
    error_log("Insert Failed: " . $e->getMessage());
    echo "Error during criteria creation.";
  }
}


function displayCriteria()
{
  global $conn; // Access the $conn variable from the global scope
  try {
    $sql = "SELECT criteria_id, criteria FROM tblcriteria";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $criteriaList = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $criteriaList[] = $row; // Assuming 'criteria' is the column name
      }
    }
    return $criteriaList;
  } catch (mysqli_sql_exception $e) {
    error_log("Error fetching criteria: " . $e->getMessage());
    return [];
  }
}

function updateCriteria($criteriaId, $newCriteria)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE tblcriteria SET criteria = ? WHERE criteria_id = ?");
  $stmt->bind_param("si", $newCriteria, $criteriaId); // "si" means string and integer types

  if ($stmt->execute()) {
    return true; // Update was successful
  } else {
    return false; // Update failed
  }
}

function deleteCriteria($criteriaId)
{
  global $conn;

  $stmt = $conn->prepare("DELETE FROM tblcriteria WHERE criteria_id = ?");
  $stmt->bind_param("i", $criteriaId); // "i" means integer type

  if ($stmt->execute()) {
    return true; // Deletion was successful
  } else {
    return false; // Deletion failed
  }
}

?>
<?php
include('header.php')
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Criteria</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="p-5 m-4">
    <div class="m-4">
      <h1 class="text-4xl">Create Criteria</h1>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($criteriaId); ?>">
      <input type="hidden" name="action" value="<?php echo $criteriaId ? 'update' : 'create'; ?>">
      <div>
        <input type="text" name="criteria" placeholder="Enter a criteria"
          value="<?php echo htmlspecialchars($selectedCriteria); ?>" required
          class="border px-4 py-3 w-[400px] rounded-md text-lg">
        <input type="submit" value="Submit"
          class="px-4 py-3 rounded-md text-white text-lg bg-blue-900 hover:bg-blue-500 cursor-pointer">
      </div>
    </form>

    <div id="criterialist" class="mt-4 p-2 rounded-md">
      <?php if (!empty($criteriaList)): ?>
        <ol>
          <?php foreach ($criteriaList as $index => $listCriteria): ?>
            <li class="grid grid-cols-3 gap-3 text-lg mt-1">
              <?php echo htmlspecialchars($listCriteria['criteria']); ?>
              <div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                  <label>
                    <input type="radio" name="rating[<?php echo $listCriteria['criteria_id']; ?>]" value="<?php echo $i; ?>" required>
                    <?php echo $i; ?>
                  </label>
                <?php endfor; ?>
              </div>
              <div class="flex justify-center items-center gap-2">
                <a href="?edit=<?php echo htmlspecialchars($listCriteria['criteria_id']); ?>"
                  class="bg-blue-900 hover:bg-blue-500 px-3 py-1 rounded-md">
                  <img src="../admin/tools/Images/update.svg" alt="Edit" class="w-6 h-6">
                </a>
                <a href="?delete=<?php echo htmlspecialchars($listCriteria['criteria_id']); ?>"
                  onclick="return confirm('Are you sure you want to delete this criteria?');"
                  class="bg-red-900 hover:bg-red-500 px-3 py-1 rounded-md">
                  <img src="../admin/tools/Images/delete.svg" alt="Delete" class="w-6 h-6">
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      <?php else: ?>
        <div>No Criteria Available</div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>