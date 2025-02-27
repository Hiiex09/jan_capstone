<?php
include('./database/models/dbconnect.php');

function adminLogin($username, $password)
{

  global $conn; // Access the $conn variable from the global scope
  try {
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $hashed_password = $row['password']; // Get hashed password from database

      // Verify the password
      if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['image'] = $row['image'];
        header('location: admin/admin_dashboard.php');
        exit();
      } else {
        echo "<script>alert('Invalid credentials.');</script>";
      }
    } else {
      echo "<script>alert('Invalid credentials.');</script>";
    }
  } catch (mysqli_sql_exception $e) {
    error_log("Login Failed: " . $e->getMessage());
    echo "Error during admin login.";
  }
}
