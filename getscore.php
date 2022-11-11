<?php
require_once __DIR__.'/inc/boot.php';

$id = $_POST["id"];

if(isset($_POST['last_update'])){
    $last_update = $_POST['last_update'];
}else{
    $last_update = "";
}

$codeVer = '1';

// формируем запрос к БД
$stmt = pdo()->prepare("SELECT last_update, json FROM games WHERE id = ?");
$stmt->execute([$_POST['id']]);

// Разбираем полученные данные
foreach ($stmt as $row) {
    $json=$row["json"];
    $timestamp=$row["last_update"];
  }

$data = json_decode($json, true);

// Проверяем версию выгрузки с ДАРТС базы
if (array_key_exists('codeVer', $data)) {
    $codeVer = $data['codeVer'];
} else {
	$codeVer = "1";	
}

// Проверяем на наличие новых данных в базе
if ($last_update == $timestamp){
	echo "no data";
}
else {

	if ($codeVer >= 2) {
	
		//Собираем название игры
		if ($data['gameData']['bestOf'] > 0) {
			$gameTo = 'Лучший из '.$data['gameData']['bestOf'].' легов';
			$viewSets = false;
			}
		elseif ($data['gameData']['firstToSets'] > '1') {
			$gameTo = 'До '.$data['gameData']['firstToSets'].' сетов из '.$data['gameData']['firstToLegs'].'';
			$viewSets = true;
			}
		else {
			$gameTo = 'До '.$data['gameData']['firstToLegs'].' побед';
			$viewSets = false;
			}
		
		if (($data['gameData']['is1vs1Play']) == "1" ) 
			{
			$players1 = $data['player1']['name1'];
			$players2 = $data['player2']['name1'];
			$gameType = ' 1 vs 1 ';
			}
		if (($data['gameData']['is2vs2Play']) == "1" ) 
			{
			$players1 = ''.$data['player1']['name1'].'<br>'.$data['player1']['name2'].'';
			$players2 = ''.$data['player2']['name1'].'<br>'.$data['player2']['name2'].'';
			$gameType = ' 2 vs 2 ';
			}
		if (($data['gameData']['is3vs3Play']) == "1" ) 
			{
			$players1 = $data['player1']['name1'].'<br>'.$data['player1']['name2'].'<br>'.$data['player1']['name3'];
			$players2 = $data['player2']['name1'].'<br>'.$data['player2']['name2'].'<br>'.$data['player2']['name3'];
			$gameType = ' 3 vs 3 ';
			}
		// Обратавыем попытки закрытия. 
		if(isset($data['player1']['doublesAttempt']) && $data['player1']['doublesSuccess'] > 0){
			$player1DoublesPercent = round(($data['player1']['doublesSuccess']/$data['player1']['doublesAttempt'])*100);
		}else{
			$player1DoublesPercent = 0;
		}
		
		if(isset($data['player2']['doublesAttempt']) && $data['player2']['doublesSuccess'] > 0){
			$player2DoublesPercent = round(($data['player2']['doublesSuccess']/$data['player2']['doublesAttempt'])*100);
		}else{
			$player2DoublesPercent = 0;
		}
	
		// собираем json для передачи в js
		$arr = [
			'tournamentName' => $data['gameData']['tournamentName'],
			'stage' => $data['gameData']['stage'],
			'gameType' => $gameType,
			'gameName' => $gameTo,
			'legBegin' => $data['gameData']['beginLeg'],
			'throwCurrent' => $data['gameData']['currentThrow'], 
			'doublesCount' => $data['gameData']['doublesCount'],
			'player1Name' => $players1,
			'player1Require' => $data['player1']["require"],
			'player1Score' => $data['player1']['score'],
			'player1Avg' => $data['player1']['avg'],
			'player1Legs' => $data['player1']['legs'],
			'player1Sets' => $data['player1']['sets'],
			'player1Score180' => $data['player1']['score180Count'],
			'player1Score140' => $data['player1']['score140Count'],
			'player1Score100' => $data['player1']['score100Count'],
			'player1First9Avg' => $data['player1']['avg'],
			'player1DoublesPercent' => $player1DoublesPercent,
			'player1AllCheck' => $data['player1']['allCheck'],
			'player1AllDartsLegs' => $data['player1']['allDartsLegs'],
			'player1DartsThrown' => $data['player1']['dartsThrown'],
			'player1HowToCheck' => $data['player1']['howToCheck'],
			'player2Name' => $players2,
			'player2Require' => $data['player2']["require"],
			'player2Score' => $data['player2']['score'],
			'player2Avg' => $data['player2']['avg'],
			'player2Legs' => $data['player2']['legs'],
			'player2Sets' => $data['player2']['sets'],
			'player2Score180' => $data['player2']['score180Count'],
			'player2Score140' => $data['player2']['score140Count'],
			'player2Score100' => $data['player2']['score100Count'],
			'player2First9Avg' => $data['player2']['avg'],
			'player2DoublesPercent' => $player2DoublesPercent,
			'player2AllCheck' => $data['player2']['allCheck'],
			'player2AllDartsLegs' => $data['player2']['allDartsLegs'],
			'player2DartsThrown' => $data['player2']['dartsThrown'],
			'player2HowToCheck' => $data['player2']['howToCheck'],
			'last_update' => $timestamp,
		];
			
		echo json_encode($arr);
		
		
		
	} else {

	

		$Is1vs1Play = ($data["Is1vs1Play"]);
		$Is2vs2Play = ($data["Is2vs2Play"]);
		$Is3vs3Play = ($data["Is3vs3Play"]);
		$FirstToSets = ($data["FirstToSets"]);
		$FirstToLegs = ($data["FirstToLegs"]);
		$BestOf = ($data["BestOf"]);
		$GUID = ($data["GUID"]);
		$Player11 = ($data["Player11"]);
		$Player12 = ($data["Player12"]);
		$Player13 = ($data["Player13"]);
		$DoublesAttempt1 = ($data["DoublesAttempt1"]);
		$DoublesSuccess1 = ($data["DoublesSuccess1"]);
		$Player21 = ($data["Player21"]);
		$Player22 = ($data["Player22"]);
		$Player23 = ($data["Player23"]);
		$DoublesAttempt2 = ($data["DoublesAttempt2"]);
		$DoublesSuccess2 = ($data["DoublesSuccess2"]);
			
		// Игроки и тип игры для отображения
			
		if (($data["Is1vs1Play"]) == "1" )
		    {
			$Players1 = $Player11;
			$Players2 = $Player21;
			$gametype = ' 1 vs 1 ';
		    }
		if (($data["Is2vs2Play"]) == "1" )
		    {
			$Players1 = ''.$Player11.'<br>'.$Player12.'';
			$Players2 = ''.$Player21.'<br>'.$Player22.'';
			$gametype = ' 2 vs 2 ';
		    }
		if (($data["Is3vs3Play"]) == "1" )
		    {
			$Players1 = $Player11.'<br>'.$Player12.'<br>'.$Player13;
			$Players2 = $Player21.'<br>'.$Player22.'<br>'.$Player23;
			$gametype = ' 3 vs 3 ';
		    }
		
		
		//Собираем название игры
		if ($BestOf > '0') {
		    $gameto = 'Лучший из '.$BestOf.' легов';
		    $ViewSets = false;
		    }
		elseif ($FirstToSets > '1') {
		    $gameto = 'До '.$FirstToSets.' сетов из '.$FirstToLegs.'';
		    }
		else {
		    $gameto = 'До '.$FirstToLegs.' побед';
		    $ViewSets = false;
		    }
		
		// Обратавыем попытки закрытия. 
		if(isset($DoublesAttempt1) && $DoublesSuccess2 > 0){
		    $Player1DoublesPercent = round(($DoublesSuccess1/$DoublesAttempt1)*100);
		}else{
			$Player1DoublesPercent = 0;
		}
		
		if(isset($DoublesAttempt2) && $DoublesSuccess2 > 0){
		    $Player2DoublesPercent = round(($DoublesSuccess2/$DoublesAttempt2)*100);
		}else{
			$Player2DoublesPercent = 0;
		}
		
				
		$arr = [
			'tournamentName' => ($data["TournamentName"]),
			'stage' => ($data["stage"]),
			'gameType' => $gametype,
			'gameName' => $gameto,
			'legBegin' => ($data["BeginLeg"]),
			'throwCurrent' => ($data["CurrentThrow"]), 
			'doublesCount' => ($data["DoublesCount"]),
			'player1Name' => $Players1,
			'player1Require' => ($data["Require1"]),
			'player1Score' => ($data["Score1"]),
			'player1Avg' => ($data["avg1"]),
			'player1Legs' => ($data["legs1"]),
			'player1Sets' => ($data["sets1"]),
			'player1Score180' => ($data["Player1Score180"]),
			'player1Score140' => ($data["Player1Score140"]),
			'player1Score100' => ($data["Player1Score100"]),
			'player1First9Avg' => ($data["First9Avg1"]),
			'player1DoublesPercent' => $Player1DoublesPercent,
			'player1AllCheck' => ($data["AllCheck1"]),
			'player1AllDartsLegs' => ($data["AllDartsLegs1"]),
			'player1DartsThrown' => ($data["DartsThrown1"]),
			'player1HowToCheck' => ($data["HowToCheck1"]),
			'player2Name' => $Players2,
			'player2Require' => ($data["Require2"]),
			'player2Score' => ($data["Score2"]),
			'player2Avg' => ($data["avg2"]),
			'player2Legs' => ($data["legs2"]),
			'player2Sets' => ($data["sets2"]),
			'player2Score180' => ($data["Player2Score180"]),
			'player2Score140' => ($data["Player2Score140"]),
			'player2Score100' => ($data["Player2Score100"]),
			'player2First9Avg' => ($data["First9Avg2"]),
			'player2DoublesPercent' => $Player2DoublesPercent,
			'player2AllCheck' => ($data["AllCheck2"]),
			'player2AllDartsLegs' => ($data["AllDartsLegs2"]),
			'player2DartsThrown' => ($data["DartsThrown2"]),
			'player2HowToCheck' => ($data["HowToCheck2"]),
			'last_update' => $timestamp,
		];
// отдаем сформированный json
echo json_encode($arr);

	}
}
?>