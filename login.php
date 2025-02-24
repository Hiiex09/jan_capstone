<?php
session_start();
include('./database/models/dbconnect.php'); // Include database connection
include('./admin/security/admin_login.php'); // Admin login function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Log to admin
  adminLogin($username, $password);

  // Secure SQL query to fetch student info
  $sql = "SELECT student_id, school_id, name FROM tblstudent WHERE school_id = ? AND password = ?";

  // Prepare and bind
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();

    $_SESSION['student_id'] = $student['student_id']; // Store student_id in session
    $_SESSION['school_id'] = $student['school_id'];   // Store school_id in session
    $_SESSION['name'] = $student['name'];             // Store name in session

    // Redirect to student dashboard
    header("Location: ./student/student_dashboard.php");
    exit;
  } else {
    $error = "Invalid username or password.";
  }

  // Close statement
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
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.2/daisyui.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>


<body>
  <div class="navbar bg-blue-900 border">
    <div class="text-2xl text-white" style="font-family: Outfit;">Cebu Eastern College</div>
  </div>

  <div class="hero-content grid sm:grid-cols-1 md:grid-cols-2 w-full p-5 rounded-md place-items-center">
    <div class="card bg-base-100 w-full shrink-0 border p-20 shadow-lg shadow-base-300">
      <div class="flex flex-col justify-center items-center">
        <div class="text-center text-2xl p-5">
          <h1 class="text-3xl" style="font-family: Outfit;">Student Evaluation System</h1>
        </div>
        <div class="mt-5">
          <img
            src="../Capstone/admin/tools/Images/CEC.png"
            alt="cec_logo"
            class="h-40 w-40 rounded-full">
        </div>
      </div>
      <form
        action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>"
        method="post"
        class="card-body">
        <div class="form-control">
          <label class="floating-label">
            <span>Username</span>
            <input
              type="text"
              placeholder="Username"
              class="input input-lg w-full shadow-lg"
              required
              autocomplete="off"
              name="username"
              style="font-family: Outfit;" />
          </label>
        </div>
        <div class="form-control">

          <label class="floating-label">
            <span>Password</span>
            <input
              type="password"
              placeholder="Password"
              class="input input-lg w-full shadow-lg"
              required
              autocomplete="off"
              name="password"
              style="font-family: Outfit;" />
          </label>
        </div>
        <div class="form-control mt-6">
          <button
            type="submit"
            class="btn bg-blue-900 btn-outline text-white text-lg w-full"
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
    <div class="h-full ml-5">
      <img
        src="../Capstone/admin/tools/Images/front_page.png"
        alt="cebu_eastern_college"
        class=" h-full object-cover animate-pulse" />
    </div>
  </div>
  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>