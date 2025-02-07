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
</head>

<body>

  <div class="grid grid-cols-1 rounded-md p-3 m-5 skeleton ">
    <!-- Teacher's Information -->
    <div class="flex flex-row justify-start items-start gap-6 p-4">
      <div class="flex-1 flex-row justify-evenly items-center gap-">
        <div class="flex-shrink-0">
          <img src="../upload/pics/<?php echo htmlspecialchars($row['image']); ?>"
            alt="Teacher's Image" class="w-60 h-60 rounded-md">
        </div>
        <div class="flex flex-col gap-4 text-center md:text-left">
          <h1 class=" text-3xl font-bold p-2"><?php echo htmlspecialchars($row['name']); ?></h1>
          <h2 class="text-sm p-1">Teacher ID: <?= htmlspecialchars($teacher_id); ?></h2>
          <!-- Display Overall Average -->
          <div class="flex flex-col justify-center gap-3">
            <div class="text-sm flex items-center gap-2">
              <span>1</span>
              <progress class="progress progress-error w-56" value="<?= number_format($overall_average, 2); ?>" max="100"></progress>
            </div>
            <div class="text-sm flex items-center gap-2">
              <span>2</span>
              <progress class="progress progress-warning w-56" value="40" max="100"></progress>
            </div>
            <div class="text-sm flex items-center gap-2">
              <span>3</span>
              <progress class="progress progress-accent w-56" value="50" max="100"></progress>
            </div>
            <div class="text-sm flex items-center gap-2">
              <span>4</span>
              <progress class="progress progress-success w-56" value="80" max="100"></progress>
            </div>
          </div>
          <div class="flex justify-between items-center border-t pt-2">
            <div class="text-sm">Overall Average Rating: </div>
            <div class="text-sm px-2 font-semibold"> <?= number_format($overall_average, 2); ?> / <span><?php echo  $criteria_totals[$criteria] ?></span></div>
          </div>
        </div>
        <!-- Table -->
      </div>
      <!-- Ratings Table -->
      <div class="flex-auto flex-col items-center md:items-start">
        <?php if (!empty($display_ratings)): ?>
          <div class="overflow-x-auto w-full">
            <table class="table w-full shadow-md rounded-lg">
              <thead>
                <tr>
                  <th class="p-4">Criteria</th>
                  <th class="p-4">Average Rating</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($display_ratings as $criteria => $rating_details): ?>
                  <tr>
                    <td class="p-4">
                      <div>
                        <?= htmlspecialchars($criteria); ?>
                        <div>
                          <p class="text-xs">Progress</p>
                          <progress class="progress progress-secondary w-full" value="6540" max="8000"></progress>
                        </div>
                      </div>
                    </td>
                    <td class="p-4 text-center">
                      <?php
                      $average_rating = $criteria_totals[$criteria] / $criteria_counts[$criteria];
                      echo number_format($average_rating, 2);
                      ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="flex justify-center space-x-2 mt-4">
            <?php if ($current_page > 1): ?>
              <a href="?teacher_id=<?= $_GET['teacher_id']; ?>&page=<?= $current_page - 1; ?>" class="btn btn-sm btn-outline btn-neutral btn-outline text-xs">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <a href="?teacher_id=<?= $_GET['teacher_id']; ?>&page=<?= $i; ?>" class="btn btn-sm btn-outline btn-neutral <?= $i == $current_page ? 'btn-neutral' : 'btn-outline'; ?>"><?= $i; ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
              <a href="?teacher_id=<?= $_GET['teacher_id']; ?>&page=<?= $current_page + 1; ?>" class="btn btn-sm btn-outline btn-neutral btn-outline text-xs">Next</a>
            <?php endif; ?>
          </div>

        <?php else: ?>
          <p class="text-gray-600">No ratings found for this teacher.</p>
        <?php endif; ?>
      </div>
      <!-- Comments Section -->
      <div class="flex-auto">
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
    </div>
  </div>





</body>

</html>