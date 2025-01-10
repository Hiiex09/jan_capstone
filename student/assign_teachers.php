<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Assigned Teachers</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-5 px-4">

  <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-center text-2xl text-gray-800">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> - <?php echo htmlspecialchars($_SESSION['school_id']); ?></h1>
    <h2 class="text-center text-xl text-gray-700 mt-2">Your Assigned Teachers and Subjects</h2>

    <?php if (!empty($teachers)): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <?php foreach ($teachers as $teacher): ?>
          <div class="bg-gray-100 p-4 rounded-lg shadow-md flex items-center hover:scale-105 transform transition-all cursor-pointer" onclick="selectTeacher(<?php echo $teacher['teacher_id']; ?>, '<?php echo addslashes($teacher['teacher_name']); ?>', '<?php echo addslashes($teacher['subject_name']); ?>')">
            <img src="../pic/pics/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Teacher Profile" class="w-20 h-20 rounded-full mr-4">
            <div class="text-gray-800">
              <p class="font-semibold"><?php echo htmlspecialchars($teacher['teacher_name']); ?></p>
              <p class="text-gray-600"><?php echo htmlspecialchars($teacher['subject_name']); ?></p>
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

    <img src="../pic/pics/<?php echo htmlspecialchars($currentTeacher['image']); ?>" alt="Teacher Profile" class="w-24 h-24 rounded-full mx-auto mt-4">
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
                  <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($listCriteria['criteria']); ?></span>
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