<?php
include('../database/models/dbconnect.php');
session_start();

// Assuming you are working with a form to assign a teacher and subject to a section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $section_id = $_POST['section_id'];
  $teacher_id = $_POST['teacher_id'];
  $subject_id = $_POST['subject_id'];

  // Call function to assign teacher and subject to section
  assignTeacherToSection($conn, $section_id, $teacher_id, $subject_id);
}

//KANI KAY MAKA ASSIGN KA OG SAME TEACHER PERO DILI PWEDE SUBJECT
// Function to assign a teacher and subject to a section
function assignTeacherToSection($conn, $section_id, $teacher_id, $subject_id)
{
  // Check if the subject is already assigned to any teacher in this section
  $checkSubjectAssignment = $conn->prepare("SELECT * FROM tblsection_teacher_subject WHERE section_id = ? AND subject_id = ?");
  $checkSubjectAssignment->bind_param("ii", $section_id, $subject_id);
  $checkSubjectAssignment->execute();
  $result = $checkSubjectAssignment->get_result();

  if ($result->num_rows > 0) {
    // If subject is already assigned to this section, show an alert and stop the process
    echo "<script>
            alert('Subject is already assigned to this section.');
            // window.location.href='teacher_view.php';
            </script>";
    return; // Stop further processing if the subject is already assigned to the section
  }

  // Check how many teachers are already assigned to the section
  $checkTeacherCount = $conn->prepare("SELECT COUNT(*) AS teacher_count FROM tblsection_teacher_subject WHERE section_id = ?");
  $checkTeacherCount->bind_param("i", $section_id);
  $checkTeacherCount->execute();
  $result = $checkTeacherCount->get_result();
  $row = $result->fetch_assoc();
  $teacher_count = $row['teacher_count'];

  if ($teacher_count >= 8) {
    echo "<script>
            alert('Cannot assign more than 8 teachers to this section.');
            // window.location.href='teacher_view.php';
            </script>";
    return; // Stop further processing if the section already has 8 teachers
  }

  // Check if the teacher-subject assignment already exists for this section
  $checkAssignment = $conn->prepare("SELECT * FROM tblsection_teacher_subject WHERE section_id = ? AND teacher_id = ? AND subject_id = ?");
  $checkAssignment->bind_param("iii", $section_id, $teacher_id, $subject_id);
  $checkAssignment->execute();
  $result = $checkAssignment->get_result();

  if ($result->num_rows == 0) {
    // If not assigned, insert the teacher-subject assignment to the section
    $assignQuery = $conn->prepare("INSERT INTO tblsection_teacher_subject (section_id, teacher_id, subject_id) VALUES (?, ?, ?)");
    $assignQuery->bind_param("iii", $section_id, $teacher_id, $subject_id);

    if ($assignQuery->execute()) {
      echo "<script>
            alert('Teacher and subject assigned to section successfully!');
            // window.location.href='teacher_view.php';
            </script>";
    } else {
      echo "Error: " . $assignQuery->error;
    }
  } else {
    echo "<script>
            alert('Teacher and subject are already assigned to this section.');
            // window.location.href='teacher_view.php';
            </script>";
  }
}


// Handle adding a regular student to a section (if needed)
function addRegularStudent($conn, $student_id, $section_id, $is_regular)
{
  if ($is_regular) {
    // If the student is regular, automatically assign the teacher and subject from the section
    // First, get the teacher and subject assigned to the section (make sure to fetch only one record)
    $getAssignment = $conn->prepare("SELECT teacher_id, subject_id FROM tblsection_teacher_subject WHERE section_id = ? LIMIT 1");
    $getAssignment->bind_param("i", $section_id);
    $getAssignment->execute();
    $result = $getAssignment->get_result();

    if ($result->num_rows > 0) {
      // Get the teacher and subject
      $assignment = $result->fetch_assoc();
      $teacher_id = $assignment['teacher_id'];
      $subject_id = $assignment['subject_id'];

      // Now assign the student to the section with the teacher and subject
      $assignStudent = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, teacher_id, subject_id) VALUES (?, ?, ?, ?)");
      $assignStudent->bind_param("iiii", $student_id, $section_id, $teacher_id, $subject_id);

      if ($assignStudent->execute()) {
        echo "Regular student added to section with assigned teacher and subject.";
      } else {
        echo "Error: " . $assignStudent->error;
      }
    } else {
      echo "No teacher and subject assigned to this section.";
    }
  }
}

