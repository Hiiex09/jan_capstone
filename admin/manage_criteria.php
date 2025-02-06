<?php
include("../database/models/dbconnect.php");
session_start();

$selectedCriteria = "";
$criteriaId = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $action = $_POST['action'];
  $criteria = $_POST['criteria'];
  $criteriaId = $_POST['id'];

  // Create action
  if ($action == "create" && !empty($criteria)) {
    createCriteria($criteria);
    header('Location: manage_criteria.php'); // Redirect to the same page after creation
    exit();
  }

  // Update action
  if ($action == "update" && !empty($criteriaId) && !empty($criteria)) {
    updateCriteria($criteriaId, $criteria);
    header('Location: manage_criteria.php'); // Redirect to the same page after updating
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($criteriaId)) {
    deleteCriteria($criteriaId);
    header('Location: manage_criteria.php'); // Redirect to the same page after deletion
    exit();
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
              window.location.href='criteria_create.php';
            </script>";
    } else {
      $sql = "INSERT INTO tblcriteria (criteria) VALUES (?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $criteria);
      if ($stmt->execute()) {
        // Success message
        echo "<script>
             alert('Criteria successfully created.');
              window.location.href='criteria_create.php';
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

// Get criteria list for display
$criteriaList = displayCriteria();

// Check if criteria is selected for editing
if (isset($_GET['edit'])) {
  $criteriaId = $_GET['edit'];
  // Assuming displayCriteria returns an array with id and name
  foreach ($criteriaList as $criteriaItem) {
    if ($criteriaItem['criteria_id'] == $criteriaId) {
      $selectedCriteria = $criteriaItem['criteria'];
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $criteriaId = $_GET['delete'];
  deleteCriteria($criteriaId);
  header('Location: manage_criteria.php'); // Redirect to the same page after deletion
  exit();
}
?>
<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Display Criteria</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="p-2">
    <div class="m-5">
      <h1 class="text-4xl">Create Criteria</h1>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($criteriaId); ?>">
      <input type="hidden" name="action" value="<?php echo $criteriaId ? 'update' : 'create'; ?>">
      <div class="p-4">
        <div>
          <input type="text"
            name="criteria"
            placeholder="Enter a criteria"
            value="<?php echo htmlspecialchars($selectedCriteria); ?>"
            required class="input input-bordered w-full max-w-xs" />
          <input
            type="submit"
            value="Submit"
            class="btn btn-md btn-outline rounded-md cursor-pointer" /> <!-- Combined submit button -->
        </div>
      </div>
    </form>

    <!-- Where the criteria will be displayed -->
    <div id="criterialist" class="p-5">
      <?php if (count($criteriaList) > 0): ?>
        <ol>
          <?php foreach ($criteriaList as $index => $listCriteria): ?>
            <li>
              <div class="grid grid-cols-3 gap-3 text-sm mt-1">
                <?php echo htmlspecialchars($listCriteria['criteria']); ?>
                <div class="flex justify-center items-center gap-4">
                  <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label>
                      <input
                        type="radio"
                        name="rating[<?php echo $criteria; ?>][<?php echo $index; ?>]"
                        value="<?php echo $i; ?>"
                        required>
                      <?php echo $i; ?>
                    </label>
                  <?php endfor; ?>
                  <div class="flex justify-center items-center gap-2">
                    <a class="btn btn-sm btn-outline btn-success" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $listCriteria['criteria_id']; ?>">
                      Update
                    </a>
                    <a class="btn btn-sm btn-outline btn-error" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $listCriteria['criteria_id']; ?>"
                      onclick="return confirm('Are you sure you want to delete this criteria?');">
                      Remove
                    </a>
                  </div>
                </div>


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