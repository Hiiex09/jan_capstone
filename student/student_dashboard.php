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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="grid grid-cols-1 md:grid-rows-1">
    <div class="navbar bg-base-100 flex justify-between items-center p-5 border-b shadow h-16">
      <div>
        <a class="btn btn-neutral btn-outline text-black text-2xl" href="../student/student_dashboard.php">Cebu Eastern College</a>
      </div>
      <div class="text-black me-4 flex justify-between items-center gap-2">
        <div class="h-12 w-12 bg-base-300 shadow-lg border rounded-full flex justify-center items-center"
          onclick="toggleLogout()">
          <h1 class="text-2xl cursor-pointer"><?= strtoupper(substr($_SESSION['name'], 0, 1)) ?></h1>
        </div>
        <div id="logout-section" class="flex justify-center items-center gap-2 hidden">
          <div class="h-8 w-8">
            <img src="../admin/tools/img_side/logout_side.svg" alt="logout_sidebar">
          </div>
          <div>
            <a href="../logout.php" class="cursor-pointer text-lg hover:text-red-600">
              Logout
            </a>
            <a href="../student/update_student.php?update_student_id=<?= $_SESSION['school_id'] ?>" class="cursor-pointer text-sm hover:text-blue-600 mx-3">Update Profile</a>
          </div>
        </div>
      </div>
    </div>

    <div class="text-gray-800 h-[600px] m-2 rounded-md flex justify-evenly items-center">
      <div>
        <img src="../student/gif/eval_gif.gif" alt="eval">
      </div>
      <div class="w-1/3">
        <h2 class="text-5xl">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
        <hr class="mt-4">
        <p class="text-justify text-2xl text-slate-900 leading-relaxed m-2">
          Your <span class="text-red-900 font-bold hover:underline underline-offset-8">Feedback</span> plays a crucial role in helping us understand what works in the classroom and where we can improve.
          We value your insights and encourage you to share your thoughts openly and respectfully
          to help create a better learning experience <span class="font-bold text-blue-900 hover:underline underline-offset-8">For Everyone.</span>
        </p>
        <div class="mt-10">
          <a
            href="../student/manage_evaluation.php?student_id=<?php echo $_SESSION['school_id']; ?>"
            class="relative px-20 py-4 bg-blue-900 
              hover:bg-blue-500 
              text-white text-center text-2xl rounded-md
              hover:border-s-8 border-slate-900">
            <img src="../admin/tools/Images/send.svg" alt="School ID"
              class="w-8 h-8 absolute top-5 left-10 ">
            Start Evaluate
          </a>

        </div>

      </div>

    </div>

    <div class="text-gray-800 h-[300px] mt-3 border bg-slate-900 text-white">
      <?php if (isset($_SESSION['name'])): ?>

        <div class="m-4">
          <p class="text-3xl">Academic Year :
            <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?>
          </p>
        </div>
        <div class="m-4">
          <p class="text-3xl">Semester :
            <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?>
          </p>
        </div>
        <div class="m-4">
          <p class="text-3xl">Status : <?= htmlspecialchars($_SESSION['is_status']) ?></p>
        <?php else: ?>
        </div>
        <div class="m-4">
          <p class="text-3xl">Academic year and semester not set. Please set the active semester.</p>
        <?php endif; ?>
        </div>
    </div>

  </div>

  <script>
    function toggleLogout() {
      const logoutSection = document.getElementById('logout-section');
      // Toggle the visibility of the logout section
      logoutSection.classList.toggle('hidden');
    }
  </script>
</body>

</html>