// DELETE
if (isset($_GET['delete'])) {
  $assignment_id = $_GET['delete'];

  // Prepare and execute delete query
  $stmt = $conn->prepare("DELETE FROM tblsection_teacher_subject WHERE section_teacher_subject_id = ?");
  $stmt->bind_param("i", $assignment_id);

  if ($stmt->execute()) {
    echo "<script>alert('Assignment successfully deleted.');</script>";
  } else {
    echo "<script>alert('Error deleting assignment: " . $stmt->error . "');</script>";
  }

  $stmt->close();
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
  <title>Assign Teacher and Subject to Section</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <div class="p-5">
    <div class="p5">
      <h1 class="text-lg p-5">Assign Teacher and Subject to Section for Regular Student</h1>
      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="flex justify-start items-start p-5 gap-5">
          <!-- Section Selection -->
          <div class="mb-3">
            <label
              for="section_id"
              class="text-lg">Select Section</label>
            <select name="section_id"
              id="section_id"
              required class="select select-bordered w-full max-w-xs">
              <?php
              // Fetch sections from the database
              $query = "SELECT * FROM tblsection";
              $result = $conn->query($query);

              while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['section_id'] . "'>" . $row['section_name'] . "</option>";
              }
              ?>
            </select>
          </div>

          <!-- Teacher Selection -->
          <div class="mb-3">
            <label
              for="teacher_id"
              class="text-lg">Select Teacher</label>
            <select name="teacher_id"
              id="teacher_id"
              required class="select select-bordered w-full max-w-xs">
              <?php
              // Fetch teachers from the database
              $query = "SELECT * FROM tblteacher";
              $result = $conn->query($query);

              while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['teacher_id'] . "'>" . $row['name'] . "</option>";
              }
              ?>
            </select>
          </div>

          <!-- Subject Selection -->
          <div class="mb-3">
            <label
              for="subject_id"
              class="text-lg">Select Subject</label>
            <select name="subject_id"
              id="subject_id"
              required class="select select-bordered w-full max-w-xs">
              <?php
              // Fetch subjects from the database
              $query = "SELECT * FROM tblsubject";
              $result = $conn->query($query);

              while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mt-8">
            <input type="submit" value="Assigned Teacher" class="input input-bordered w-full max-w-xs" />
          </div>
        </div>
      </form>
    </div>

  </div>


  <!-- DISPLAY -->
  <div class="p-5">
    <h2 class="text-lg p-1">Assigned Teachers and Subjects</h2>
    <table class="min-w-full p-5">
      <thead>
        <tr>
          <th class="p-3 border">Section</th>
          <th class="p-3 border">Teacher</th>
          <th class="p-3 border">Subject</th>
          <th class="p-3 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = $conn->query("SELECT tblsection_teacher_subject.section_teacher_subject_id, 
        tblsection.section_name, 
        tblteacher.name AS teacher_name, 
        tblsubject.subject_name 
 FROM tblsection_teacher_subject
 INNER JOIN tblsection ON tblsection_teacher_subject.section_id = tblsection.section_id 
 INNER JOIN tblteacher ON tblsection_teacher_subject.teacher_id = tblteacher.teacher_id 
 INNER JOIN tblsubject ON tblsection_teacher_subject.subject_id = tblsubject.subject_id");

        ?>

        <?php while ($row = $query->fetch_assoc()) { ?>
          <tr class="text-center">
            <td class="border hover p-2"><?= htmlspecialchars($row['section_name']) ?></td>
            <td class="border hover p-2"><?= htmlspecialchars($row['teacher_name']) ?></td>
            <td class="border hover p-2"><?= htmlspecialchars($row['subject_name']) ?></td>
            <td class="border hover p-2">
              <a href="?edit= <? $row[' section_teacher_subject_id'] ?> " class="btn btn-sm btn-outline btn-success rounded-md  max-w-sm">Edit</a>
              <a href=" ?delete=<?= $row['section_teacher_subject_id'] ?>" class="btn btn-sm btn-outline btn-error rounded-md  max-w-sm">Delete</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

  </div>


</body>

</html>