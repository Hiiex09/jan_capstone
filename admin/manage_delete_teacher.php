<?php
include('../database/models/dbconnect.php');

// Debugging: Check if 'deleteId' is set
if (isset($_GET['deleteId'])) {
  $teacher_id = $_GET['deleteId'];

  // Debugging: Output the received teacher_id
  echo "Teacher ID: " . $teacher_id;

  // Ensure $teacher_id is an integer to avoid any unexpected issues
  $teacher_id = intval($teacher_id);

  // Step 1: Delete related records in tblsection_teacher_subject
  $sql_delete_section_teacher_subject = "DELETE FROM `tblsection_teacher_subject` WHERE teacher_id = ?";
  if ($stmt_section_teacher_subject = $conn->prepare($sql_delete_section_teacher_subject)) {
    $stmt_section_teacher_subject->bind_param("i", $teacher_id); // 'i' for integer
    if ($stmt_section_teacher_subject->execute()) {
      // Success: Proceed to delete related records in tblanswer
      echo "Related records in tblsection_teacher_subject deleted successfully.<br>";

      // Step 2: Delete related records in tblanswer
      $sql_delete_answer = "DELETE FROM `tblanswer` WHERE evaluate_id IN (SELECT evaluate_id FROM tblevaluate WHERE teacher_id = ?)";
      if ($stmt_answer = $conn->prepare($sql_delete_answer)) {
        $stmt_answer->bind_param("i", $teacher_id); // 'i' for integer
        if ($stmt_answer->execute()) {
          // Success: Proceed to delete related evaluations in tblevaluate
          echo "Related answers deleted successfully.<br>";

          // Step 3: Delete related records in tblevaluate
          $sql_delete_evaluate = "DELETE FROM `tblevaluate` WHERE teacher_id = ?";
          if ($stmt_evaluate = $conn->prepare($sql_delete_evaluate)) {
            $stmt_evaluate->bind_param("i", $teacher_id); // 'i' for integer
            if ($stmt_evaluate->execute()) {
              // Success: Proceed to delete the teacher record
              echo "Related evaluations deleted successfully.<br>";

              // Step 4: Delete the teacher record
              $sql_delete_teacher = "DELETE FROM `tblteacher` WHERE teacher_id = ?";
              if ($stmt_teacher = $conn->prepare($sql_delete_teacher)) {
                $stmt_teacher->bind_param("i", $teacher_id);
                if ($stmt_teacher->execute()) {
                  // Success: Redirect to the teacher list page after successful deletion
                  echo "Teacher deleted successfully.";
                  header("Location: manage_teacher.php");  // Redirect to another page, e.g., teacher list
                  exit();
                } else {
                  echo "Error deleting teacher record: " . $stmt_teacher->error;
                }
                $stmt_teacher->close();
              } else {
                echo "Error preparing delete statement for teacher: " . $conn->error;
              }
            } else {
              echo "Error deleting related evaluations: " . $stmt_evaluate->error;
            }
            $stmt_evaluate->close();
          } else {
            echo "Error preparing delete statement for evaluations: " . $conn->error;
          }
        } else {
          echo "Error deleting related answers: " . $stmt_answer->error;
        }
        $stmt_answer->close();
      } else {
        echo "Error preparing delete statement for answers: " . $conn->error;
      }
    } else {
      echo "Error deleting related records in tblsection_teacher_subject: " . $stmt_section_teacher_subject->error;
    }
    $stmt_section_teacher_subject->close();
  } else {
    echo "Error preparing delete statement for tblsection_teacher_subject: " . $conn->error;
  }
} else {
  echo "No teacher ID specified.";
}
