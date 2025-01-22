<?php

include('../database/models/dbconnect.php'); // Include database connection
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id']) && !isset($_SESSION['school_id']) || !isset($_SESSION['name'])) {
  echo "Please log in as a student to view your teachers.";
  exit;
}
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';



// $student_id = $_SESSION['student_id']; // Assuming student_id is passed in the form via POST
$school_id = $_SESSION['school_id']; // Get the school_id from the session
$fname = $_SESSION['name']; // Get the student name from the session

$schoolyear_query = "SELECT schoolyear_id FROM tblschoolyear WHERE is_status = 'Started' LIMIT 1";
$result = $conn->query($schoolyear_query);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $schoolyear_id = $row['schoolyear_id'];
} else {
  // Handle the case where there is no active school year
  echo "<script>
  alert('No active school year found.');
  window.location.href='../student/student_dashboard.php';
  </script>";

  exit;
}

// Check if the student is regular or irregular
$checkStudentStatus = $conn->prepare("SELECT is_regular FROM tblstudent_section WHERE student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)");
$checkStudentStatus->bind_param("i", $school_id);
$checkStudentStatus->execute();
$statusResult = $checkStudentStatus->get_result();
$is_regular = false;

if ($statusResult->num_rows > 0) {
  $status = $statusResult->fetch_assoc();
  $is_regular = $status['is_regular']; // Determine if student is regular
}

$teachers = [];

