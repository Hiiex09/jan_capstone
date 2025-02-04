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
      $teacher_name = $row['fname'];
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
</head>

<body class="bg-gray-100">

  <!-- Teacher's Information -->
  <div class="flex items-center bg-white shadow-lg rounded-lg p-4 mb-6">
    <img src="<?php echo !empty($teacher_image) ? '../upload/pics/' . htmlspecialchars($teacher_image) : '../upload/pics/default.jpg'; ?>"
      alt="Teacher's Image" class="w-20 h-20 rounded-full border border-gray-300">
    <div class="ml-4">
      <h1 class="text-2xl font-bold"><?= htmlspecialchars($teacher_name); ?></h1>
    </div>
  </div>

  <h2 class="text-xl font-semibold mb-4">Ratings for Teacher ID: <?= htmlspecialchars($teacher_id); ?></h2>

  <?php if (!empty($ratings)): ?>
    <!-- Ratings Table -->
    <div class="overflow-x-auto">
      <table class="table w-full bg-white shadow-md rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-4">Criteria</th>
            <th class="p-4">Average Rating</th>
          </tr>
        </thead>
        <tbody>
          <!-- Display Overall Average -->
          <tr>
            <td colspan="2" class="p-4 bg-gray-50">
              <div class="font-bold text-lg text-gray-700">Overall Average Rating: <?= number_format($overall_average, 2); ?></div>
            </td>
          </tr>
          <?php foreach ($ratings as $criteria => $rating_details): ?>
            <tr>
              <td class="p-4"><?= htmlspecialchars($criteria); ?></td>
              <td class="p-4">
                <?php
                // Calculate the average rating for each criteria
                $average_rating = $criteria_totals[$criteria] / $criteria_counts[$criteria];
                echo number_format($average_rating, 2);
                ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-gray-600">No ratings found for this teacher.</p>
  <?php endif; ?>

  <!-- Comments Section -->
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4">All Comments:</h3>
    <?php if (!empty($comments)): ?>
      <div class="space-y-4">
        <?php foreach ($comments as $comment): ?>
          <div class="bg-gray-100 p-4 rounded-lg shadow-md">
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