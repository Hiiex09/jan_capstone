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
  <!-- <aside>
    <div class="container">
      <div class="fixed bottom-0 left-0 top-0 z-50 w-[270px] bg-white shadow">
        <div class=" text-2xl text-center flex justify-center items-center hover:border py-1 rounded-sm cursor-pointer">
          <div class="mx-3">
            <img
              src="../admin/Images/CEC.png"
              alt="school_year-image"
              class="h-10 w-10">
          </div>
          <div>
            <a href="../student/studentDashboard.php" class="cursor-pointer">Dashboard</a>
          </div>
        </div>
        <div class="mt-10 text-2xl text-center flex justify-center items-center hover:bg-blue-200 py-1 rounded-sm cursor-pointer">
          <div>
            <img
              src="../admin/img_side/criteria_side.svg"
              alt="school_year-image"
              class="h-10 w-10">
          </div>
          <div class="mx-3">
            <a href="../student/tryanderror.php"
              class="cursor-pointer text-2xl">
              Evaluate
            </a>
          </div>
        </div>
        <div class="mt-10 text-2xl text-center flex justify-center items-center hover:bg-blue-200 py-1 rounded-sm cursor-pointer">
          <div>
            <img
              src="../admin/img_side/logout_side.svg"
              alt="school_year-image"
              class="h-10 w-10">
          </div>
          <div class="mx-5">
            <a href="../logout.php"
              class="cursor-pointer text-2xl">
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </aside> -->

  <main class="fixed top-0 left-0 right-0 bottom-0 z-10 w-full">
    <div class="grid grid-cols-1 md:grid-rows-1">

      <div class="text-gray-800 p-3 h-[60px] border hidden md:flex justify-between items-center text-white rounded-md ">
        <div class="mx-1 text-2xl text-center flex justify-center items-center py-1 rounded-sm cursor-pointer">
          <div class="mx-1">
            <img
              src="../admin/tools/Images/CEC.png"
              alt="school_year-image"
              class="h-10 w-10">
          </div>
          <div class="mx-3">
            <a href="../student/student_dashboard.php" class="cursor-pointer text-black">Cebu Eastern College</a>
          </div>
        </div>


        <div class="text-black me-4 flex justify-center items-center gap-2">
          <div class="h-8 w-8">
            <img src="../admin/tools/img_side/logout_side.svg" alt="logout_sidebar">
          </div>
          <div>
            <a href="../logout.php"
              class="cursor-pointer text-lg hover:font-semibold hover:text-blue-600">
              Logout
            </a>
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
              href="../student/manage_evaluation.php  "
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
  </main>

</body>

</html>