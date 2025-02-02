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
  <div class="container-full flex justify-center items-center gap-2 p-20">
    <div>
      <?php if (isset($_SESSION['username'])): ?>
        <div class="p-4">
          <div class="p-4 bg-base-100 shadow text-black w-full rounded-md hidden md:block">
            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <div class="text-2xl">Academic Year:
                  <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?>
                </div>
              </div>
            </div>

            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <div class="text-3xl">Semester:
                  <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?>
                </div>
              </div>
            </div>

            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <!-- Display status -->
                <div class="text-3xl relative">Status: <?= htmlspecialchars($_SESSION['is_status']) ?></div>
              <?php else: ?>
                <div>Academic year and semester not set. Please set the active semester.</div>
              <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
        <div class="grid grid-cols-3 m-4 gap-8">
          <!-- Manage Teacher Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/teacher_side_com.svg" alt="Teacher" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Teacher</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tblteacher`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="../admin/manage_teacher.php" class="btn btn-primary btn-outline w-[150px] mt-14">View Teacher</a>
            </div>
          </div>

          <!-- Manage Student Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/student_side.svg" alt="Student" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Student</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tblstudent`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="../admin/student_table.php" class="btn btn-primary btn-outline w-[150px] mt-14">View Student</a>
            </div>
          </div>

          <!-- Manage User Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/user_side.svg" alt="User" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage User</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `admin`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="#viewDepartment" class="btn btn-primary btn-outline w-[150px] mt-14">View User</a>
            </div>
          </div>

          <!-- Manage Department Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/department_side.svg" alt="Department" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Department</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tbldepartment`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="#viewDepartment" class="btn btn-primary btn-outline w-[150px] mt-14">View Department</a>
            </div>
          </div>

          <!-- Manage Section Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/section_side.svg" alt="Section" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Section</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tblsection`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="#viewSection" class="btn btn-primary btn-outline w-[150px] mt-14">View Section</a>
            </div>
          </div>

          <!-- Manage Criteria Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/criteria_side.svg" alt="Criteria" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Criteria</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tblcriteria`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="#viewCriteria" class="btn btn-primary btn-outline w-[150px] mt-14">View Criteria</a>
            </div>
          </div>

          <!-- Manage Subject Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/subject_side.svg" alt="Subject" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Subject</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM `tblsubject`";
                  $result = mysqli_query($conn, $sql);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>
                </div>
              </div>
              <a href="#viewSubject" class="btn btn-primary btn-outline w-[150px] mt-14">View Subject</a>
            </div>
          </div>

          <!-- Manage Archive Card -->
          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img src="../admin/tools/img_side/archive_side.svg" alt="Archive" />
            </figure>
            <div class="card-body">
              <h2 class="card-title">Manage Archive</h2>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">0</div>
              </div>
              <a href="#viewArchive" class="btn btn-primary btn-outline w-[150px] mt-14">View Archive</a>
            </div>
          </div>
        </div>

    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>