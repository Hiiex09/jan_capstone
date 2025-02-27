<?php
include('../database/models/dbconnect.php');
session_start();

// Handle adding, editing, or deleting sections based on POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  $section_name = $_POST['section_name'] ?? '';
  $year_level = $_POST['year_level'] ?? '';
  $section_id = $_POST['section_id'] ?? '';
  $dept = $_POST['department_id'] ?? '';

  if ($action == 'add') {
    $stmt = $conn->prepare("INSERT INTO tblsection (section_name, year_level, department_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $section_name, $year_level, $dept);
  } elseif ($action == 'edit') {
    $stmt = $conn->prepare("UPDATE tblsection SET section_name = ?, year_level = ?, department_id = ? WHERE section_id = ?");
    $stmt->bind_param("siii", $section_name, $year_level, $dept, $section_id);
  } elseif ($action == 'delete') {
    $stmt = $conn->prepare("UPDATE tblsection SET deleted_at = NOW() WHERE section_id = ?");
    $stmt->bind_param("i", $section_id);
  }

  if ($stmt->execute()) {
    $stmt->close();
    header("Location: manage_section.php"); // Redirect to avoid resubmission
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }
}

// Fetch all sections (excluding soft-deleted ones)
$sections = $conn->query("SELECT s.*, d.department_name 
                          FROM tblsection s
                          LEFT JOIN tbldepartment d ON s.department_id = d.department_id
                          WHERE s.deleted_at IS NULL");

// Set default form action
$form_action = 'add';
$section_id = $section_name = $year_level = $dept = '';

// If editing, prefill form fields
if (isset($_GET['edit'])) {
  $sectionId = $_GET['edit'];
  $editQuery = $conn->prepare("SELECT * FROM tblsection WHERE section_id = ?");
  $editQuery->bind_param("i", $sectionId);
  $editQuery->execute();
  $editResult = $editQuery->get_result();
  if ($row = $editResult->fetch_assoc()) {
    $form_action = 'edit';
    $section_id = $row['section_id'];
    $section_name = $row['section_name'];
    $year_level = $row['year_level'];
    $dept = $row['department_id'];
  }
  $editQuery->close();
}
?>
<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Section</title>
</head>

<body>

  <div class="p-5 bg-base-300 m-5 rounded-md">
    <div class="m-4">
      <h1 class="text-3xl">Manage Sections</h1>
    </div>
    <div class="m-4">
      <form method="post" action="">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
        <div class="mt-1">
          <div class="mt-1 mb-1">
            <label class="text-2xl">Section Name</label>
          </div>
          <div>
            <input type="text"
              name="section_name"
              value="<?php echo $section_name; ?>"
              required
              autocomplete="off" class="input input-bordered w-full max-w-xs" />
          </div>
        </div>
        <div class="mt-1">
          <div class="mt-1 mb-1">
            <label class="text-2xl">Year Level</label>
          </div>
          <div>
            <input type="number"
              name="year_level"
              value="<?php echo $year_level; ?>"
              required
              autocomplete="off" class="input input-bordered w-full max-w-xs" />
          </div>
        </div>
        <div class="mt-1">
          <label class="text-2xl">Department</label>
          <select class="select select-bordered w-full max-w-xs" name="department_id" required>
            <option value="" disabled>Select Department</option>
            <?php
            $department = $conn->query("SELECT * FROM tbldepartment");
            while ($row = $department->fetch_assoc()) :
              $selected = ($dept == $row['department_id']) ? "selected" : "";
            ?>
              <option value="<?php echo $row['department_id']; ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($row['department_name']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div>
          <input
            type="submit"
            value="<?php echo $form_action == 'add' ? 'Add Section' : 'Update Section'; ?>"
            class="btn btn-md btn-outline w-full max-w-xs mt-2">
        </div>
      </form>
    </div>
    <!-- Single form for adding, editing, and deleting -->
    <div class="p-5">
      <h3 class="text-2xl m-1">Existing Sections</h3>
      <table class="table-auto w-full border shadow">
      </table>
      <div>
        <table class="table overflow-y-auto">
          <!-- head -->
          <thead>
            <tr class="text-center hover cursor-pointer">
              <th>Section ID</th>
              <th>Section Name</th>
              <th>Year Level</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $sections->fetch_assoc()) { ?>
              <tr class="text-center hover cursor-pointer">
                <td><?php echo $row['section_id']; ?></td>
                <td><?php echo $row['section_name']; ?></td>
                <td><?php echo $row['year_level']; ?></td>
                <td>
                  <a href="?action=edit&section_id=<?php echo $row['section_id']; ?>&section_name=<?php echo $row['section_name']; ?>&year_level=<?php echo $row['year_level']; ?>"
                    class="text-sm btn btn-xs btn-success">
                    Update
                  </a>
                  <a href="#" onclick="deleteSection(<?php echo $row['section_id']; ?>)" class="text-sm text-sm btn btn-xs btn-error">
                    Remove
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <script>
    function deleteSection(sectionId) {
      // Confirm deletion
      if (confirm("Are you sure you want to delete this section?")) {
        // Set form action to delete and submit the form
        const form = document.querySelector("form");
        form.action.value = "delete";
        form.section_id.value = sectionId;
        form.submit();
      }
    }
  </script>
</body>

</html>