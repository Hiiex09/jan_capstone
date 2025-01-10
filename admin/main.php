<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <div class="container-full flex justify-center items-center gap-2 bg-blue-100">
    <div>
      <?php if (isset($_SESSION['username'])): ?>
        <div class="p-4">
          <div class="p-4 bg-base-100 shadow text-black w-full rounded-md hidden md:block">

            <div class="flex justify-start items-center">
              <div class="mx-3">
                <h2 class="text-3xl"><span class="font-bold">Welcome</span> <span class="text-slate-900 font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span></h2>
              </div>
              <div>
                <img src="../admin/tools/Images/hello_admin.svg" alt="hello" class="w-10 h-20">
              </div>
            </div>

            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <p class="text-3xl">Academic Year:
                  <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?>
                </p>
              </div>
            </div>

            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <p class="text-3xl">Semester:
                  <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?>
                </p>
              </div>
            </div>

            <div class="flex justify-start items-center mx-4 mt-2">
              <div>
                <!-- Display status -->
                <p class="text-3xl relative">Status: <?= htmlspecialchars($_SESSION['is_status']) ?></p>
              <?php else: ?>
                <p>Academic year and semester not set. Please set the active semester.</p>
              <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
        <div class="grid  sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 m-4 gap-8">

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/teacher_side_com.svg"
                alt="teacher" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Teacher</div>
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
              <a href="../admin/teacher_table.php" class="btn btn-primary btn-outline w-[150px] mt-14">View Teacher</a>
            </div>
          </div>

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/student_side.svg"
                alt="student" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Student</div>
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/user_side.svg"
                alt="department" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage User</div>
              <div class="flex justify-center items-center gap-3 mt-7">
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/department_side.svg"
                alt="department" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Department</div>
              <div class="flex justify-center items-center gap-3 mt-1">
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/section_side.svg"
                alt="section" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Section</div>
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/criteria_side.svg"
                alt="criteria" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Criteria</div>
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/subject_side.svg"
                alt="subject" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Subject</div>
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

          <div class="card card-side bg-base-100 shadow-xl border p-4">
            <figure>
              <img
                src="../admin/tools/img_side/archive_side.svg"
                alt="subject" />
            </figure>
            <div class="card-body">
              <div class="card-title">Manage Archive</div>
              <div class="flex justify-center items-center gap-3 mt-8">
                <div class="h-8 w-8 bg-blue-900 rounded-full"></div>
                <div class="text-6xl">0</div>
              </div>
              <a href="#viewArchive" class="btn btn-primary btn-outline w-[150px] mt-14">View Archive</a>
            </div>
          </div>

        </div>
    </div>

</body>

</html>