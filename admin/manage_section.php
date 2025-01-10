<?php
include('../database/models/dbconnect.php');
// Handle adding, editing, or deleting sections based on POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  $section_name = $_POST['section_name'] ?? '';
  $year_level = $_POST['year_level'] ?? '';
  $section_id = $_POST['section_id'] ?? '';

  if ($action == 'add') {
    $stmt = $conn->prepare("INSERT INTO tblsection (section_name, year_level) VALUES (?, ?)");
    $stmt->bind_param("si", $section_name, $year_level);
  } elseif ($action == 'edit') {
    $stmt = $conn->prepare("UPDATE tblsection SET section_name = ?, year_level = ? WHERE section_id = ?");
    $stmt->bind_param("sii", $section_name, $year_level, $section_id);
  } elseif ($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM tblsection WHERE section_id = ?");
    $stmt->bind_param("i", $section_id);
    // echo "<script> window.location.href='../admin/section.php'; </script>";
  }

  $stmt->execute();
  $stmt->close();
  header("Location:../admin/section.php"); // Redirect to avoid resubmission
  exit();
}

// Fetch all sections
$sections = $conn->query("SELECT * FROM tblsection");

// Set default form action to add
$form_action = 'add';
$section_id = $section_name = $year_level = '';

// If editing, prefill form fields
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
  $form_action = 'edit';
  $section_id = $_GET['section_id'];
  $section_name = $_GET['section_name'];
  $year_level = $_GET['year_level'];
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
  <div class="p-5">

    <div class="m-4">
      <h1 class="text-4xl">Manage Sections</h1>
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
            <input
              type="text"
              name="section_name"
              value="<?php echo $section_name; ?>"
              required
              autocomplete="off"
              class="px-4 py-2 rounded-md border-2 w-[500px] text-black">
          </div>
        </div>
        <div class="mt-1">
          <div class="mt-1 mb-1">
            <label class="text-2xl">Year Level</label>
          </div>
          <div>
            <input
              type="number"
              name="year_level"
              value="<?php echo $year_level; ?>"
              required
              autocomplete="off"
              class="px-4 py-2 rounded-md border-2 w-[500px] text-black">
          </div>
        </div>
        <div class="relative">
          <input
            type="submit"
            value="<?php echo $form_action == 'add' ? 'Add Section' : 'Update Section'; ?>"
            class="bg-blue-900 hover:bg-blue-500 px-4 py-3 rounded-md w-[500px] mt-4 text-white
           cursor-pointer hover:border-s-8 border-yellow-300">
          <img
            src="../admin/tools/Images/send.svg"
            alt="send"
            class="h-8 w-8 absolute top-6 left-40">
        </div>
      </form>
    </div>
    <!-- Single form for adding, editing, and deleting -->

    <div class="m-4">
      <div class="mt-4 mb-2">
        <h3 class="text-2xl">Existing Sections</h3>
      </div>
      <table class="table-auto w-full border shadow">
        <thead class="border bg-blue-900 text-white text-sm">
          <tr>
            <th class="px-4 py-2 text-start">Section ID</th>
            <th class="px-4 py-2 text-start">Section Name</th>
            <th class="px-4 py-2 text-start">Year Level</th>
            <th class="px-4 py-2 text-start">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $sections->fetch_assoc()) { ?>
            <tr class="border-b hover:bg-pink-50">
              <td class="px-4 py-2 text-start border"><?php echo $row['section_id']; ?></td>
              <td class="px-4 py-2 text-start border"><?php echo $row['section_name']; ?></td>
              <td class="px-4 py-2 text-start border"><?php echo $row['year_level']; ?></td>
              <td class="px-4 py-2 text-start border">
                <div class="flex justify-center items-center gap-2">
                  <div class="bg-blue-900 hover:bg-blue-500 px-2 py-1 rounded-md">
                    <!-- Update link -->
                    <a href="?action=edit&section_id=<?php echo $row['section_id']; ?>&section_name=<?php echo $row['section_name']; ?>&year_level=<?php echo $row['year_level']; ?>">
                      <img src="../admin/tools/Images/update.svg" alt="School ID"
                        class="w-6 h-6">
                    </a>
                  </div>
                  <div class="bg-red-900 hover:bg-red-500 px-2 py-1 rounded-md">
                    <!-- Delete link triggers the form submission with delete action -->
                    <a href="#" onclick="deleteSection(<?php echo $row['section_id']; ?>)">
                      <img src="../admin/tools/Images/delete.svg" alt="School ID"
                        class="w-6 h-6">
                    </a>

                  </div>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>


      </table>

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