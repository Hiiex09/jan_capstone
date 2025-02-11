<?php
session_start();
include('../database/models/dbconnect.php'); // Include database connection
include("../admin/criteria.php");

// Check if the student is logged in
if (!isset($_SESSION['student_id']) && !isset($_SESSION['school_id']) || !isset($_SESSION['name'])) {
  echo "Please log in as a student to view your teachers.";
  exit;
}

$student_id = $_SESSION['student_id']; // Assuming student_id is passed in the form via POST
$school_id = $_SESSION['school_id']; // Get the school_id from the session
$fname = $_SESSION['name']; // Get the student name from the session

$schoolyear_query = "SELECT schoolyear_id FROM tblschoolyear WHERE is_status = 'Started' LIMIT 1";
$result = $conn->query($schoolyear_query);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $schoolyear_id = $row['schoolyear_id'];
} else {
  // Handle the case where there is no active school year
  $errorMessage = 'No Active Schoolyear Found.';
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>
  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },100);
  </script>
 </body>
  </html>
  ";

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
if ($result->num_rows === 0) {
  $errorMessage = 'You do not have any teachers assigned. Please contact your administrator.';
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>

  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },1000);
  </script>

  </body>
  </html>
  ";
  exit;
}

// Fetch teachers and subjects into an array
$teachers = [];
while ($row = $result->fetch_assoc()) {
  $teachers[] = $row;
}



$stmt->close();

$criteriaList = displayCriteria();

$currentTeacherIndex = isset($_SESSION['current_teacher_index']) ? $_SESSION['current_teacher_index'] : 0;

// Check if all teachers have been evaluated
if ($currentTeacherIndex >= count($teachers)) {

  $errorMessage = 'You do not have any teachers assigned. Please contact your administrator.';
  // Reset index if all teachers have been evaluated
  unset($_SESSION['current_teacher_index']);
  echo "
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Error</title>
      <!-- Ensure Tailwind CSS or your styles are linked -->
      <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
      <style>
          /* Ensure modal is properly centered */
          .modal {
              display: flex;
              justify-content: center;
              align-items: center;
              position: fixed;
              inset: 0;
              
              z-index: 50;
          }
      </style>
  </head>
  <body>

  <div id='errorModal' class='modal'>
      <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 relative'>
          <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
          <div class='flex justify-center'>
              <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('errorModal').style.display = 'flex';
      });
      setTimeout(()=>{
      window.location.href='../student/student_dashboard.php';
      },100);
  </script>

  </body>
  </html>
  ";
  exit;
}

// Get current teacher
$currentTeacher = $teachers[$currentTeacherIndex];

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
    $errorMessage = 'You have already evaluated this teacher for this school years.';



    // If evaluation already exists, stop further processing
    if ($result->num_rows > 0) {
      $all_evaluated = allTeachersEvaluated($_SESSION['student_id'], $schoolyear_id);

      if ($all_evaluated) {

        $student_id = $_SESSION['school_id'];

        throw new Exception($all_evaluated);
      } else {
        throw new Exception("<div id='errorModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
            <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 absolute top-72 left-1/3'>
                <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
                <div class='flex justify-between'>
                    <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
                    <a href='studentDashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
                </div>
            </div>
        </div>
        <script>document.getElementById('errorModal').style.display = 'block';</script>
    ");
      }
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
    $errorMessage = 'You have already evaluated this teacher for this school year.';

    echo "
        <div id='errorModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
        <div class='bg-red-500 p-9 rounded-lg shadow-lg w-1/3 absolute  top-72 left-1/3'>
        <h2 class='text-center text-2xl text-white mb-4'>$errorMessage</h2>
        <div class='flex justify-between'>
        <a href='javascript:history.back()' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>OK</a>
        <a href='studentDashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
        </div>
        </div>
        </div>
        <script>document.getElementById('errorModal').style.display = 'block';</script>
        " . $e->getMessage();
  }
}

$offensiveWords = ['badword1', 'badword2', 'badword3']; // Replace with actual offensive words
$maxLetters = 50;
$filteredComment = "";

// Sanitize input by removing special characters except letters, numbers, and whitespace
function sanitizeInput($input)
{
  return preg_replace('/[^a-zA-Z0-9\s.,]/', '', $input);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher_id = $_POST['teacher_id'];
  $ratings = $_POST['rating'];  // Array of ratings for different criteria
  $comments = $_POST['comment']; // Array of comments for different criteria
  $schoolyear_id = $_POST['schoolyear_id'];

  // Sanitize comments
  foreach ($comments as $key => $comment) {
    $filteredComment = sanitizeInput($comment);

    // Check for offensive words
    foreach ($offensiveWords as $offensiveWord) {
      if (stripos($filteredComment, $offensiveWord) !== false) {
        echo "<script>alert('Your comment contains offensive words.');</script>";
        exit;
      }
    }

    // If the comment exceeds the max character limit, trim it
    if (strlen($filteredComment) > $maxLetters) {
      $filteredComment = substr($filteredComment, 0, $maxLetters);
    }
  }

  // Store the evaluation data
  storeEvaluation($teacher_id, $ratings, $comments, $schoolyear_id);
}

function checkIfEvaluated($studentId, $teacherId)
{
  global $conn; // Ensure $conn is accessible here
  $query = "SELECT COUNT(*) FROM tblevaluate WHERE student_id = ? AND teacher_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $studentId, $teacherId);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  return $count > 0;  // Returns true if evaluated, false otherwise
}

