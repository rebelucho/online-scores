<?php
$dbhost = "localhost";
$dbname = "��� ����";
$dbuser = "����������� ���� ������";
$dbpass = "������";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// create
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// check
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


?>