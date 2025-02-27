<!-- Manage_reg_debug -->
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
<!-- Manage_irreg_debug -->
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
<!-- manage_teacher_ratings_debug -->
<?php
include("../database/models/dbconnect.php");
session_start();

// Check if the teacher_id is provided in the URL
if (isset($_GET['teacher_id'])) {
  $teacher_id = $_GET['teacher_id']; // Get the teacher ID from the URL parameter

  // SQL query to fetch criteria names, ratings, and comments for the specified teacher
  $query = "
        SELECT 
            c.criteria, 
            r.ratings, r.comment,
            t.name AS teacher_name, t.image AS teacher_image
        FROM tblanswer r
        JOIN tblevaluate e ON r.evaluate_id = e.evaluate_id
        JOIN tblcriteria c ON r.criteria_id = c.criteria_id
        JOIN tblteacher t ON e.teacher_id = t.teacher_id
        WHERE e.teacher_id = ? 
        ORDER BY c.criteria";

  // Prepare and execute the statement using MySQLi
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $teacher_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Fetch all results and organize by criteria
  $ratings = [];
  $comments = [];
  $teacher_name = "";
  $teacher_image = "";
  $criteria_totals = [];  // For calculating total ratings per criteria
  $criteria_counts = [];  // For counting the number of ratings per criteria
  $total_ratings = 0;      // For calculating overall average
  $total_count = 0;        // For counting all ratings for overall average

  while ($row = mysqli_fetch_assoc($result)) {
    if (empty($teacher_name)) {
      // Store teacher details (name and image) only once
      $teacher_name = $row['teacher_name'];
      $teacher_image = $row['teacher_image'];
    }

    $criteria = $row['criteria'];

    // Add ratings and comments
    $ratings[$criteria][] = [
      'rating' => $row['ratings'],
      'comment' => $row['comment']
    ];

    // Add to comment array if not empty or "0"
    if (!empty($row['comment']) && $row['comment'] !== '0') {
      $comments[] = $row['comment'];
    }

    // Calculate total ratings and counts for average calculation per criteria
    if (!isset($criteria_totals[$criteria])) {
      $criteria_totals[$criteria] = 0;
      $criteria_counts[$criteria] = 0;
    }
    $criteria_totals[$criteria] += $row['ratings'];
    $criteria_counts[$criteria]++;

    // Calculate total ratings for overall average
    $total_ratings += $row['ratings'];
    $total_count++;
  }

  // Free result and close the statement
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  // Calculate overall average rating
  if ($total_count > 0) {
    $overall_average = $total_ratings / $total_count;
  } else {
    $overall_average = 0; // If no ratings, overall average is 0
  }
} else {
  echo "No teacher ID provided in the URL.";
  exit;
}
?>

<?php

$teacher_id = $_GET['teacher_id'];

$sql = "SELECT * FROM `tblteacher` WHERE teacher_id = $teacher_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$image = $row['image'];
$fullname = $row['name'];
?>

<?php
// Pagination Variables
$per_page = 5; // Display 5 rows per page
$total_items = count($ratings);
$total_pages = ceil($total_items / $per_page);
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); // Ensure valid page number

// Calculate the Offset
$offset = ($current_page - 1) * $per_page;

// Slice the Ratings Array for Display
$display_ratings = array_slice($ratings, $offset, $per_page, true);
?>
<!-- manage_evaluation_debug -->
<?php
session_start();
include('../database/models/dbconnect.php'); // Include database connection
include("../admin/criteria.php");

// Check if the student is logged in
if (!isset($_SESSION['student_id']) && !isset($_SESSION['school_id']) || !isset($_SESSION['name'])) {
  echo "Please log in as a student to view your teachers.";
  exit;
}

$student_id = $_SESSION['student_id']; // Assuming student_id is passed in the form via POST
$school_id = $_SESSION['school_id']; // Get the school_id from the session
$fname = $_SESSION['name']; // Get the student name from the session

$schoolyear_query = "SELECT schoolyear_id FROM tblschoolyear WHERE is_status = 'Started' LIMIT 1";
$result = $conn->query($schoolyear_query);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $schoolyear_id = $row['schoolyear_id'];
} else {
  // Handle the case where there is no active school year
  $errorMessage = 'No Active Schoolyear Found.';
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>
  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },100);
  </script>
 </body>
  </html>
  ";

  exit;
}

// Check if the student is regular or irregular
$checkStudentStatus = $conn->prepare("SELECT is_regular FROM tblstudent_section WHERE student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)");
$checkStudentStatus->bind_param("i", $school_id);
$checkStudentStatus->execute();
$statusResult = $checkStudentStatus->get_result();
$is_regular = false;

