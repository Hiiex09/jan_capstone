<?php
include("../database/models/dbconnect.php");
session_start();

// Check if the teacher_id is provided in the URL
if (isset($_GET['teacher_id'])) {
  $teacher_id = $_GET['teacher_id']; // Get the teacher ID from the URL parameter

  // SQL query to fetch criteria names, ratings, and comments for the specified teacher
  $query = "
        SELECT 
            c.criteria, 
            r.ratings, r.comment,
            t.name AS teacher_name, t.image AS teacher_image
        FROM tblanswer r
        JOIN tblevaluate e ON r.evaluate_id = e.evaluate_id
        JOIN tblcriteria c ON r.criteria_id = c.criteria_id
        JOIN tblteacher t ON e.teacher_id = t.teacher_id
        WHERE e.teacher_id = ? 
        ORDER BY c.criteria";

  // Prepare and execute the statement using MySQLi
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $teacher_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Fetch all results and organize by criteria
  $ratings = [];
  $comments = [];
  $teacher_name = "";
  $teacher_image = "";
  $criteria_totals = [];  // For calculating total ratings per criteria
  $criteria_counts = [];  // For counting the number of ratings per criteria
  $total_ratings = 0;      // For calculating overall average
  $total_count = 0;        // For counting all ratings for overall average

  while ($row = mysqli_fetch_assoc($result)) {
    if (empty($teacher_name)) {
      // Store teacher details (name and image) only once
      $teacher_name = $row['teacher_name'];
      $teacher_image = $row['teacher_image'];
    }

    $criteria = $row['criteria'];

    // Add ratings and comments
    $ratings[$criteria][] = [
      'rating' => $row['ratings'],
      'comment' => $row['comment']
    ];

    // Add to comment array if not empty or "0"
    if (!empty($row['comment']) && $row['comment'] !== '0') {
      $comments[] = $row['comment'];
    }

    // Calculate total ratings and counts for average calculation per criteria
    if (!isset($criteria_totals[$criteria])) {
      $criteria_totals[$criteria] = 0;
      $criteria_counts[$criteria] = 0;
    }
    $criteria_totals[$criteria] += $row['ratings'];
    $criteria_counts[$criteria]++;

    // Calculate total ratings for overall average
    $total_ratings += $row['ratings'];
    $total_count++;
  }

  // Free result and close the statement
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  // Calculate overall average rating
  if ($total_count > 0) {
    $overall_average = $total_ratings / $total_count;
  } else {
    $overall_average = 0; // If no ratings, overall average is 0
  }
} else {
  echo "No teacher ID provided in the URL.";
  exit;
}
?>


<?php

$teacher_id = $_GET['teacher_id'];

$sql = "SELECT * FROM `tblteacher` WHERE teacher_id = $teacher_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$image = $row['image'];
$fullname = $row['name'];
?>

<?php
// Pagination Variables
$per_page = 5; // Display 5 rows per page
$total_items = count($ratings);
$total_pages = ceil($total_items / $per_page);
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); // Ensure valid page number

// Calculate the Offset
$offset = ($current_page - 1) * $per_page;

// Slice the Ratings Array for Display
$display_ratings = array_slice($ratings, $offset, $per_page, true);
?>

<?php include('../admin/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Teacher Ratings Status</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {},
      },
      plugins: [require("daisyui")],
    };
  </script>
  <link href="https://fonts.googleapis.com/css?family=Outfit:100,200,300,regular,500,600,700,800,900" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,200,300,regular,500,600,700,800,900,100italic,200italic,300italic,italic,500italic,600italic,700italic,800italic,900italic" rel="stylesheet" />
</head>

<body>
  <div class="px-5">
    <div>
      <h1 class="text-2xl" style="font-family: 'Roboto';">Teacher Evaluation Report</h1>
    </div>
  </div>
  <div class="grid grid-cols-2 rounded-md p-3 m-5 skeleton ">
    <!-- Teacher's Information -->
    <div class="flex flex-row justify-start items-start gap-6 p-4">
      <div class="flex-1 flex-row justify-evenly items-center gap-">
        <div class="flex-shrink-0">
          <img src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>"
            alt="Teacher's Image" class="w-60 h-60 rounded-md">
        </div>
        <div class="flex flex-col">
          <div class=" text-2xl font-bold p-1"><?php echo htmlspecialchars($row['name']); ?></div>
          <div class="text-sm p-1">Teacher ID: <?= htmlspecialchars($teacher_id); ?>
            <p class="text-sm">Academic Year: <b><?= htmlspecialchars($_SESSION['school_year']); ?></b></p>
            <p class="text-sm">Semester: <b><?= htmlspecialchars($_SESSION['semester']); ?></b></p>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4">
      <table class="w-full max-w-full text-center text-xs">
        <thead>
          <tr>
            <th class="border">Not Satisfied <b>(1)</b></th>
            <th class="border">Satisfied <b>(2)</b></th>
            <th class="border">Moderately Satisfied <b>(3)</b></th>
            <th class="border">Very Satisfied <b>(4)</b></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border">1.0 - 2.3</td>
            <td class="border">2.4 - 2.6</td>
            <td class="border">2.7 - 3.3</td>
            <td class="border">3.4 & Above</td>
          </tr>
        </tbody>
      </table>
      <?php if (!empty($ratings)): ?>

        <table class="w-full max-w-full text-center text-sm p-1 mt-2">
          <thead>
            <tr>
              <th class="text-start">Criteria</th>
              <th>Average Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ratings as $criteria => $rating_details): ?>
              <tr>
                <td class="text-start"> <?= htmlspecialchars($criteria); ?> </td>
                <td>
                  <?php
                  $average_rating = $criteria_totals[$criteria] / $criteria_counts[$criteria];
                  echo number_format($average_rating, 2);
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="text-sm text-start pt-1 mt-5 border-t">
          <div class="flex flex-row justify-between">
            <div>Overall Average Rating :</div>
            <div class="me-20"><?= number_format($overall_average, 2); ?></div>
          </div>
          <div class="flex flex-row justify-between">
            <div> Rating Legend :</div>
            <div class="me-12"> <?php
                                if ($overall_average >= 1 && $overall_average <= 2.3) {
                                  echo "Not Satisfied";
                                } elseif ($overall_average > 2.3 && $overall_average <= 2.6) {
                                  echo "Satisfied";
                                } elseif ($overall_average > 2.6 && $overall_average <= 3.3) {
                                  echo "Moderately Satisfied";
                                } elseif ($overall_average > 3.3) {
                                  echo "Very Satisfied";
                                } else {
                                  echo "No Rating";
                                }
                                ?></div>
          </div>
        </div>
      <?php else: ?>
        <p class="text-gray-600 text-center">No ratings found for this teacher.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Comments Section -->
  <div class="flex-auto p-6">
    <h3 class="text-xl font-semibold mb-4">All Comments:</h3>
    <?php if (!empty($comments)): ?>
      <div class="space-y-4">
        <?php foreach ($comments as $comment): ?>
          <div class="text-sm">
            <?= nl2br(htmlspecialchars($comment)); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-gray-600">No comments available.</p>
    <?php endif; ?>
  </div>


</body>

</html>