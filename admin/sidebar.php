<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" /> -->
</head>

<body>
  <div class="drawer z-50">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content m-4 flex justify-start items-center gap-3">
      <div>
        <img src="../admin/tools/Images/dashboard.svg" alt="logo" class="w-10 h-10">
      </div>
      <div>
        <label for="my-drawer" class="cursor-pointer drawer-button text-4xl font-bold text-blue-900">Cebu Eastern College</label>
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
            <a href="../admin/student_table.php" class="text-lg hover:font-semibold hover:text-red-900">View Student</a>
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
            <a href="../admin/teacher_table.php" class="text-lg hover:font-semibold hover:text-red-900">View Teacher</a>
            <a href="../admin/regular_student.php" class="hover:font-semibold text-lg flex justify-start items-center gap-4">
              <img src="../admin/tools/img_side/assign_side.svg" alt="assing_side" class="w-7 h-7">
              <p class="hover:text-red-900">Assign Regular Student</p>
            </a>
            <a href="../admin/irreg_student.php" class="hover:font-semibold text-lg flex justify-start items-center gap-4">
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
            <a href="../admin/create_acad.php" class="text-lg hover:font-semibold hover:text-red-900">View Academic</a>
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
            <a href="../admin/department.php" class="text-lg hover:font-semibold hover:text-red-900">View Department</a>
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
            <a href="../admin/section.php" class="text-lg hover:font-semibold hover:text-red-900">View Section</a>
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
            <a href="#viewStudent" class="text-lg hover:font-semibold hover:text-red-900">View Subject</a>
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
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</body>

</html>