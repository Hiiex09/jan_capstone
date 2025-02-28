<?php
ob_start();
include("../database/models/dbconnect.php");
session_start();

include('../admin/header.php');

// Restore a deleted department
if (isset($_GET['restore'])) {
  $deptId = $_GET['restore'];
  $sql = "UPDATE tbldepartment SET deleted_at = NULL WHERE department_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['delete'])) {
  $deptId = $_GET['delete'];
  $sql = "DELETE FROM tbldepartment WHERE department_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted departments
$sql = "SELECT * FROM tbldepartment WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedDepartments = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedDepartments[] = $row;
  }
}


// Restore a deleted criteria
if (isset($_GET['criter'])) {
  $deptId = $_GET['criter'];
  $sql = "UPDATE tblcriteria SET deleted_at = NULL WHERE criteria_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletes'])) {
  $deptId = $_GET['deletes'];
  $sql = "DELETE FROM tblcriteria WHERE criteria_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted criterias
$sql = "SELECT * FROM tblcriteria WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedCriteria = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedCriteria[] = $row;
  }
}


// Restore a deleted subject
if (isset($_GET['sub'])) {
  $deptId = $_GET['sub'];
  $sql = "UPDATE tblsubject SET deleted_at = NULL WHERE subject_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletesub'])) {
  $deptId = $_GET['deletesub'];
  $sql = "DELETE FROM tblsubject WHERE subject_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted subjects
$sql = "SELECT * FROM tblsubject WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedSubject = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedSubject[] = $row;
  }
}


// Restore a deleted section
if (isset($_GET['sec'])) {
  $deptId = $_GET['sec'];
  $sql = "UPDATE tblsection SET deleted_at = NULL WHERE section_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletesec'])) {
  $deptId = $_GET['deletesec'];
  $sql = "DELETE FROM tblsection WHERE section_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted sections
$sql = "SELECT * FROM tblsection WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedSection = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedSection[] = $row;
  }
}



// Restore a deleted schoolyear
if (isset($_GET['sc'])) {
  $deptId = $_GET['sc'];
  $sql = "UPDATE tblschoolyear SET deleted_at = NULL WHERE schoolyear_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletesc'])) {
  $deptId = $_GET['deletesc'];
  $sql = "DELETE FROM tblschoolyear WHERE schoolyear_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted schoolyears
$sql = "SELECT * FROM tblschoolyear WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedAcademic = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedAcademic[] = $row;
  }
}


// Restore a deleted admin
if (isset($_GET['ad'])) {
  $deptId = $_GET['ad'];
  $sql = "UPDATE admin SET deleted_at = NULL WHERE id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletead'])) {
  $deptId = $_GET['deletead'];
  $sql = "DELETE FROM admin WHERE id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted admins
$sql = "SELECT * FROM admin WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedAdmin = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedAdmin[] = $row;
  }
}



// Restore a deleted tblstudent
if (isset($_GET['stud'])) {
  $deptId = $_GET['stud'];
  $sql = "UPDATE tblstudent SET deleted_at = NULL WHERE student_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deletestud'])) {
  $deptId = $_GET['deletestud'];

  // First, delete related records from tblstudent_section
  $sql1 = "DELETE FROM tblstudent_section WHERE student_id = ?";
  if ($stmt1 = $conn->prepare($sql1)) {
    $stmt1->bind_param("i", $deptId);
    $stmt1->execute();
    $stmt1->close();
  }

  // Now delete from tblstudent
  $sql2 = "DELETE FROM tblstudent WHERE student_id = ?";
  if ($stmt2 = $conn->prepare($sql2)) {
    $stmt2->bind_param("i", $deptId);
    $stmt2->execute();
    $stmt2->close();
  }

  header('Location: archive.php');
  exit();
}


// Fetch only deleted tblstudents
$sql = "SELECT * FROM tblstudent WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedStudent = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedStudent[] = $row;
  }
}


// Restore a deleted tblteacher
if (isset($_GET['teach'])) {
  $deptId = $_GET['teach'];
  $sql = "UPDATE tblteacher SET deleted_at = NULL WHERE teacher_id = ?";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: ../admin/archive.php');
  exit();
}

// Hard delete (permanently remove from the database)
if (isset($_GET['deleteteach'])) {
  $deptId = $_GET['deleteteach'];
  $sql = "DELETE FROM tblteacher WHERE teacher_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $deptId);
    $stmt->execute();
    $stmt->close();
  }

  header('Location: archive.php');
  exit();
}

