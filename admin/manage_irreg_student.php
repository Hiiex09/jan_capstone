<?php
include('../database/models/dbconnect.php');
session_start();

$selected_student = $selected_teacher = $selected_subject = ""; // Initialize selected values

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form inputs safely
  $student_id = $_POST['student_id'] ?? null;
  $teacher_id = $_POST['teacher_id'] ?? null;
  $subject_id = $_POST['subject_id'] ?? null;

  // Store selected values for form retention
  $selected_student = $student_id;
  $selected_teacher = $teacher_id;
  $selected_subject = $subject_id;

  // Get teacher's department
  $stmt = $conn->prepare("SELECT department_id FROM tblteacher WHERE teacher_id = ?");
  $stmt->bind_param("i", $teacher_id);
  $stmt->execute();
  $teacherResult = $stmt->get_result();
  $teacher = $teacherResult->fetch_assoc();
  $teacher_dept = $teacher['department_id'];
  $stmt->close();

  // Get student's department
  $stmt = $conn->prepare("SELECT department_id FROM tblstudent WHERE student_id = ?");
  $stmt->bind_param("i", $student_id);
  $stmt->execute();
  $studentResult = $stmt->get_result();
  $student = $studentResult->fetch_assoc();
  $student_dept = $student['department_id'];
  $stmt->close();

  // Get subject's department
  $stmt = $conn->prepare("SELECT department_id FROM tblsubject WHERE subject_id = ?");
  $stmt->bind_param("i", $subject_id);
  $stmt->execute();
  $subjectResult = $stmt->get_result();
  $subject = $subjectResult->fetch_assoc();
  $subject_dept = $subject['department_id'];
  $stmt->close();

  // IT teacher can only assign IT subjects to IT students
  if ($teacher_dept == $selected_teacher && ($student_dept == $selected_student && $subject_dept == $selected_subject)) {
    echo "<script>alert('IT teachers can only assign IT subjects to IT students.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Education teacher can assign any subject to any student (No restriction)
  // Other departments must match subject and student department
  if ($teacher_dept != $selected_teacher && $teacher_dept != $subject_dept) {
    echo "<script>alert('This teacher cannot assign this subject. Department mismatch.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Check if student is regular
  $stmt = $conn->prepare("SELECT is_regular FROM tblstudent_section WHERE student_id = ?");
  $stmt->bind_param("i", $student_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();

  if (!$row) {
    echo "<script>alert('No data found for this student.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  if ($row['is_regular'] == 1) {
    echo "<script>alert('This student is regular. Assignments must be handled through sections.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Check how many teachers are already assigned to the student
  $stmt = $conn->prepare("SELECT COUNT(*) AS teacher_count FROM tblstudent_teacher_subject WHERE student_id = ?");
  $stmt->bind_param("i", $student_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();

  if ($row['teacher_count'] >= 8) {
    echo "<script>alert('This student has already been assigned the maximum of 8 teachers.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Check if student already has the subject assigned to another teacher
  $stmt = $conn->prepare("SELECT COUNT(*) AS teacher_subject_count FROM tblstudent_teacher_subject WHERE student_id = ? AND subject_id = ? AND teacher_id != ?");
  $stmt->bind_param("iii", $student_id, $subject_id, $teacher_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();

  if ($row['teacher_subject_count'] > 0) {
    echo "<script>alert('This student already has this subject assigned to another teacher.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Check if this exact assignment already exists
  $stmt = $conn->prepare("SELECT COUNT(*) AS assignment_count FROM tblstudent_teacher_subject WHERE student_id = ? AND teacher_id = ? AND subject_id = ?");
  $stmt->bind_param("iii", $student_id, $teacher_id, $subject_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();

  if ($row['assignment_count'] > 0) {
    echo "<script>alert('This student is already assigned to this teacher for this subject.'); window.location.href='manage_irreg_student.php';</script>";
    exit;
  }

  // Assign the teacher and subject to the student
  $stmt = $conn->prepare("INSERT INTO tblstudent_teacher_subject (student_id, teacher_id, subject_id) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $student_id, $teacher_id, $subject_id);

  if ($stmt->execute()) {
    echo "<script>alert('Teacher and subject successfully assigned to student!'); window.location.href='manage_irreg_student.php';</script>";
  } else {
    echo "<script>alert('Error in assignment: " . $stmt->error . "'); window.location.href='manage_irreg_student.php';</script>";
  }

  $stmt->close();
}

// DELETE
if (isset($_GET['delete'])) {
  $assignment_id = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM tblstudent_teacher_subject WHERE sts_id = ?");
  $stmt->bind_param("i", $assignment_id);

  if ($stmt->execute()) {
    echo "<script>alert('Assignment successfully deleted.'); window.location.href='manage_irreg_student.php';</script>";
  } else {
    echo "<script>alert('Error deleting assignment: " . $stmt->error . "'); window.location.href='manage_irreg_student.php';</script>";
  }

  $stmt->close();
}

// EDIT
if (isset($_GET['edit'])) {
  $assignment_id = $_GET['edit'];
  $stmt = $conn->prepare("SELECT student_id, teacher_id, subject_id FROM tblstudent_teacher_subject WHERE sts_id = ?");
  $stmt->bind_param("i", $assignment_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();

  if ($row) {
    $selected_student = $row['student_id'];
    $selected_teacher = $row['teacher_id'];
    $selected_subject = $row['subject_id'];
  }
}
?>


<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assign Teacher and Subject to Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <div class="p-5 m-5 bg-base-300 rounded-md">
    <div class="p-5">
      <h2 class="text-3xl m-1 ">Assign Teacher and Subject to Student For Irregular Student</h2>
      <div class="p-3">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <div class="flex justify-start items-center gap-3">
            <!-- Student Selection -->
            <div>
              <label for="student" class="text-lg">Select Student</label>
              <select
                name="student_id"
                id="student"
                required class="select select-bordered w-full max-w-xs">
                <?php
                // Fetch only irregular students from the database
                $query = $conn->query("SELECT s.student_id, s.name FROM tblstudent s JOIN tblstudent_section ss ON s.student_id = ss.student_id WHERE ss.is_regular = 0");
                while ($row = $query->fetch_assoc()) {
                  // Retain the previously selected student
                  $selected = ($row['student_id'] == $selected_student) ? "selected" : "";
                  echo "<option value='" . htmlspecialchars($row['student_id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
              </select>
            </div>
            <!-- Teacher Selection -->
            <div>
              <label for="teacher" class="text-lg">Select Teacher</label>
              <select name="teacher_id"
                id="teacher"
                required class="select select-bordered w-full max-w-xs">
                <?php
                // Fetch teachers from the database
                $query = $conn->query("SELECT teacher_id, name FROM tblteacher");
                while ($row = $query->fetch_assoc()) {
                  // Retain the previously selected teacher
                  $selected = ($row['teacher_id'] == $selected_teacher) ? "selected" : "";
                  echo "<option value='" . htmlspecialchars($row['teacher_id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
              </select>
            </div>
            <!-- Subject Selection -->
            <div>
              <label for="subject" class="text-lg">Select Subject:</label>
              <select name="subject_id"
                id="subject"
                required class="select select-bordered w-full max-w-xs">
                <?php
                // Fetch subjects from the database
                $query = $conn->query("SELECT subject_id, subject_name FROM tblsubject");
                while ($row = $query->fetch_assoc()) {
                  // Retain the previously selected subject
                  $selected = ($row['subject_id'] == $selected_subject) ? "selected" : "";
                  echo "<option value='" . htmlspecialchars($row['subject_id']) . "' $selected>" . htmlspecialchars($row['subject_name']) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="mt-7">
              <input type="submit" value="Assigned Teacher" class="btn btn-md btn-outline rounded-md w-full max-w-sm" />
            </div>
          </div>

        </form>
      </div>
    </div>


    <!-- DISPLAY -->
    <div class="p-8">
      <h3 class="text-3xl">Assigned Teachers and Subjects to Students</h3>
      <table class="table-auto w-full mt-6 border-collapse border border-gray-300">
        <thead>
          <tr>
            <th class="border p-2">Student Name</th>
            <th class="border p-2">Teacher Name</th>
            <th class="border p-2">Subject</th>
            <th class="border p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = $conn->query("SELECT ts.sts_id, s.name AS student_name, t.name AS teacher_name, sub.subject_name
                       FROM tblstudent_teacher_subject ts
                       JOIN tblstudent s ON ts.student_id = s.student_id
                       JOIN tblteacher t ON ts.teacher_id = t.teacher_id
                       JOIN tblsubject sub ON ts.subject_id = sub.subject_id");

          ?>
          <?php while ($row = $query->fetch_assoc()) { ?>
            <tr>
              <td class="border p-2 text-center"><?= htmlspecialchars($row['student_name']) ?></td>
              <td class="border p-2 text-center"><?= htmlspecialchars($row['teacher_name']) ?></td>
              <td class="border p-2 text-center"><?= htmlspecialchars($row['subject_name']) ?></td>
              <td class="border p-2 text-center">
                <a href="?edit= <? $row[' sts_id'] ?> " class="btn btn-sm btn-outline btn-success">Edit</a>
                <a href=" ?delete=<?= $row['sts_id'] ?>" class="btn btn-sm btn-outline btn-error">Remove</a>
              </td>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    </div>
  </div>


</body>

</html>

<?php
$conn->close();
?>