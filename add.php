<?php
header("Content-Type: application/json");
require_once __DIR__.'/inc/boot.php';
// include 'inc/db.php';

$json = file_get_contents("php://input");

// разбираем JSON-строку на составляющие встроенной командой
$data = json_decode($json,true);

// Узнаем версию кода экспорта из ДАРТС базы.
if (array_key_exists('codeVer', $data)) {
    $codeVer = $data['codeVer'];
} else {
	$codeVer = "1";	
}




if ($codeVer >= 2) { // Если версия код 2, то действуем по сценарию.

	$gameType = 'x01';
	 
	if ($codeVer = 3) { // если вервия кода 3, то проверяем тип игры, остальное, как в версии 2
		if (($data['gameData']['gameType']) == "Cricket") {
			$gameType = 'Крикет';
			if (($data['gameData']['cricketWithScores']) == true)
				$gameType = $gameType.' с набором очков';
			else 
				$gameType = $gameType;
		}
		else $gameType = $data['gameData']['gameType'];
	}


    // Формируем игровой состав
	
	if (($data['gameData']['is1vs1Play']) == "1" ) 
	    {
		$players1 = $data['player1']['name1'];
		$players2 = $data['player2']['name1'];
	    }
	if (($data['gameData']['is2vs2Play']) == "1" ) 
	    {
		$players1 = ''.$data['player1']['name1'].'<br>'.$data['player1']['name2'].'';
		$players2 = ''.$data['player2']['name1'].'<br>'.$data['player2']['name2'].'';
		
	    }
	if (($data['gameData']['is3vs3Play']) == "1" ) 
	    {
		$players1 = $data['player1']['name1'].'<br>'.$data['player1']['name2'].'<br>'.$data['player1']['name3'];
		$players2 = $data['player2']['name1'].'<br>'.$data['player2']['name2'].'<br>'.$data['player2']['name3'];
		
	    }
	if ($players2 == "") 
	    {
		$players2 = 'Incognito';
	    }
		
	$guid = $data['gameData']['GUID'];
	$sets1 = $data['player1']['sets'];		
	$legs1 = $data['player1']['legs'];
	$sets2 = $data['player2']['sets'];
	$legs2 = $data['player2']['legs'];
	$tag = $data['gameData']['tag'];
	$curtime = date("Y-m-d H:i:s");
	$endGame = $data['gameData']['gameEnd'];

// Формируем запрос к БД
$stmt = pdo()->prepare("INSERT INTO games (guid, game_type, gamer1_name, sets1, legs1, gamer2_name, sets2, legs2, json, last_update, tag, code_version) VALUES ('$guid', '$gameType', '$players1', '$sets1', '$legs1', '$players2', '$sets2', '$legs2', '$json', '$curtime' , '$tag', '$codeVer') ON DUPLICATE KEY UPDATE game_type = '$gameType', gamer1_name = '$players1', sets1 = '$sets1', legs1 = '$legs1', gamer2_name= '$players2', sets2 = '$sets2', legs2 = '$legs2', json = '$json', end_match = '$endGame';");
// $stmt = pdo()->prepare("INSERT INTO games (guid, gamer1_name, sets1, legs1, gamer2_name, sets2, legs2, json, last_update, tag, code_version) VALUES (:guid, :players1, :sets1, :legs1, :players2, :sets2, :legs2, :json, :curtime, :tag, :codeVer) ON DUPLICATE KEY UPDATE gamer1_name = :players1, sets1 = :sets1, legs1 = :legs1, gamer2_name= :players2, sets2 = :sets2, legs2 = :legs2, json = :json, end_match = :endGame ;");
	// $sql = "INSERT INTO games (guid, gamer1_name, sets1, legs1, gamer2_name, sets2, legs2, json, last_update, tag, code_version) VALUES ('$guid', '$players1', '$sets1', '$legs1', '$players2', '$sets2', '$legs2', '$json', '$curtime' , '$tag', '$codeVer') ON DUPLICATE KEY UPDATE gamer1_name = '$players1', sets1 = '$sets1', legs1 = '$legs1', gamer2_name= '$players2', sets2 = '$sets2', legs2 = '$legs2', json = '$json', end_match = '$endGame';";

} else { // Если версия ниже 2, то используем старый вариант импорта

// Формируем игровой состав

$Player11 = ($data['Player11']);
$Player12 = ($data['Player12']);
$Player13 = ($data['Player13']);
$Player21 = ($data['Player21']);
$Player22 = ($data['Player22']);
$Player23 = ($data['Player23']);

if (($data['Is1vs1Play']) == "1" ) 
    {
	$Players1 = $Player11;
	$Players2 = $Player21;
    }
if (($data['Is2vs2Play']) == "1" ) 
    {
	$Players1 = ''.$Player11.'<br>'.$Player12.'';
	$Players2 = ''.$Player21.'<br>'.$Player22.'';
	
    }
if (($data['Is3vs3Play']) == "1" ) 
    {
	$Players1 = $Player11.'<br>'.$Player12.'<br>'.$Player13;
	$Players2 = $Player21.'<br>'.$Player22.'<br>'.$Player23;
	
    }
if ($Players2 == "") 
    {
	$Players2 = 'Incognito';
    }



$guid = ($data['GUID']);
$sets1 = ($data['sets1']);
$legs1 = ($data['legs1']);
$sets2 = ($data['sets2']);
$legs2 = ($data['legs2']);
$curtime = date("Y-m-d H:i:s");

$stmt = pdo()->prepare("INSERT INTO games (guid, gamer1_name, sets1, legs1, gamer2_name, sets2, legs2, json, last_update) VALUES ('$guid', '$Players1', '$sets1', '$legs1', '$Players2', '$sets2', '$legs2', '$json' , '$curtime') ON DUPLICATE KEY UPDATE gamer1_name = '$Players1', sets1 = '$sets1', legs1 = '$legs1', gamer2_name = '$Players2', sets2 = '$sets2', legs2 = '$legs2', json = '$json'");
// $sql = "INSERT INTO games (guid, gamer1_name, sets1, legs1, gamer2_name, sets2, legs2, json, last_update) VALUES ('$guid', '$Players1', '$sets1', '$legs1', '$Players2', '$sets2', '$legs2', '$json' , '$curtime') ON DUPLICATE KEY UPDATE gamer1_name = '$Players1', sets1 = '$sets1', legs1 = '$legs1', gamer2_name = '$Players2', sets2 = '$sets2', legs2 = '$legs2', json = '$json';";
}

// отправляем в ответ строку с подтверждением
if ($guid == ""){
    echo "Connect OK (No GUID)";
}
else { 
	$stmt->execute();
    // mysqli_query($conn, $sql);
}

// mysqli_close($conn);

?>