<?php
header("Content-Type: application/json");

include 'inc/db.php';

$json = file_get_contents("php://input");

// разбираем JSON-строку на составляющие встроенной командой
$data = json_decode($json,true);

// Формируем игровой состав

$Player11 = ($data["Player11"]);
$Player12 = ($data["Player12"]);
$Player13 = ($data["Player13"]);
$Player21 = ($data["Player21"]);
$Player22 = ($data["Player22"]);
$Player23 = ($data["Player23"]);

if (($data["Is1vs1Play"]) == "1" ) 
    {
	$Players1 = $Player11;
	$Players2 = $Player21;
    }
if (($data["Is2vs2Play"]) == "1" ) 
    {
	$Players1 = ''.$Player11.'<br>'.$Player12.'';
	$Players2 = ''.$Player21.'<br>'.$Player22.'';
	
    }
if (($data["Is3vs3Play"]) == "1" ) 
    {
	$Players1 = $Player11.'<br>'.$Player12.'<br>'.$Player13;
	$Players2 = $Player21.'<br>'.$Player22.'<br>'.$Player23;
	
    }
if ($Players2 == "") 
    {
	$Players2 = 'Incognito';
    }



$guid = ($data["GUID"]);
$legs1 = ($data["legs1"]);
$legs2 = ($data["legs2"]);

$sql = "INSERT INTO games (guid, gamer1_name, legs1, gamer2_name, legs2, json) VALUES ('$guid', '$Players1', '$legs1', '$Players2', '$legs2', '$json') ON DUPLICATE KEY UPDATE gamer1_name = '$Players1', legs1 = $legs1, gamer2_name = '$Players2', legs2 = $legs2, json = '$json';";

// отправляем в ответ строку с подтверждением
if ($guid == ""){
    echo "Connect OK (No GUID)";
}
else { 
    mysqli_query($conn, $sql);
}

mysqli_close($conn);

?>