if ($statusResult->num_rows > 0) {
  $status = $statusResult->fetch_assoc();
  $is_regular = $status['is_regular']; // Determine if student is regular
}

$teachers = [];

// Fetch teachers for regular students (based on sections) or irregular students (manual assignment)
if ($is_regular) {
  $sql = "
        SELECT tblteacher.name AS teacher_name, tblsubject.subject_name, tblteacher.image, tblteacher.teacher_id
        FROM tblstudent_section
        INNER JOIN tblsection_teacher_subject ON tblstudent_section.section_id = tblsection_teacher_subject.section_id
        INNER JOIN tblteacher ON tblsection_teacher_subject.teacher_id = tblteacher.teacher_id
        INNER JOIN tblsubject ON tblsection_teacher_subject.subject_id = tblsubject.subject_id
        WHERE tblstudent_section.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
    ";
} else {
  $sql = "
        SELECT tblteacher.name AS teacher_name, tblsubject.subject_name, tblteacher.image, tblteacher.teacher_id
        FROM tblstudent_teacher_subject
        INNER JOIN tblteacher ON tblstudent_teacher_subject.teacher_id = tblteacher.teacher_id
        INNER JOIN tblsubject ON tblstudent_teacher_subject.subject_id = tblsubject.subject_id
        WHERE tblstudent_teacher_subject.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
    ";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();



// Fetch teachers and subjects
if ($result->num_rows === 0) {
  $errorMessage = 'You do not have any teachers assigned. Please contact your administrator.';
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>

  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },1000);
  </script>

  </body>
  </html>
  ";
  exit;
}

// Fetch teachers and subjects into an array
$teachers = [];
while ($row = $result->fetch_assoc()) {
  $teachers[] = $row;
}



$stmt->close();

$criteriaList = displayCriteria();

$currentTeacherIndex = isset($_SESSION['current_teacher_index']) ? $_SESSION['current_teacher_index'] : 0;

// Check if all teachers have been evaluated
if ($currentTeacherIndex >= count($teachers)) {

  $errorMessage = 'You do not have any teachers assigned. Please contact your administrator.';
  // Reset index if all teachers have been evaluated
  unset($_SESSION['current_teacher_index']);
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>

  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },100);
  </script>

  </body>
  </html>
  ";
  exit;
}

// Get current teacher
$currentTeacher = $teachers[$currentTeacherIndex];

