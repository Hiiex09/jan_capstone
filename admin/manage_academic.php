<?php
include("../database/models/dbconnect.php");
// include('../admin/aside.php');
session_start();

// Define variables
$edit_id = null;
$edit_year = '';
$edit_semester = '';

// Handle form submission for adding or updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
  $year = $_POST['year'];
  $semester = $_POST['semester'];
  $years = (strpos($year, '-') === false) ? $year . ' - ' . ($year + 1) : $year;

  // Extract the starting year for comparison
  $start_year = explode(" - ", $years)[0];

  // Get the current year
  $current_year = date("Y");

  // Check if the start year is in the future
  if ($start_year < $current_year) {
    echo "<script>return('The school year cannot be in the past. Please select a valid year.');</script>";
  } else {
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
      // Update existing school year
      $edit_id = $_POST['edit_id'];
      $stmt = $conn->prepare("UPDATE tblschoolyear SET school_year = ?, semester = ? WHERE schoolyear_id = ?");
      $stmt->bind_param("ssi", $years, $semester, $edit_id);
      if ($stmt->execute()) {
        echo "<script>alert('School year updated successfully!'); window.location.href='manage_academic.php';</script>";
      } else {
        echo "<script>alert('Error updating school year.');</script>";
      }
      $stmt->close();
    } else {
      // Insert new school year
      $sc = $conn->prepare("SELECT COUNT(*) AS COUNT FROM tblschoolyear WHERE school_year = ?");
      $sc->bind_param('s', $years);
      $sc->execute();
      $rs = $sc->get_result();
      $r = $rs->fetch_assoc();
      $cs = $r['COUNT'];

      if ($cs < 2) {
        $stmt = $conn->prepare("INSERT INTO tblschoolyear (school_year, semester, is_status) VALUES (?, ?, 'Not Yet Started')");
        $stmt->bind_param("ss", $years, $semester);
        if ($stmt->execute()) {
          echo "<script>window.location.href='manage_academic.php'; alert('School year saved successfully!'); </script>";
        } else {
          echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
      } else {
        echo "<script>alert('The School year \"$years\" has already been added twice.');</script>";
      }
      $sc->close();
    }
  }
}

// Soft delete a school year (mark as deleted)
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $stmt = $conn->prepare("UPDATE tblschoolyear SET deleted_at = NOW() WHERE schoolyear_id = ?");
  $stmt->bind_param("i", $delete_id);
  if ($stmt->execute()) {
    echo "<script>alert('School year archived successfully!'); window.location.href='manage_academic.php';</script>";
  } else {
    echo "<script>alert('Error archiving school year.');</script>";
  }
  $stmt->close();
}


// Fetch record for editing
if (isset($_GET['editid'])) {
  $edit_id = $_GET['editid'];
  $result = $conn->query("SELECT * FROM tblschoolyear WHERE schoolyear_id = $edit_id");
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $edit_year = $row['school_year'];
    $edit_semester = $row['semester'];
  }
}

// Fetch existing school years
$school_years = $conn->query("SELECT * FROM tblschoolyear");
$conn->close();
?>



