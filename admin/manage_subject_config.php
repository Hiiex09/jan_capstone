<?php
include("../database/models/dbconnect.php");

function createSubject($subject, $subject_type, $department_id)
{
  global $conn; // Access the $conn variable from the global scope
  try {
    // Check if subject already exists
    $csql = "SELECT * FROM tblsubject WHERE subject_name = ?";
    $stmtc = $conn->prepare($csql);
    $stmtc->bind_param("s", $subject);
    $stmtc->execute();
    $stmtc->store_result();

    if ($stmtc->num_rows() > 0) {
      echo "<script>
                    alert('Subject already exists.');
                    window.location.href='subjectCreate.php';
                </script>";
    } else {
      // Insert the subject along with subject_type and department_id
      $sql = "INSERT INTO tblsubject (subject_name, subject_type, department_id) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssi", $subject, $subject_type, $department_id); // Bind subject, subject_type, department_id
      if ($stmt->execute()) {
        // Success message
        echo "<script>
                        alert('Subject successfully created.');
                        window.location.href='subjectCreate.php';
                    </script>";
      } else {
        // Handle failure
        echo "Error: Unable to insert subject.";
      }
      // Close the statement
      $stmt->close();
    }
    $stmtc->close();
  } catch (mysqli_sql_exception $e) {
    // Log error and display a generic message
    error_log("Insert Failed: " . $e->getMessage());
    echo "Error during subject creation.";
  }
}


function displaySubject()
{
  global $conn; // Access the $conn variable from the global scope
  try {
    // Include department_id, subject_type in the SELECT query
    $sql = "SELECT subject_id, subject_name, subject_type, department_id FROM tblsubject";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjectList = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $subjectList[] = $row; // Store subject data in the list
      }
    }
    return $subjectList;
  } catch (mysqli_sql_exception $e) {
    error_log("Error fetching subject: " . $e->getMessage());
    return [];
  }
}


function updateSubject($subjectId, $subjectName, $subjectType, $departmentId)
{
  global $conn;

  // Update subject_name, subject_type, and department_id
  $stmt = $conn->prepare("UPDATE tblsubject SET subject_name = ?, subject_type = ?, department_id = ? WHERE subject_id = ?");
  $stmt->bind_param("ssii", $subjectName, $subjectType, $departmentId, $subjectId); // "ssii" means string, string, integer, integer types

  if ($stmt->execute()) {
    return true; // Update was successful
  } else {
    return false; // Update failed
  }
}


function deleteSubject($subjectId)
{
  global $conn;

  // Delete the subject by subject_id
  $stmt = $conn->prepare("DELETE FROM tblsubject WHERE subject_id = ?");
  $stmt->bind_param("i", $subjectId); // "i" means integer type

  if ($stmt->execute()) {
    return true; // Deletion was successful
  } else {
    return false; // Deletion failed
  }
}
