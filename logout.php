
<?php
include('./database/models/dbconnect.php');
session_start();
session_unset();
if (session_destroy()) {
  header("Location: index.php");
}
exit();
?>