<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage School Year</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <div class="m-5 p-4 bg-base-300 rounded-md">
    <div class="flex justify-start items-center">
      <div class="m-2">
        <img
          src="../admin/tools/Images/CEC.png"
          alt="school_year-image"
          class="h-20 w-20">
      </div>
      <div class="m-1">
        <h1 class="text-6xl">Manage School Year</h1>
      </div>
    </div>
    <form action="" method="POST">
      <div class="flex flex-row justify-start items-center gap-2 mt-5">
        <div>
          <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
          <label for="year" class="text-lg">Academic Year</label>
          <input type="text"
            id="year"
            name="year"
            required
            placeholder="Enter Academic Year (e.g., 2024 - 2025)"
            value="<?php echo $edit_year; ?>"
            oninput="validateYearInput(this)"
            pattern="\d{4} - \d{4}"
            class="input input-bordered w-full max-w-xs" />
        </div>
        <div>
          <label for="semester" class="text-lg">Semester</label>
          <select id="semester"
            name="semester"
            required
            class="select select-bordered w-full max-w-xs">
            <option value="1" <?php echo ($edit_semester == '1') ? 'selected' : ''; ?>>First Semester</option>
            <option value="2" <?php echo ($edit_semester == '2') ? 'selected' : ''; ?>>Second Semester</option>
          </select>
        </div>
        <div class="mt-7">
          <button
            type="submit"
            name="save"
            class="btn btn-md btn-outline w-full max-w-xs px-5">
            <?php echo $edit_id ? 'Update' : 'Submit'; ?>
          </button>
        </div>
      </div>
    </form>

    <div class="mt-4">
      <h2 class="text-2xl">Existing School Years</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="table">
        <!-- head -->
        <thead>
          <tr>
            <th class="text-center">Academic Year</th>
            <th class="text-center">Semester</th>
            <th class="text-center">Default</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          while ($row = $school_years->fetch_assoc()):
            $active = $row['is_default'];
          ?>
            <tr>
              <td class="text-center"><b><?php echo $row['school_year']; ?></b></td>
              <td class="text-center"><b><?php echo $row['semester']; ?></b></td>
              <td class="text-center">
                <button class="btn btn-sm btn-outline btn-error" onclick="toggleActive(<?php echo $row['schoolyear_id']; ?>, '<?php echo $active; ?>')">
                  <?php echo $active === 'Yes' ? 'Yes' : 'No'; ?>
                </button>
              </td>
              <td class="flex justify-center items-center">
                <select id="status-<?php echo $row['schoolyear_id']; ?>" onchange="updateStatus(<?php echo $row['schoolyear_id']; ?>, this.value)" <?php echo $active === 'Yes' ? '' : 'disabled'; ?>
                  class="select select-bordered w-full">
                  <option value="Not Yet Started" <?php echo $row['is_status'] === 'Not Yet Started' ? 'selected' : ''; ?>>Not Yet Started</option>
                  <option value="Started" <?php echo $row['is_status'] === 'Started' ? 'selected' : ''; ?>>Started</option>
                  <option value="Closed" <?php echo $row['is_status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
              </td>
              <td class="text-center">
                <a class="btn btn-sm btn-outline btn-success m-1" href="?editid=<?php echo $row['schoolyear_id']; ?>">Update</a>
                <a class="btn btn-sm btn-outline btn-error m-1" href="?delete_id=<?php echo $row['schoolyear_id']; ?>" onclick="return confirm('Are you sure you want to delete this school year?')">Remove</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <table class="table-auto w-full border shadow mt-3">



    </table>
  </div>


  <script>
    function toggleActive(id, currentStatus) {
      const newStatus = currentStatus === 'Yes' ? 'No' : 'Yes';
      const statusDropdown = document.getElementById('status-' + id);

      // Create the AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '../admin/academic.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        if (this.status === 200) {
          // Refresh to reflect the changes after the request completes successfully
          location.reload();
        } else {
          console.error('Error toggling active status:', this.responseText);
        }
      };

      if (newStatus === 'Yes') {
        // Set this school year as active, reset others to inactive, and enable the dropdown
        xhr.send(`schoolyear_id=${id}&status=${newStatus}&set_single_active=1`);
        statusDropdown.disabled = false;
      } else {
        // Set this school year to inactive, reset status to 'Not Yet Started', and disable the dropdown
        xhr.send(`schoolyear_id=${id}&status=${newStatus}&reset_status=1`);
        statusDropdown.value = 'Not Yet Started';
        statusDropdown.disabled = true;
      }
    }

    function updateStatus(id, status) {
      // Create the AJAX request for updating only the status
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '../admin/academic_status.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        if (this.status === 200) {
          console.log('Status updated successfully.');
          location.reload();
        } else {
          console.error('Error updating status:', this.responseText);
        }
      };

      xhr.send(`schoolyear_id=${id}&status=${status}`);
    }




    function validateYearInput(input) {
      // Replace any non-numeric characters
      input.value = input.value.replace(/[^0-9]/g, "");

      // If the length is greater than 4, insert the dash
      if (input.value.length > 4) {
        input.value = input.value.slice(0, 4) + " - " + input.value.slice(4, 8);
      }
    }
  </script>


</body>

</html>