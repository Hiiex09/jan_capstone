<?php
include('./database/models/dbconnect.php');
include('./admin/secure.php');
session_start();

if (!isset($_SESSION['username'])) {
  header('location: login.php');
  exit();
}
