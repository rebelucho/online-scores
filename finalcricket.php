<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';

$id = $_GET["id"];

if(isset($_GET['view'])){
  $view = $_GET['view'];
}else{
  $view = "full";
}

$player1AvgArray = array();
$player2AvgArray = array();
$legCounter = array();

// формируем запрос к БД

$stmt = pdo()->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$_GET['id']]);

// разбираем полученные данные

foreach ($stmt as $row) {
    $json=$row["json"];
    $player1Name=$row['gamer1_name'];
    $player2Name=$row['gamer2_name'];
    $gameTypeName=$row["game_type_name"];
  }

$data = json_decode($json, true);

// Проверяем версию выгрузки с ДАРТС базы
if (array_key_exists('codeVer', $data)) {
    $codeVer = $data['codeVer'];
} else {
	$codeVer = "1";	
}