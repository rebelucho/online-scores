<?php
include 'inc/db.php';

$id = $_POST["id"];

if(isset($_POST['last_update'])){
    $last_update = $_POST['last_update'];
}else{
    $last_update = "";
}


// формируем запрос к БД

$sql = "SELECT json, last_update FROM games WHERE id='$id'";
$result = mysqli_query($conn, $sql);

// Разбираем запрос
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    $json=$row["json"];
    $timestamp=$row["last_update"];
  }
} else {
  echo "0 results";
}

mysqli_close($conn);

$data = json_decode($json, true);


if ($last_update == $timestamp){
	echo "no data";
}
else {

// $stage = ;
// $TournamentName = ;
$Is1vs1Play = ($data["Is1vs1Play"]);
$Is2vs2Play = ($data["Is2vs2Play"]);
$Is3vs3Play = ($data["Is3vs3Play"]);
// $CurrentThrow = ($data["CurrentThrow"]);
// $BeginLeg = ($data["BeginLeg"]);
$FirstToSets = ($data["FirstToSets"]);
$FirstToLegs = ($data["FirstToLegs"]);
$BestOf = ($data["BestOf"]);
$GUID = ($data["GUID"]);
// $DoublesCount = ($data["DoublesCount"]);
$Player11 = ($data["Player11"]);
$Player12 = ($data["Player12"]);
$Player13 = ($data["Player13"]);
// $Require1 = ($data["Require1"]);
// $Score1 = ($data["Score1"]);
// $avg1 = ($data["avg1"]);
// $legs1 = ($data["legs1"]);
// $sets1 = ($data["sets1"]);
// $Player1Score180 = ($data["Player1Score180"]);
// $Player1Score140 = ($data["Player1Score140"]);
// $Player1Score100 = ($data["Player1Score100"]);
// $First9Avg1 = ($data["First9Avg1"]);
$DoublesAttempt1 = ($data["DoublesAttempt1"]);
$DoublesSuccess1 = ($data["DoublesSuccess1"]);
// $AllCheck1 = ($data["AllCheck1"]);
// $AllDartsLegs1 = ($data["AllDartsLegs1"]);
// $DartsThrown1 = ($data["DartsThrown1"]);
// $HowToCheck1 = ($data["HowToCheck1"]);
$Player21 = ($data["Player21"]);
$Player22 = ($data["Player22"]);
$Player23 = ($data["Player23"]);
// $Require2 = ($data["Require2"]);
// $Score2 = ($data["Score2"]);
// $avg2 = ($data["avg2"]);
// $legs2 = ($data["legs2"]);
// $sets2 = ($data["sets2"]);
// $Player2Score140 = ($data["Player2Score140"]);
// $Player2Score100 = ($data["Player2Score100"]);
// $Player2Score180 = ($data["Player2Score180"]);
// $First9Avg2 = ($data["First9Avg2"]);
$DoublesAttempt2 = ($data["DoublesAttempt2"]);
$DoublesSuccess2 = ($data["DoublesSuccess2"]);
// $AllCheck2 = ($data["AllCheck2"]);
// $AllDartsLegs2 = ($data["AllDartsLegs2"]);
// $DartsThrown2 = ($data["DartsThrown2"]);
// $HowToCheck2 = ($data["HowToCheck2"]);


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

// echo json_encode(array("player1Name"=>$Players1, "player1Require"=>$Require1, "player1Score"=>$Score1));

echo json_encode($arr);

// echo $json;

}
?>