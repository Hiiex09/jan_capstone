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
    <div class="flex-1 rounded-md">
      <a href="../admin/admin_dashboard.php" class="btn btn-ghost text-2xl text-white">Cebu Eastern College</a>
    </div>
    <div class="flex-none mt-1">
      <div class="dropdown dropdown-end">
        <!-- Dropdown Trigger -->
        <label tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-20 rounded-full">
            <img alt="profile-image" src="../<?= htmlspecialchars($_SESSION['image']) ?>" />
          </div>
        </label>

        <!-- Dropdown Content -->
        <ul class=" menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li class="w-full">
            <p class="text-sm"> Welcome, <span class="p-1 text-center"><?= htmlspecialchars($_SESSION['username']) ?></span></p>
          </li>
          <li>
            <div class="bg-base-300 p-5 m-1">
              <?php if (isset($_SESSION['username'])): ?>
                <div class="flex flex-col justify-start items-start gap-2">
                  <div class="text-sm">
                    <span>Academic Year: <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?></span>
                  </div>
                  <div class="text-sm">
                    <span>Semester: <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?></span>
                  </div>
                  <div class="text-sm">
                    <span>Status: <?= htmlspecialchars($_SESSION['is_status']) ?></span>
                  </div>
                <?php else: ?>
                  <div>Academic year and semester not set. Please set the active semester.</div>
                <?php endif; ?>
                </div>
            </div>
          </li>
          <li>
            <div class="flex flex-row justify-evenly items-center">
              <div class="text-xs text-start">
                Theme Settings
                <input
                  type="checkbox"
                  id="theme-toggle"
                  class="toggle theme-controller bg-base-content col-span-2 col-start-1 row-start-1 mt-1" />
              </div>
              <span class="badge badge-primary text-xs">New</span>
            </div>
          </li>
          <li>
            <a href="../logout.php" class="hover:font-bold hover:text-blue-900 mx-2">
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
            <a href="../admin/manage_criteria.php" class="hover:font-semibold hover:text-red-900">View Criteria</a>
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
  <footer class="fixed bottom-0 left-0 right-0 footer bg-base-300 items-center p-4">
    <aside class="grid-flow-col items-center">
      <svg
        width="36"
        height="36"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
        fill-rule="evenodd"
        clip-rule="evenodd"
        class="fill-current">
        <path
          d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path>
      </svg>
      <p>Copyright © <?php echo date("Y"); ?> - All rights reserved</p>
    </aside>
    <nav class="grid-flow-col gap-4 md:place-self-center md:justify-self-end">
      <a href="https://www.facebook.com/nelson.nellas.jr">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          class="fill-current">
          <path
            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
        </svg>
      </a>
    </nav>
  </footer>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Function to apply theme
    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      localStorage.setItem("theme", theme);
    }

    // Load theme from localStorage
    document.addEventListener("DOMContentLoaded", () => {
      const savedTheme = localStorage.getItem("theme") || "pastel";
      applyTheme(savedTheme);
      document.getElementById("theme-toggle").checked = savedTheme === "luxury";
    });

    // Toggle theme and save to localStorage
    document.getElementById("theme-toggle").addEventListener("change", function() {
      const newTheme = this.checked ? "luxury" : "pastel";
      applyTheme(newTheme);
    });
  </script>
</body>

</html>