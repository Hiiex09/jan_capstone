<?php
include('../database/models/dbconnect.php');
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['school_year']) && !isset($_SESSION['semester']) && !isset($_SESSION['is_status'])) {
  // Redirect to login page or display an error message
  header("Location: ../login.php");
  exit();
}

// Query to get the active academic year and semester if set to 'Started'
$schoolyear_query = $conn->query("SELECT school_year, semester, is_status FROM tblschoolyear WHERE is_status = 'Started'");
$schoolyear = $schoolyear_query->fetch_assoc();

// Set session variables only if there is an active academic year
if ($schoolyear) {
  $_SESSION['school_year'] = $schoolyear['school_year'];
  $_SESSION['semester'] = $schoolyear['semester'];
  $_SESSION['is_status'] = $schoolyear['is_status'];
} else {
  // Set default values if no academic year is active
  $_SESSION['school_year'] = "Not Set";
  $_SESSION['semester'] = "Not Yet Started";
  $_SESSION['is_status'] = "Inactive";
}
?>
<?php include('../admin/header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

</head>

<body>
  <section class="h-full bg-base-300 rounded-md mx-5 p-5 flex grid grid-cols-4 gap-10">
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Student</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblstudent`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Teacher</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblteacher`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Admin</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `admin`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Academic</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblschoolyear`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Department</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tbldepartment`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Section</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblsection`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Criteria</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblcriteria`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Subject</h1>
        <h2 class="text-5xl text-start p-5"><?php
                                            $count = 0;
                                            $sql = "SELECT COUNT(*) AS total FROM `tblsubject`";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                              $row = mysqli_fetch_assoc($result);
                                              $count = $row['total'];
                                            }
                                            echo $count;
                                            ?></h2>
      </div>
    </div>
    <div class="h-40 bg-base-200 flex justify-center items-center rounded-md p-1 skeleton ">
      <div class="h-40 w-full p-2">
        <h1 class="text-2xl">Coming Soon</h1>
        <h2 class="text-5xl text-start p-5 animate-bounce">❤</h2>
      </div>
    </div>
  </section>


</body>

</html>