function allTeachersEvaluated($studentId, $schoolyear_id)
{
  global $conn;

  // Correct SQL to check if ALL assigned teachers have been evaluated
  $sql = "SELECT COUNT(*) FROM tblteacher t
            WHERE (EXISTS (
                SELECT 1
                FROM tblstudent_section ss
                INNER JOIN tblsection_teacher_subject sts ON ss.section_id = sts.section_id
                WHERE ss.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
                AND sts.teacher_id = t.teacher_id
            ) OR EXISTS (
                SELECT 1
                FROM tblstudent_teacher_subject stts
                WHERE stts.student_id = (SELECT student_id FROM tblstudent WHERE school_id = ?)
                AND stts.teacher_id = t.teacher_id
            )) AND NOT EXISTS (
                SELECT 1
                FROM tblevaluate e
                WHERE e.teacher_id = t.teacher_id
                  AND e.student_id = ?
                  AND e.schoolyear_id = ?
            )";


  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iiii", $_SESSION['school_id'], $_SESSION['school_id'], $studentId, $schoolyear_id);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();

  return $count == 0; // All teachers are evaluated if count is 0
}
$all_evaluated = allTeachersEvaluated($_SESSION['student_id'], $schoolyear_id);

if ($all_evaluated) {

  $student_id = $_SESSION['school_id'];

  if (isset($_SESSION['name'])) {
    $student_name = $_SESSION['name']; // Retrieve student name from session
  } else {
    $student_name = 'Unknown'; // Fallback if name is not set
  }
  echo "<div id='allEvaluatedModal' class='fixed inset-0 bg-gray-500 bg-opacity-30 flex justify-center items-center z-50'>
          <div class='bg-green-500 p-9 rounded-lg shadow-lg w-1/3 absolute top-72 left-1/3'>
              <h2 class='text-center text-2xl text-white mb-4'>All teachers have been evaluated. Thank you!</h2>
              <p class='text-center text-white mb-4'>Student ID: $student_id</p>
                <p class='text-center text-white mb-4'>Student Name: $student_name</p>
              <div class='flex justify-center space-x-4'>
                  <button id='continueBtn' class='bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600'>Continue Evaluating</button>
                  <a href='../student/student_dashboard.php' class='bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600'>Go to Dashboard</a>
              </div>
          </div>
      </div>
      <script>
          const modal = document.getElementById('allEvaluatedModal');
          const continueBtn = document.getElementById('continueBtn');

          modal.style.display = 'block';

          continueBtn.addEventListener('click', () => {
              modal.style.display = 'none';
          });
      </script>";
}


// Example: If all teachers have been evaluated, show a message



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Assigned Teachers</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    /* Toast style */
    .toast {
      background-color: #333;
      color: white;
      padding: 12px 20px;
      border-radius: 5px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      font-size: 16px;
      animation: fadeIn 0.5s, fadeOut 0.5s 3s forwards;

    }

    /* Keyframes for fade-in and fade-out effect */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateX(20px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }
  </style>
</head>

<body>
  <div id="toas-container"></div>


  <div class="grid grid-cols-1 p-5 gap-6">
    <div class="border rounded-md p-5">
      <div>
        <h1 class="text-center text-4xl text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> -
          <span class="text-blue-500"><? //php echo htmlspecialchars($_SESSION['school_id']); 
                                      ?></span>
        </h1>
        <h2 class="text-center text-2xl text-slate-900 mt-2">Your Assigned Teachers and Subjects</h2>
      </div>
    </div>
    <div class="border p-4">
      <div class="m-2 p-2">
        <?php if (!empty($teachers)): ?>
          <div class="grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-6 mt-6 h-20">
            <?php foreach ($teachers as $teacher): ?>

              <?php
              // Check if the teacher has already been evaluated by the student
              $evaluated = checkIfEvaluated($_SESSION['student_id'], $teacher['teacher_id']);
              $bgColor = $evaluated ? 'border-2 border-green-500' : 'bg-gray-100'; // Set background color based on evaluation status
              ?>
              <div class="bg-gray-100 p-4 rounded-md w-full cursor-pointer <?php echo $bgColor ?>" onclick="selectTeacher(<?php echo $teacher['teacher_id']; ?>, '<?php echo addslashes($teacher['teacher_name']); ?>', '<?php echo addslashes($teacher['subject_name']) ?>',  )">
                <div class="text-gray-800 flex flex-col justify-center items-center w-full gap-1">
                  <img src="../upload/pics/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Teacher Profile" class="w-16 h-16 rounded-full">
                  <p class="text-sm"><?php echo htmlspecialchars($teacher['teacher_name']); ?></p>
                  <p class="text-xs"><?php echo htmlspecialchars($teacher['subject_name']); ?></p>
                </div>

              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-center text-gray-600 mt-4">You have no assigned teachers yet.</p>
        <?php endif; ?>
      </div>
      <div class="mt-16 p-1">
        <div class="m-2">
          <h1 class="text-sm">Evaluating Teacher: <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></h1>
          <img src="../upload/pics/<?php echo htmlspecialchars($currentTeacher['image']); ?>" alt="Teacher Profile" class="w-16 h-16 rounded-full m-3">
          <p class="text-sm"><span>Teacher Name:</span> <?php echo htmlspecialchars($currentTeacher['teacher_name']); ?></p>
          <p class="text-sm"><span>Subject:</span> <?php echo htmlspecialchars($currentTeacher['subject_name']); ?></p>
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
                      <span class="text-sm">
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
    </div>
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


    function showToast(message) {
      const toastContainer = document.getElementById('toast-container');
      // Create toast element
      const toast = document.createElement('div');
      toast.classList.add('toast');
      toast.innerText = message;

      // Append to container
      toastContainer.appendChild(toast);

      // Remove toast after animation ends (4s total)
      setTimeout(() => {
        toast.remove();
      }, 1500);
    }
  </script>
</body>

</html>