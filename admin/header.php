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

  <div class="navbar bg-slate-900 shadow shadow-gray-800">
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
    <div class="mx-5">
      <label class="grid cursor-pointer place-items-center">
        <input
          type="checkbox"
          id="theme-toggle"
          class="toggle theme-controller bg-base-content col-span-2 col-start-1" />
        <svg class="stroke-base-100 fill-base-100 col-start-1 row-start-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5" />
          <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
        </svg>
        <svg class="stroke-base-100 fill-base-100 col-start-2 row-start-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
      </label>
    </div>
  </div>

  <div class="drawer z-50">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content m-4 flex justify-start items-center gap-3">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-900">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M5 17h14" />
        </svg>
      </div>
      <div>
        <label for="my-drawer" class="cursor-pointer drawer-button text-4xl">Dashboard</label>
      </div>
    </div>


    <div class="drawer-side">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <ul class="menu bg-base-200 text-base-content min-h-full w-80">


        <!-- Student Table & Management -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50 pt-5">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H2v-2a3 3 0 013-3h2m6-6a3 3 0 100-6 3 3 0 000 6z" />
            </svg>
            <span class="text-lg text-black">Manage Student</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_student.php" class="hover:font-semibold text-sm ">View Student</a>
          </div>
        </div>


        <!-- Teacher Table & Management -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25c-4.97 0-9 1.567-9 3.5v2.25h18V17.75c0-1.933-4.03-3.5-9-3.5zM12 3.75a3 3 0 00-3 3V9c0 .818.393 1.5.75 1.5h4.5c.357 0 .75-.682.75-1.5V6.75a3 3 0 00-3-3z" />
            </svg>
            <span class="text-lg text-black">Manage Teacher</span>
          </div>
          <div class="collapse-content p-2 flex flex-col">
            <a href="../admin/manage_teacher.php" class="hover:font-semibold hover:text-red-900">View Teacher</a>
            <a href="../admin/manage_reg_student.php" class="hover:font-semibold hover:text-red-900">Assign Regular Student</a>
            <a href="../admin/manage_irreg_student.php" class="hover:font-semibold hover:text-red-900">Assign Irregular Student</a>
          </div>
        </div>

        <!-- Manage Admin -->

        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 01-3.75-3.75 3.75 3.75 0 017.5 0A3.75 3.75 0 0112 15.75zM8.25 12A3.75 3.75 0 1112 8.25 3.75 3.75 0 018.25 12z" />
            </svg>
            <span class="text-lg text-black">Manage Admin</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_admin.php" class="hover:font-semibold hover:text-red-900">View Admin</a>
          </div>
        </div>

        <!-- Manage Academic -->

        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 01-3.75-3.75 3.75 3.75 0 017.5 0A3.75 3.75 0 0112 15.75zM8.25 12A3.75 3.75 0 1112 8.25 3.75 3.75 0 018.25 12z" />
            </svg>
            <span class="text-lg text-black">Manage Academic</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_academic.php" class="hover:font-semibold hover:text-red-900">View Academic</a>
          </div>
        </div>

        <!-- Manage Department -->

        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
            <span class="text-lg text-black">Manage Department</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_department.php" class="hover:font-semibold hover:text-red-900">View Department</a>
          </div>
        </div>

        <!-- Manage Section -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
            <span class="text-lg text-black">Manage Section</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_section.php" class="hover:font-semibold hover:text-red-900">View Section</a>
          </div>
        </div>

        <!-- Manage Criteria -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
            <span class="text-lg text-black">Manage Criteria</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/criteria.php" class="hover:font-semibold hover:text-red-900">View Criteria</a>
          </div>
        </div>


        <!-- Manage Subject -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M4 5a2 2 0 012-2h12a2 2 0 012 2M4 5a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2m-4 6H8m4 4H8" />
            </svg>
            <span class="text-lg text-black">Manage Subject</span>
          </div>
          <div class="collapse-content p-2">
            <a href="../admin/manage_subject.php" class="hover:font-semibold hover:text-red-900">View Subject</a>
          </div>
        </div>


        <!-- Manage Archive -->
        <div class="collapse hover:shadow-lg hover:shadow-base-300/50">
          <input type="checkbox" />
          <div class="collapse-title flex items-center gap-3 p-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M4 5a2 2 0 012-2h12a2 2 0 012 2M4 5a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2m-4 6H8m4 4H8" />
            </svg>
            <span class="text-lg text-black">Manage Archive</span>
          </div>
          <div class="collapse-content p-2">
            <a href="#viewArchive" class="hover:font-semibold hover:text-red-900">View Archive</a>
          </div>
        </div>

      </ul>
    </div>
  </div>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Function to apply theme
    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      localStorage.setItem("theme", theme);
    }

    // Load theme from localStorage
    document.addEventListener("DOMContentLoaded", () => {
      const savedTheme = localStorage.getItem("theme") || "nord";
      applyTheme(savedTheme);
      document.getElementById("theme-toggle").checked = savedTheme === "luxury";
    });

    // Toggle theme and save to localStorage
    document.getElementById("theme-toggle").addEventListener("change", function() {
      const newTheme = this.checked ? "luxury" : "nord";
      applyTheme(newTheme);
    });
  </script>
</body>

</html>