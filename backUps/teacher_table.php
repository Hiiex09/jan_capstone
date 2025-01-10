<?php
include('../database/models/dbconnect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <?php
  include('../admin/components/header.php');
  include('../admin/components/sidebar.php');
  include('../admin/components/teacher_table.php');
  ?>

  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>