<?php
include('../database/models/dbconnect.php');

// Debugging: Check if 'deleteId' is set
if (isset($_GET['deleteId'])) {
  $student_id = $_GET['deleteId'];

  // Debugging: Output the received student_id
  echo "Student ID: " . $student_id;

  // Ensure $student_id is an integer to avoid any unexpected issues
  $student_id = intval($student_id);

  // Step 1: Delete related records in tblstudent_section
  $sql_delete_section = "DELETE FROM `tblstudent_section` WHERE student_id = ?";
  if ($stmt = $conn->prepare($sql_delete_section)) {
    $stmt->bind_param("i", $student_id); // 'i' for integer
    if ($stmt->execute()) {
      // Success: Proceed to delete the student record
      echo "Related records in tblstudent_section deleted successfully.<br>";

      // Step 2: Delete the student record from tblstudent
      $sql_delete_student = "DELETE FROM `tblstudent` WHERE student_id = ?";
      if ($stmt_student = $conn->prepare($sql_delete_student)) {
        $stmt_student->bind_param("i", $student_id);
        if ($stmt_student->execute()) {
          // Success: Redirect to the student list page after successful deletion
          echo "Student deleted successfully.";
          header("Location: manage_student.php");  // Redirect to another page, e.g., student list
          exit();
        } else {
          echo "Error deleting student record: " . $stmt_student->error;
        }
        $stmt_student->close();
      } else {
        echo "Error preparing delete statement for student: " . $conn->error;
      }
    } else {
      echo "Error deleting related records in tblstudent_section: " . $stmt->error;
    }
    $stmt->close();
  } else {
    echo "Error preparing delete statement for tblstudent_section: " . $conn->error;
  }
} else {
  echo "No student ID specified.";
}
