<?php
include('./database/models/dbconnect.php');

function adminLogin($username, $password)
{
  global $conn; // Access the $conn variable from the global scope
  try {
    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE (username = ? OR email = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); // Bind only the username/email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // If admin is found, fetch the data
      while ($row = $result->fetch_assoc()) {
        // Use password_verify() to check if the entered password matches the hashed password in the database
        if (password_verify($password, $row['password'])) {
          // Password is correct, store session data
          $_SESSION['image'] = $row['image'];
          $_SESSION['username'] = $row['username'];
          header('Location: ./admin/admin_dashboard.php'); // Redirect to dashboard
          exit();
        } else {
          // Password is incorrect
          echo "Invalid password.";
          header('Location: ./login.php'); // Redirect to dashboard
        }
      }
    } else {
      // User not found
      echo "No admin found with that username or email.";
    }
  } catch (mysqli_sql_exception $e) {
    // Log the error for debugging
    error_log("Login Failed: " . $e->getMessage());
    echo "Error during admin login.";
  }
}
