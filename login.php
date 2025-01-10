<?php
session_start();
include('./database//models/dbconnect.php'); // Include database connection
include('./admin/security/admin_login.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  //Log to admin
  adminLogin($username, $password);

  // SQL query to fetch student info based on username and password
  $sql = "SELECT school_id, name FROM tblstudent WHERE school_id = ? AND password = ?";

  // Prepare and bind
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if login is successful
  if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $_SESSION['school_id'] = $student['school_id']; // Store school_id in session
    $_SESSION['name'] = $student['name'];
    header("Location: ./student/student_dashboard.php"); // Redirect to the teacher list page
    exit;
  } else {
    $error = "Invalid username or password";
  }

  // Close prepared statement
  $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css?family=Teko:300,regular,500,600,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Outfit:100,200,300,regular,500,600,700,800,900" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>


<body>
  <div class="hero bg-blue-300 min-h-screen">
    <div class="hero-content flex-col lg:flex-row-reverse">
      <div class="text-center lg:text-left mx-4">
        <h1 class="text-5xl font-bold" style="font-family: 'Teko';">Login</h1>
        <p class="py-6 text-3xl text-justify leading-2 font-bold" style="font-family: 'Teko';">
          Welcome to our <span class="text-blue-900 font-semibold">Teacher Evaluation Platform</span>,
          Your feedback is incredibly valuable in helping us enhance the learning experience and recognize the hard work of our educators.
          By sharing your honest thoughts, you contribute to a positive and thriving academic environment.
          Remember, your input remains confidential and plays a vital role in shaping the future of our teaching methods.</br>
        </p>
        <span class="text-3xl mt-3 font-bold" style="font-family: 'Teko';">Thank you for taking the time to make a difference!</span>
      </div>
      <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <form
          action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>"
          method="post"
          class="card-body">
          <div class="form-control">
            <label class="label" style="font-family: 'Outfit';">
              <span class="label-text text-2xl font-semibold">Username</span>
            </label>
            <input
              type="text"
              placeholder="Username"
              class="input input-bordered text-1xl font-semibold"
              required
              name="username"
              autocomplete="off" />
          </div>
          <div class="form-control">
            <label class="label" style="font-family: 'Outfit';">
              <span class="label-text text-2xl font-semibold">Password</span>
            </label>
            <input
              type="password"
              placeholder="************"
              class="input input-bordered"
              required
              name="password"
              autocomplete="off" />
            <!-- <label class="label">
              <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
            </label> -->
          </div>
          <div class="form-control mt-6">
            <button
              type="submit"
              class="btn bg-blue-900 btn-outline text-white text-lg"
              name="submit"
              style="font-family: 'Outfit';">Login</button>
          </div>
          <?php if (isset($error)): ?>
            <div role="alert" class="alert bg-red-900">
              <p class="text-xl text-white text-center h-8"><?php echo $error; ?></p>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>