function storeEvaluation($teacher_id, $ratings, $comment, $schoolyear_id)
{
  global $conn;  // Ensure the connection variable is available

  // Start transaction for better error handling
  $conn->begin_transaction();

  try {
    // Retrieve the student_id from the session
    if (!isset($_SESSION['student_id'])) {
      throw new Exception("Student ID is missing from the session.");
    }
    $student_id = $_SESSION['student_id'];

    $check_evaluation_query = "SELECT * FROM tblevaluate WHERE teacher_id = ? AND student_id = ? AND schoolyear_id = ?";
    $stmt = $conn->prepare($check_evaluation_query);
    $stmt->bind_param("iis", $teacher_id, $student_id, $schoolyear_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $errorMessage = 'You have already evaluated this teacher for this school years.';



    // If evaluation already exists, stop further processing
    if ($result->num_rows > 0) {
      $all_evaluated = allTeachersEvaluated($_SESSION['student_id'], $schoolyear_id);

      if ($all_evaluated) {

        $student_id = $_SESSION['school_id'];

        throw new Exception($all_evaluated);
      } else {
        throw new Exception("<div id='errorModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
            <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 absolute top-72 left-1/3'>
                <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
                <div class='flex justify-between'>
                    <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
                    <a href='studentDashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
                </div>
            </div>
        </div>
        <script>document.getElementById('errorModal').style.display = 'block';</script>
    ");
      }
    }

    // Insert evaluation data into tblevaluate
    $evaluate_query = "INSERT INTO tblevaluate (teacher_id, student_id, schoolyear_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($evaluate_query);
    $stmt->bind_param("iis", $teacher_id, $student_id, $schoolyear_id);
    $stmt->execute();

    // Get the last inserted evaluation_id
    $evaluation_id = $stmt->insert_id;

    // Insert ratings and comments
    if ($ratings) {
      foreach ($ratings as $criteria_id => $rating_value) {
        // Check if a comment exists, otherwise set it to '0'
        $comment_value = isset($comment[$criteria_id]) && trim($comment[$criteria_id]) !== '' ? trim($comment[$criteria_id]) : '0';

        // Insert the values into the database
        $rating_query = "INSERT INTO tblanswer (evaluate_id, criteria_id, ratings, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($rating_query);
        $stmt->bind_param("iiis", $evaluation_id, $criteria_id, $rating_value, $comment_value);
        $stmt->execute();
      }
    }

    // Commit the transaction
    $conn->commit();

    $_SESSION['current_teacher_index'] = $_SESSION['current_teacher_index'] + 1;

    // Redirect or continue to the next task
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } catch (Exception $e) {
    // Rollback the transaction if there's an error
    $conn->rollback();
    $errorMessage = 'You have already evaluated this teacher for this school year.';

    echo "
        <div id='errorModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
        <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 absolute  top-72 left-1/3'>
        <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
        <div class='flex justify-between'>
        <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
        <a href='studentDashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
        </div>
        </div>
        </div>
        <script>document.getElementById('errorModal').style.display = 'block';</script>
        " . $e->getMessage();
  }
}

$offensiveWords = ['badword1', 'badword2', 'badword3']; // Replace with actual offensive words
$maxLetters = 50;
$filteredComment = "";

// Sanitize input by removing special characters except letters, numbers, and whitespace
function sanitizeInput($input)
{
  return preg_replace('/[^a-zA-Z0-9\s.,]/', '', $input);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher_id = $_POST['teacher_id'];
  $ratings = $_POST['rating'];  // Array of ratings for different criteria
  $comments = $_POST['comment']; // Array of comments for different criteria
  $schoolyear_id = $_POST['schoolyear_id'];

  // Sanitize comments
  foreach ($comments as $key => $comment) {
    $filteredComment = sanitizeInput($comment);

    // Check for offensive words
    foreach ($offensiveWords as $offensiveWord) {
      if (stripos($filteredComment, $offensiveWord) !== false) {
        echo "<script>alert('Your comment contains offensive words.');</script>";
        exit;
      }
    }

    // If the comment exceeds the max character limit, trim it
    if (strlen($filteredComment) > $maxLetters) {
      $filteredComment = substr($filteredComment, 0, $maxLetters);
    }
  }

  // Store the evaluation data
  storeEvaluation($teacher_id, $ratings, $comments, $schoolyear_id);
}

function checkIfEvaluated($studentId, $teacherId)
{
  global $conn; // Ensure $conn is accessible here
  $query = "SELECT COUNT(*) FROM tblevaluate WHERE student_id = ? AND teacher_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $studentId, $teacherId);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  return $count > 0;  // Returns true if evaluated, false otherwise
}

function allTeachersEvaluated($studentId, $schoolyear_id)
{
  global $conn;

  // Correct SQL to check if ALL assigned teachers have been evaluated
  $sql = "SELECT COUNT(*) FROM tblteacher t
            WHERE (EXISTS (
                SELECT 1
                FROM tblstudent_section ss
                INNER JOIN tblsection_teacher_subject sts ON ss.section_id = sts.section_id
                WHERE ss.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
                AND sts.teacher_id = t.teacher_id
            ) OR EXISTS (
                SELECT 1
                FROM tblstudent_teacher_subject stts
                WHERE stts.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
                AND stts.teacher_id = t.teacher_id
            )) AND NOT EXISTS (
                SELECT 1
                FROM tblevaluate e
                WHERE e.teacher_id = t.teacher_id
                  AND e.student_id = ?
                  AND e.schoolyear_id = ?
            )";


  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iiii", $_SESSION['school_id'], $_SESSION['school_id'], $studentId, $schoolyear_id);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();

  return $count == 0; // All teachers are evaluated if count is 0
}
$all_evaluated = allTeachersEvaluated($_SESSION['student_id'], $schoolyear_id);

if ($all_evaluated) {

  $student_id = $_SESSION['school_id'];

  if (isset($_SESSION['name'])) {
    $student_name = $_SESSION['name']; // Retrieve student name from session
  } else {
    $student_name = 'Unknown'; // Fallback if name is not set
  }
  echo "<div id='allEvaluatedModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
          <div class='bg-green-500 p-9 rounded-lg shadow-lg w-1/3 absolute top-72 left-1/3'>
              <h2 class='text-center text-2xl text-white mb-4'>All teachers have been evaluated. Thank you!</h2>
              <p class='text-center text-white mb-4'>Student ID: $student_id</p>
                <p class='text-center text-white mb-4'>Student Name: $student_name</p>
              <div class='flex justify-center space-x-4'>
                  <button id='continueBtn' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>Continue Evaluating</button>
                  <a href='../student/student_dashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
              </div>
          </div>
      </div>
      <script>
          const modal = document.getElementById('allEvaluatedModal');
          const continueBtn = document.getElementById('continueBtn');

          modal.style.display = 'block';

          continueBtn.addEventListener('click', () => {
              modal.style.display = 'none';
          });
      </script>";
}


// Example: If all teachers have been evaluated, show a message

?>