// Fetch teachers for regular students (based on sections) or irregular students (manual assignment)
if ($is_regular) {
  $sql = "
        SELECT tblteacher.name AS teacher_name, tblsubject.subject_name, tblteacher.image, tblteacher.teacher_id
        FROM tblstudent_section
        INNER JOIN tblsection_teacher_subject ON tblstudent_section.section_id = tblsection_teacher_subject.section_id
        INNER JOIN tblteacher ON tblsection_teacher_subject.teacher_id = tblteacher.teacher_id
        INNER JOIN tblsubject ON tblsection_teacher_subject.subject_id = tblsubject.subject_id
        WHERE tblstudent_section.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
    ";
} else {
  $sql = "
        SELECT tblteacher.name AS teacher_name, tblsubject.subject_name, tblteacher.image, tblteacher.teacher_id
        FROM tblstudent_teacher_subject
        INNER JOIN tblteacher ON tblstudent_teacher_subject.teacher_id = tblteacher.teacher_id
        INNER JOIN tblsubject ON tblstudent_teacher_subject.subject_id = tblsubject.subject_id
        WHERE tblstudent_teacher_subject.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
    ";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch teachers and subjects
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
  }
}

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
function storeEvaluation($teacher_id, $ratings, $comment, $schoolyear_id)
{
  global $conn;  // Ensure the connection variable is available

  // Start transaction for better error handling
  $conn->begin_transaction();

  try {
    // Retrieve the student_id from the session
    if (!isset($_SESSION['student_id'])) {
      throw new Exception("Student ID is missing from the session.");
    }
    $student_id = $_SESSION['student_id'];

    $check_evaluation_query = "SELECT * FROM tblevaluate WHERE teacher_id = ? AND student_id = ? AND schoolyear_id = ?";
    $stmt = $conn->prepare($check_evaluation_query);
    $stmt->bind_param("iis", $teacher_id, $student_id, $schoolyear_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If evaluation already exists, stop further processing
    if ($result->num_rows > 0) {
      throw new Exception(" <script>alert('You have already evaluated this teacher for this school year.');</script>");
    }


    // Insert evaluation data into tblevaluate
    $evaluate_query = "INSERT INTO tblevaluate (teacher_id, student_id, schoolyear_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($evaluate_query);
    $stmt->bind_param("iis", $teacher_id, $student_id, $schoolyear_id);
    $stmt->execute();

    // Get the last inserted evaluation_id
    $evaluation_id = $stmt->insert_id;

    // Insert ratings and comments
    if ($ratings) {
      foreach ($ratings as $criteria_id => $rating_value) {
        // Check if a comment exists, otherwise set it to '0'
        $comment_value = isset($comment[$criteria_id]) && trim($comment[$criteria_id]) !== '' ? trim($comment[$criteria_id]) : '0';

        // Insert the values into the database
        $rating_query = "INSERT INTO tblanswer (evaluate_id, criteria_id, ratings, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($rating_query);
        $stmt->bind_param("iiis", $evaluation_id, $criteria_id, $rating_value, $comment_value);
        $stmt->execute();
      }
    }

    // Commit the transaction
    $conn->commit();

    $_SESSION['current_teacher_index'] = $_SESSION['current_teacher_index'] + 1;

    // Redirect or continue to the next task
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } catch (Exception $e) {
    // Rollback the transaction if there's an error
    $conn->rollback();
    die("<script>alert('Error: You have already evaluated this teacher for this school year.');
    window.location.href='tryanderror.php';
    </script>" . $e->getMessage());
  }
}


// If the form is submitted, handle the evaluation
// Increment the current_teacher_index after evaluation

$offensiveWords = ['badword1', 'badword2', 'badword3', 'b a d w o r d 1']; // Replace with actual offensive words
$maxLetters = 50;
$filteredComment = "";

function sanitizeInput($input)
{
  return preg_replace('/[^a-zA-Z0-9\s.,]/', '', $input);
}

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//   $teacher_id = $_POST['teacher_id'];
//   $ratings = $_POST['rating'];  // Array of ratings for different criteria
//   $comment = $_POST['comment'];  // Array of comments for different criteria
//   $schoolyear_id = $_POST['schoolyear_id'];
//   $criteria_id = $_POST['criteria_id'];

//   // Store the evaluation 
//   storeEvaluation($teacher_id, $ratings, $comment, $schoolyear_id);

//   // Increment teacher index after evaluation
//   if (isset($_SESSION['current_teacher_index'])) {
//     $_SESSION['current_teacher_index'] += 1;
//   } else {
//     $_SESSION['current_teacher_index'] = 1;
//   }

//   // Redirect to refresh the page and move to the next teacher
//   header("Location: " . $_SERVER['PHP_SELF']);
//   exit;
// }



// // Sanitize input by removing special characters except letters, numbers, and whitespace


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher_id = $_POST['teacher_id'];
  $ratings = $_POST['rating'];  // Array of ratings for different criteria
  $comments = $_POST['comment']; // Array of comments for different criteria
  $schoolyear_id = $_POST['schoolyear_id'];
  $criteria_id = $_POST['criteria_id'];

  $filteredComments = [];
  $hasOffensiveContent = false;

  foreach ($comments as $index => $comment) {
    $sanitizedComment = sanitizeInput($comment);
    $letterCount = strlen($sanitizedComment);

    // Check if comment exceeds max letters
    if ($letterCount > $maxLetters) {
      $filteredComments[$index] = "Comment is too long. Maximum allowed characters: $maxLetters";
    } else {
      // Check for offensive words
      foreach ($offensiveWords as $word) {
        if (stripos($sanitizedComment, $word) !== false) {
          $hasOffensiveContent = true;
          break 2;  // Stop further processing if offensive content is detected
        }
      }
      $filteredComments[$index] = htmlspecialchars($sanitizedComment);
    }
  }

  if ($hasOffensiveContent) {
    echo "<script>
              alert('Your comment contains offensive words and cannot be submitted.');
              window.location.href = '" . $_SERVER['PHP_SELF'] . "';
            </script>";
    exit;  // Stop execution to avoid storing the data
  }

  // Store the evaluation if comments are within limits and contain no offensive words
  storeEvaluation($teacher_id, $ratings, $filteredComments, $schoolyear_id, $criteria_id);
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

<body>

  <div class="w-full bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-center text-4xl text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> - <span class="text-blue-500"><?php echo htmlspecialchars($_SESSION['school_id']); ?></span></h1>
    <h2 class="text-center text-2xl text-slate-900 mt-2">Your Assigned Teachers and Subjects</h2>

    <?php if (!empty($teachers)): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 w-full">
        <?php foreach ($teachers as $teacher): ?>
          <div class="bg-gray-100 border-s-8 border-green-900 p-4 rounded-lg shadow-md flex items-center hover:scale-105 transform transition-all cursor-pointer" onclick="selectTeacher(<?php echo $teacher['teacher_id']; ?>, '<?php echo addslashes($teacher['teacher_name']); ?>', '<?php echo addslashes($teacher['subject_name']); ?>')">
            <img src="../upload/pics/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Teacher Profile" class="w-20 h-20 rounded-full mr-4">
            <div class="text-gray-800">
              <p class="font-semibold text-2xl"><?php echo htmlspecialchars($teacher['teacher_name']); ?></p>
              <p class="text-gray-600 text-2xl"><?php echo htmlspecialchars($teacher['subject_name']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-600 mt-4">You have no assigned teachers yet.</p>
    <?php endif; ?>
  </div>

  <div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-center text-2xl text-gray-800">Evaluating Teacher: <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></h1>

    <img src="../upload/pics/<?php echo htmlspecialchars($currentTeacher['image']); ?>" alt="Teacher Profile" class="w-24 h-24 rounded-full mx-auto mt-4">
    <div class="text-center text-gray-800 mt-2">
      <p><span class="font-semibold">Teacher Name:</span> <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></p>
      <p><span class="font-semibold">Subject:</span> <?php echo htmlspecialchars($currentTeacher['subject_name']); ?></p>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-8">
      <h3 class="text-lg font-semibold mb-4">Selected Teacher and Subject</h3>

      <!-- Hidden inputs to store teacher, student, and schoolyear information -->
      <input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo htmlspecialchars($currentTeacher['teacher_id']); ?>">
      <input type="hidden" name="student_id" id="student_id" value="<?php echo $_SESSION['student_id']; ?>">
      <input type="hidden" name="schoolyear_id" id="schoolyear_id" value="<?php echo htmlspecialchars($schoolyear_id); ?>">

      <!-- Teacher and Subject Inputs -->
      <label for="teacher_name" class="block text-sm font-medium text-gray-700">Teacher:</label>
      <input type="text" name="teacher_name" id="teacher_name" value="<?php echo htmlspecialchars($currentTeacher['teacher_name']); ?>" readonly class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">

      <label for="subject_name" class="block text-sm font-medium text-gray-700 mt-4">Subject:</label>
      <input type="text" name="subject_name" id="subject_name" value="<?php echo htmlspecialchars($currentTeacher['subject_name']); ?>" readonly class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">

      <!-- Criteria List -->
      <div id="criterialist" class="mt-6">
        <?php if (count($criteriaList) > 0): ?>
          <ol class="space-y-4">
            <?php foreach ($criteriaList as $index => $listCriteria): ?>
              <li>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg shadow-sm">
                  <span class="font-semibold text-gray-800">
                    <?php echo htmlspecialchars($listCriteria['criteria']); ?>
                    <input type="hidden" name="criteria_id[<?php echo $listCriteria['criteria_id']; ?>]" value="<?php echo $listCriteria['criteria_id']; ?>">
                  </span>

                  <div class="flex space-x-3">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                      <label class="inline-flex items-center">
                        <input type="radio" name="rating[<?php echo $listCriteria['criteria_id']; ?>]" value="<?php echo $i; ?>" required class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-1 text-gray-700"><?php echo $i; ?></span>
                      </label>
                    <?php endfor; ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
        <?php else: ?>
          <div class="text-gray-500">No Criteria Available</div>
        <?php endif; ?>
      </div>

      <!-- Comment Section -->
      <div class="mt-6">
        <textarea id="comment" name="comment[<?php echo htmlspecialchars($currentTeacher['teacher_id']); ?>]" rows="5" cols="50" placeholder="Type your comment here..." oninput="updateCharCount()" class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 text-gray-700"></textarea>
        <p id="letterCount" class="text-sm text-gray-600 mt-2">Letter Count: 0 / 50</p>
        <p id="charLimitWarning" class="text-sm text-red-500 mt-2" style="display: none;">You have reached the maximum character limit!</p>
        <input type="submit" value="Submit Evaluation" class="mt-4 w-full bg-green-500 text-white py-2 px-4 rounded-md cursor-pointer hover:bg-green-600 transition-all">
      </div>
    </form>
  </div>

  <script>
    const offensiveWords = ['badword1', 'badword2', 'badword3', 'b a d w o r d 1']; // Replace with actual offensive words
    const maxLetters = 50;

    const commentInput = document.getElementById('comment');
    const letterCountDisplay = document.getElementById('letterCount');
    const charLimitWarning = document.getElementById('charLimitWarning');
    const submitButton = document.querySelector('input[type="submit"]');

    function sanitizeInput(input) {
      return input.replace(/[^a-zA-Z0-9\s.,]/g, ''); // Remove special characters but keep letters, numbers, and whitespace
    }

    commentInput.addEventListener('input', () => {
      let text = sanitizeInput(commentInput.value);
      const letterCount = text.length;
      letterCountDisplay.textContent = `Letter Count: ${letterCount} / ${maxLetters}`;

      if (letterCount > maxLetters) {
        charLimitWarning.style.display = 'block';
        letterCountDisplay.style.color = 'red';
        commentInput.value = text.substring(0, maxLetters); // Trim text to maxLetters
        submitButton.disabled = true;
      } else {
        charLimitWarning.style.display = 'none';
        letterCountDisplay.style.color = 'black';
        submitButton.disabled = false;
      }
    });

    // JavaScript function to set teacher and subject details
    function selectTeacher(teacherId, teacherName, subjectName) {
      document.getElementById('teacher_name').value = teacherName;
      document.getElementById('subject_name').value = subjectName;
      document.getElementById('teacher_id').value = teacherId;
    }

    function updateCharCount() {
      let comment = document.getElementById('comment');
      let charCount = comment.value.length;
      document.getElementById('letterCount').textContent = charCount + " characters";
    }
  </script>
</body>

</html>