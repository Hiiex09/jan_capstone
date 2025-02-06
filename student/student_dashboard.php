<?php
include('../database/models/dbconnect.php');
session_start();

if (!isset($_SESSION['name']) && !isset($_SESSION['school_year']) && !isset($_SESSION['semester']) && !isset($_SESSION['is_status'])) {
  header("Location: ../login.php");
  exit();
}

$schoolyear_query = $conn->query("SELECT school_year, semester, is_status FROM tblschoolyear WHERE is_status = 'Started'");
$schoolyear = $schoolyear_query->fetch_assoc();

if ($schoolyear) {
  $_SESSION['school_year'] = $schoolyear['school_year'];
  $_SESSION['semester'] = $schoolyear['semester'];
  $_SESSION['is_status'] = $schoolyear['is_status'];
} else {
  $_SESSION['school_year'] = "Not Set";
  $_SESSION['semester'] = "Not Yet Started";
  $_SESSION['is_status'] = "Inactive";
}
?>
<?php

$student_id = $_SESSION['school_id'];

$sql = "SELECT * FROM `tblstudent` WHERE school_id = $student_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$fullname = $row['name'];
$email = $row['email'];
$image = $row['image'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <header>
    <div class="grid grid-cols-1 md:grid-rows-1">
      <div class="navbar bg-base-100 flex justify-start items-center p-5 border-b shadow h-16">
        <div>
          <a class="btn btn-sm   btn-neutral btn-outline text-lg rounded-md" href="../student/student_dashboard.php">Cebu Eastern College</a>
        </div>
      </div>
    </div>
  </header>
  <main class="flex gap-5">
    <aside>
      <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col items-start justify-start">
          <!-- Page content here -->
          <label for="my-drawer-2" class="btn btn-md btn-outline drawer-button lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-900">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14M5 12h14M5 17h14" />
            </svg>
          </label>
        </div>
        <div class="drawer-side w-full border">
          <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
          <ul class="menu text-base-content min-h-full w-80 p-4">
            <!-- Sidebar content here -->
            <li>
              <div class="flex justify-center items-center p-2 w-full">
                <img id="image-preview"
                  src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>"
                  alt="Image Preview"
                  class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl h-auto object-cover mt-4 rounded-md">
              </div>
              <div class="flex flex-col justify-center items-center w-full p-3">
                <div class="text-center text-lg"><?php echo $fullname; ?></div>
                <div class="text-sm">Student Name</div>
              </div>
            </li>
            <li>
              <?php if (isset($_SESSION['name'])): ?>
                <p>Academic Year: <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?></p>
                <p>Semester: <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?></p>
                <p>Status : <?= htmlspecialchars($_SESSION['is_status']) ?></p>
              <?php else: ?>
                <p>Academic year and semester not set. Please set the active semester.</p>
              <?php endif; ?>
            </li>
            <li>
              <div class="flex flex-row justify-between items-center">
                <div class="text-sm text-start">
                  Theme Settings
                </div>
                <div>
                  <input
                    type="checkbox"
                    id="theme-toggle"
                    class="toggle theme-controller bg-base-content col-span-2 col-start-1 row-start-1 mt-1" />
                  <span class="badge badge-primary text-xs animate-bounce mx-1">New</span>
                </div>
              </div>
            </li>
            <li>
              <a href="../student/update_student.php?update_student_id=<?= $_SESSION['school_id'] ?>" class="cursor-pointer text-sm hover:text-blue-600">
                Update Profile
              </a>
            </li>
            <li>
              <a href="../logout.php" class="cursor-pointer text-sm hover:text-red-600">
                Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </aside>
    <section class="p-5 m-5">
      <h2 class="text-5xl"> <br>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
      <p class="text-justify leading-relaxed m-2">
        Your Feedback plays a crucial role in helping us understand what works in the classroom and where we can improve. <br>
        We value your insights and encourage you to share your thoughts openly and respectfully <br>
        to help create a better learning experience For Everyone.
      </p>
      <a href="../student/manage_evaluation.php" class="btn btn-sm btn-outline rounded-md">
        Start Evaluate
      </a>
    </section>
  </main>


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
      document.getElementById("theme-toggle").checked = savedTheme === "night";
    });

    // Toggle theme and save to localStorage
    document.getElementById("theme-toggle").addEventListener("change", function() {
      const newTheme = this.checked ? "night" : "pastel";
      applyTheme(newTheme);
    });
  </script>
</body>

</html>