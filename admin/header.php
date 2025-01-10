<?php
include('../database/models/dbconnect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

  <div class="navbar bg-blue-900 shadow shadow-gray-800">
    <div class="flex-1 hover:border-s-4 border-white rounded-md">
      <a href="../admin/admin_dashboard.php" class="btn btn-ghost text-2xl text-white">Cebu Eastern College</a>
    </div>
    <div class="flex-none mt-1">
      <div class="dropdown dropdown-end">
        <!-- Dropdown Trigger -->
        <label tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-20 rounded-full">
            <img
              alt="Tailwind CSS Navbar component"
              src="../admin/tools/Profiles User/Student.png" />
          </div>
        </label>

        <!-- Dropdown Content -->
        <ul class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li>
            <a href="#updateprofile" class="btn hover:text-red-900">
              Welcome, <span class="text-black text-lg"><?= htmlspecialchars($_SESSION['username']) ?></span>
            </a>
          </li>
          <li class="mt-2">
            <!-- <a href="#updateprofile" class="hover:font-bold hover:text-red-900">
              Update Profile
              <span class="badge text-red-900">Try!</span>
            </a> -->
            <a href="../logout.php" class="hover:font-bold hover:text-blue-900 text-start">
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

  </div>
  <div class="drawer z-50">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content m-4 flex justify-start items-center gap-3">
      <div>
        <img src="../admin/tools/Images/dashboard.svg" alt="logo" class="w-10 h-10">
      </div>
      <div>
        <label for="my-drawer" class="cursor-pointer drawer-button text-4xl font-bold">Dashboard</label>
      </div>
    </div>
    <div class="drawer-side">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <ul class="menu bg-base-200 text-base-content min-h-full w-96">
        <!-- Student Table & Management -->
        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center text-xl flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/student_side.svg" alt="student" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Student
            </div>
          </div>
          <div class="collapse-content text-center">
            <a href="../admin/manage_student.php" class="text-lg hover:font-semibold hover:text-red-900">View Student</a>
          </div>
        </div>
        <!-- Teacher Table & Management -->
        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/teacher_side_com.svg" alt="teacher" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Teacher
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/manage_teacher.php" class="text-lg hover:font-semibold hover:text-red-900">View Teacher</a>
            <a href="../admin/manage_reg_student.php" class="hover:font-semibold text-lg flex justify-start items-center gap-4">
              <img src="../admin/tools/img_side/assign_side.svg" alt="assing_side" class="w-7 h-7">
              <p class="hover:text-red-900">Assign Regular Student</p>
            </a>
            <a href="../admin/manage_irreg_student.php" class="hover:font-semibold text-lg flex justify-start items-center gap-4">
              <img src="../admin/tools/img_side/assign_side.svg" alt="assing_side" class="w-7 h-7">
              <p class="hover:text-red-900">Assign Irregular Student</p>
            </a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/teacher_side_com.svg" alt="teacher" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Admin
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="#viewStudent" class="text-lg hover:font-semibold hover:text-red-900">View Admin</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/academic_side.svg" alt="academic" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Academic
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/manage_academic.php" class="text-lg hover:font-semibold hover:text-red-900">View Academic</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/department_side.svg" alt="department" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Department
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/manage_department.php" class="text-lg hover:font-semibold hover:text-red-900">View Department</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/section_side.svg" alt="section" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Section
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/manage_section.php" class="text-lg hover:font-semibold hover:text-red-900">View Section</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/criteria_side.svg" alt="criteria" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Criteria
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/criteria.php" class="text-lg hover:font-semibold hover:text-red-900">View Criteria</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/subject_side.svg" alt="subject" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Subject
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center">
            <a href="../admin/manage_subject.php" class="text-lg hover:font-semibold hover:text-red-900">View Subject</a>
          </div>
        </div>

        <div class="collapse hover:bg-blue-200 w-full">
          <input type="checkbox" />
          <div class="collapse-title text-center  flex justify-center items-center">
            <div>
              <img src="../admin/tools/img_side/archive_side.svg" alt="archive" class="w-16 h-16">
            </div>
            <div class="text-xl text-black flex-1">
              Manage Archive
            </div>
          </div>
          <div class="collapse-content text-center flex flex-col justify-start items-center gap-4">
            <a href="#viewStudent" class="text-lg hover:font-semibold hover:text-red-900">View Archive</a>
          </div>
        </div>

      </ul>
    </div>
  </div>
  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>