<?php
include("../database/models/dbconnect.php");
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

// Handle delete operation
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $stmt = $conn->prepare("DELETE FROM tblschoolyear WHERE schoolyear_id = ?");
  $stmt->bind_param("i", $delete_id);
  if ($stmt->execute()) {
    echo "<script>window.location.href='manage_academic.php'; alert('School year deleted successfully!'); </script>";
  } else {
    echo "<script>alert('Error deleting school year.');</script>";
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

  <div class="m-5 p-4">
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
      <div class="mt-4 border-b-4 border-black p-2">
        <div>
          <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
          <label for="year" class="text-2xl">Academic Year :</label>
          <input
            type="text"
            id="year"
            name="year"
            required
            placeholder="Enter Academic Year (e.g., 2024 - 2025)"
            value="<?php echo $edit_year; ?>"
            class="px-2 py-1 border text-lg mx-2"><br>
        </div>
        <div class="mx-9">
          <label for="semester" class="text-2xl mx-5">Semester :</label>
          <select
            id="semester"
            name="semester"
            required
            class="px-2 py-1 border text-lg w-[220px] mx-[-12px] mt-1">
            <option value="1" <?php echo ($edit_semester == '1') ? 'selected' : ''; ?>>First Semester</option>
            <option value="2" <?php echo ($edit_semester == '2') ? 'selected' : ''; ?>>Second Semester</option>
          </select>
        </div>
        <div class="mx-44 mt-2 relative hover:border-s-4 border-yellow-400 bg-blue-900 hover:bg-blue-500 w-[219px] py-2 rounded-md text-center text-white">
          <button
            type="submit"
            name="save">
            <?php echo $edit_id ? 'Update' : 'Submit'; ?>
          </button>
          <img src="../admin/tools/Images/send.svg" alt="School ID"
            class="w-7 h-7 absolute top-2 left-12">
        </div>

      </div>





    </form>

    <div class="mt-4">
      <h2 class="text-2xl">Existing School Years</h2>
    </div>

    <table class="table-auto w-full border shadow mt-3">
      <thead class="border bg-blue-900 text-white shadow">
        <tr>
          <th class="px-4 py-2 text-center">ID</th>
          <th class="px-4 py-2 text-center">Academic Year</th>
          <th class="px-4 py-2 text-center">Semester</th>
          <th class="px-4 py-2 text-center">Default</th>
          <th class="px-4 py-2 text-center">Status</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $i = 1;
        while ($row = $school_years->fetch_assoc()):
          $active = $row['is_default'];
        ?>
          <tr class="border-b hover:bg-pink-50">
            <th class="text-center"><?php echo $i++ ?></th>
            <td class="px-4 py-2 text-center border"><b><?php echo $row['school_year']; ?></b></td>
            <td class="px-4 py-2 text-center border"><b><?php echo $row['semester']; ?></b></td>
            <td class="px-4 py-2 text-center border">
              <button class="btn btn-sm btn-outline btn-error" onclick="toggleActive(<?php echo $row['schoolyear_id']; ?>, '<?php echo $active; ?>')">
                <?php echo $active === 'Yes' ? 'Yes' : 'No'; ?>
              </button>
            </td>
            <td class="px-4 py-2 text-center border">
              <select id="status-<?php echo $row['schoolyear_id']; ?>" onchange="updateStatus(<?php echo $row['schoolyear_id']; ?>, this.value)" <?php echo $active === 'Yes' ? '' : 'disabled'; ?>>
                <option value="Not Yet Started" <?php echo $row['is_status'] === 'Not Yet Started' ? 'selected' : ''; ?>>Not Yet Started</option>
                <option value="Started" <?php echo $row['is_status'] === 'Started' ? 'selected' : ''; ?>>Started</option>
                <option value="Closed" <?php echo $row['is_status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
              </select>
            </td>
            <td class="px-4 py-2 text-center border">
              <div class="flex justify-center items-center">
                <div class="w-full mx-1 bg-blue-900 hover:bg-blue-500 rounded-md p-1 flex justify-center items-center">
                  <button class="btn1">
                    <a
                      href="?editid=<?php echo $row['schoolyear_id']; ?>">
                      <div>
                        <div>
                          <img src="../admin/tools/Images/update.svg" alt="School ID"
                            class="w-full h-6">
                        </div>
                      </div>
                    </a>
                  </button>
                </div>
                <div class="w-full mx-1 bg-red-900 hover:bg-red-500 rounded-md p-1 flex justify-center items-center">
                  <a class="btn1" href="?delete_id=<?php echo $row['schoolyear_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this school year?')">
                    <div>
                      <div>
                        <img src="../admin/tools/Images/delete.svg" alt="School ID"
                          class="w-full h-6">
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
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
  </script>


</body>

</html>