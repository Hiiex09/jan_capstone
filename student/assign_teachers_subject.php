<?php

include('../database/models/dbconnect.php'); // Include database connection
session_start();
// Check if the student is logged in
if (!isset($_SESSION['school_id']) || !isset($_SESSION['name'])) {
  echo "Please log in as a student to view your teachers.";
  exit;
}

$school_id = $_SESSION['school_id']; // Get the school_id from the session
$fname = $_SESSION['name']; // Get the student name from the session

// Query to fetch assigned teachers and subjects for the logged-in student
$sql = "
    SELECT tblteacher.name AS teacher_name, tblsubject.subject_name, tblteacher.image
    FROM tblstudent_teacher_subject
    INNER JOIN tblteacher ON tblstudent_teacher_subject.teacher_id = tblteacher.teacher_id
    INNER JOIN tblsubject ON tblstudent_teacher_subject.subject_id = tblsubject.subject_id
    WHERE tblstudent_teacher_subject.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch teachers and subjects
$teachers = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
  }
}

// Close prepared statement
$stmt->close();

$criteriaList = displayCriteria();

$currentTeacherIndex = isset($_SESSION['current_teacher_index']) ? $_SESSION['current_teacher_index'] : 0;

// Check if all teachers have been evaluated
if ($currentTeacherIndex >= count($teachers)) {
  // Reset index if all teachers have been evaluated
  unset($_SESSION['current_teacher_index']);
  echo "<h2>All teachers have been evaluated. Thank you!</h2>";
  exit;
}

// Get current teacher
$currentTeacher = $teachers[$currentTeacherIndex];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process ratings and comments for the current teacher
  // For example:
  // saveEvaluation($_POST['rating'], $_POST['comment'], $currentTeacher['name']);

  // Move to the next teacher
  $_SESSION['current_teacher_index'] = $currentTeacherIndex + 1;
  header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
  exit;
}

function displayCriteria()
{
  global $conn; // Access the $conn variable from the global scope
  try {
    $sql = "SELECT criteria_id, criteria FROM tblcriteria";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $criteriaList = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $criteriaList[] = $row; // Assuming 'criteria' is the column name
      }
    }
    return $criteriaList;
  } catch (mysqli_sql_exception $e) {
    error_log("Error fetching criteria: " . $e->getMessage());
    return [];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Assigned Teachers</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
  <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-semibold text-center mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> - <?php echo htmlspecialchars($_SESSION['school_id']); ?></h1>
    <h2 class="text-xl font-medium text-center mb-6">Your Assigned Teachers and Subjects</h2>

    <?php if (!empty($teachers)): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($teachers as $teacher): ?>
          <div class="bg-gray-200 p-4 rounded-lg shadow-md text-center">
            <img class="w-24 h-24 object-cover rounded-full mx-auto mb-4" src="../pic/pics/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Teacher Profile">
            <div class="teacher-info">
              <p class="text-lg font-medium"><span class="font-bold">Teacher Name:</span> <?php echo htmlspecialchars($teacher['teacher_name']); ?></p>
              <p class="text-lg font-medium"><span class="font-bold">Subject:</span> <?php echo htmlspecialchars($teacher['subject_name']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-lg text-gray-500">You have no assigned teachers yet.</p>
    <?php endif; ?>


    <h1 class="text-xl font-medium mt-8 mb-4">Evaluating Teacher: <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></h1>

    <div class="bg-gray-200 p-6 rounded-lg shadow-md mb-6 text-center">
      <img class="w-24 h-24 object-cover rounded-full mx-auto mb-4" src="../pic/pics/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Teacher Profile">
      <div class="teacher-info">
        <p class="text-lg font-medium"><span class="font-bold">Teacher Name:</span> <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></p>
        <p class="text-lg font-medium"><span class="font-bold">Subject:</span> <?php echo htmlspecialchars($currentTeacher['subject_name']); ?></p>
      </div>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="bg-white p-6 rounded-lg shadow-md">
      <div id="criterialist">
        <?php if (count($criteriaList) > 0): ?>
          <ol class="space-y-4">
            <?php foreach ($criteriaList as $index => $listCriteria): ?>
              <li class="space-y-2">
                <div class="text-lg font-medium"><?php echo htmlspecialchars($listCriteria['criteria']); ?></div>
                <div class="flex space-x-4">
                  <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label class="inline-flex items-center space-x-2">
                      <input type="radio" name="rating[<?php echo $criteria; ?>][<?php echo $index; ?>]" value="<?php echo $i; ?>" class="form-radio text-blue-500">
                      <span><?php echo $i; ?></span>
                    </label>
                  <?php endfor; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
        <?php else: ?>
          <div class="text-center text-lg text-gray-500">No Criteria Available</div>
        <?php endif; ?>
      </div>

      <div class="mt-6">
        <textarea id="comment" name="comment[<?php echo $currentTeacher['teacher_name']; ?>]" rows="5" class="w-full p-4 border border-gray-300 rounded-md" placeholder="Type your comment here..."></textarea>
        <p id="letterCount" class="mt-2 text-gray-500">Letter Count: 0 / 50</p>
        <p id="charLimitWarning" class="text-red-500 text-sm mt-2" style="display: none;">You have reached the maximum character limit!</p>
      </div>

      <div class="mt-6 text-center">
        <button id="submitComment" type="submit" name="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600">Submit Evaluation</button>
      </div>
    </form>
  </div>

  <script>
    const offensiveWords = ['badword1', 'badword2', 'badword3']; // Replace with actual offensive words
    const maxLetters = 50;

    const commentInput = document.getElementById('comment');
    const letterCountDisplay = document.getElementById('letterCount');
    const charLimitWarning = document.getElementById('charLimitWarning');
    const submitButton = document.getElementById('submitComment');

    function sanitizeInput(input) {
      return input.replace(/[^a-zA-Z0-9\s.,]/g, ''); // Remove special characters but keep letters, numbers, and whitespace
    }

    commentInput.addEventListener('input', () => {
      let text = sanitizeInput(commentInput.value);
      const letterCount = text.length;
      letterCountDisplay.textContent = `Letter Count: ${letterCount} / ${maxLetters}`;

      if (letterCount <= maxLetters) {
        charLimitWarning.style.display = 'none';
        letterCountDisplay.style.color = 'black';
        commentInput.value = text.substring(0, maxLetters);
        submitButton.disabled = null;
      } else {
        charLimitWarning.style.display = 'block';
        letterCountDisplay.style.color = 'red';
        submitButton.disabled = true;
      }
    });
  </script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>