<?php
$dbhost = "localhost";
$dbname = "имя базы";
$dbuser = "пользоваель базы данных";
$dbpass = "пароль пользователя БД";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// create
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// check
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


?>