// Fetch only deleted tblteachers
$sql = "SELECT * FROM tblteacher WHERE deleted_at IS NOT NULL";
$result = $conn->query($sql);
$deletedTeacher = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $deletedTeacher[] = $row;
  }
}











ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Deleted Departments</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.24/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="p-4">
    <h1 class="text-3xl mb-4">Deleted Items</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <!-- Deleted Departments -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Departments</h2>
        <a href="manage_department.php" class="btn btn-sm btn-neutral inline-block">Back to Active Departments</a>
        <div id="deletedDepartments">
          <?php if (count($deletedDepartments) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Department Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedDepartments as $deletedDepartment): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedDepartment['department_name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?restore=" . $deletedDepartment['department_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?delete=" . $deletedDepartment['department_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this department?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted departments.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Deleted Criteria -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Criteria</h2>
        <a href="manage_criteria.php" class="btn btn-sm btn-neutral inline-block">Back to Active Criteria</a>
        <div id="deletedCriteria">
          <?php if (count($deletedCriteria) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Criteria Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedCriteria as $deletedCriterias): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedCriterias['criteria']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?criter=" . $deletedCriterias['criteria_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletes=" . $deletedCriterias['criteria_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this criteria?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted criteria.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Deleted Subjects -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Subjects</h2>
        <a href="manage_subject.php" class="btn btn-sm btn-neutral inline-block">Back to Active Subjects</a>
        <div id="deletedSubject">
          <?php if (count($deletedSubject) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Subject Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedSubject as $deletedSubjects): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedSubjects['subject_name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?sub=" . $deletedSubjects['subject_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletesub=" . $deletedSubjects['subject_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this subject?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted subjects.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Deleted Section -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Section</h2>
        <a href="manage_section.php" class="btn btn-sm btn-neutral inline-block">Back to Active Section</a>
        <div id="deletedSection">
          <?php if (count($deletedSection) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Section Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedSection as $deletedSections): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedSections['section_name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?sec=" . $deletedSections['section_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletesec=" . $deletedSections['section_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this section?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted sections.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Deleted Academic -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Academic</h2>
        <a href="manage_academic.php" class="btn btn-sm btn-neutral inline-block">Back to Active Schoolyear</a>
        <div id="deletedAcademic">
          <?php if (count($deletedAcademic) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Academic Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedAcademic as $deletedAcademics): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedAcademics['school_year']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?sc=" . $deletedAcademics['schoolyear_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletesc=" . $deletedAcademics['schoolyear_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this schoolyear?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted schoolyears.</p>
          <?php endif; ?>
        </div>
      </div>


      <!-- Deleted Admin -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Admin</h2>
        <a href="manage_admin.php" class="btn btn-sm btn-neutral inline-block">Back to Active Admin</a>
        <div id="deletedAdmin">
          <?php if (count($deletedAdmin) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm text-sm text-sm">Admin Name</th>
                    <th class="p-2 text-sm text-sm text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedAdmin as $deletedAdmins): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedAdmins['name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?ad=" . $deletedAdmins['id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletead=" . $deletedAdmins['id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this Admin?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted admin.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Deleted Student -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Student</h2>
        <a href="manage_student.php" class="btn btn-sm btn-neutral inline-block">Back to Active Student</a>
        <div id="deletedStudent">
          <?php if (count($deletedStudent) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm text-sm">Student Name</th>
                    <th class="p-2 text-sm text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedStudent as $deletedStudents): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedStudents['name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?stud=" . $deletedStudents['student_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deletestud=" . $deletedStudents['student_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this Student?');">Delete</a>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted student.</p>
          <?php endif; ?>
        </div>
      </div>


      <!-- Deleted Teacher -->
      <div class="bg-base-300 p-4 shadow-md rounded-lg hover:border-red-300 hover:border">
        <h2 class="text-2xl mb-2">Deleted Teacher</h2>
        <a href="manage_teacher.php" class="btn btn-sm btn-neutral inline-block">Back to Active Teacher</a>
        <div id="deletedTeacher">
          <?php if (count($deletedTeacher) > 0): ?>
            <div class="overflow-x-auto">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th class="p-2 text-sm">Teacher Name</th>
                    <th class="p-2 text-sm">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($deletedTeacher as $deletedTeachers): ?>
                    <tr class="bg-base-300">
                      <td class="p-2 text-center">
                        <?php echo htmlspecialchars($deletedTeachers['name']); ?>
                      </td>
                      <td class="p-2 text-center">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?teach=" . $deletedTeachers['teacher_id']; ?>" class="btn btn-sm btn-primary">Restore</a>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?deleteteach=" . $deletedTeachers['teacher_id']; ?>" class="btn btn-sm btn-error" onclick="return confirm('Are you sure you want to permanently delete this Teacher?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No deleted teacher.</p>
          <?php endif; ?>
        </div>
      </div>



    </div>
  </div>

</body>

</html>