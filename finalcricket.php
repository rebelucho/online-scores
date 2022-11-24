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

if ($codeVer < 3) {
    echo '<div class="h1 text-center">Тут не до крикета</div>';
} else {

    // Название игры
    $gameType = $data['gameData']['gameType'];
    $gameTypeName = 'x01';
    if (($data['gameData']['gameType']) == "Cricket") {
        $gameTypeName = 'Крикет';
        if (($data['gameData']['cricketWithScores']) == true)
            $gameTypeName = $gameTypeName.' с набором очков';
        else 
            $gameTypeName = $gameTypeName;
    }
    else $gameTypeName = $data['gameData']['gameType'];

    // echo '<div class="h1 text-center">'.$gameTypeName.'</div>';
    
    // Счет
    if ($data['gameData']['firstToSets'] > 1) $gameResult = $data['player1']['sets'].':'.$data['player2']['sets'];
    else $gameResult = $data['player1']['legs'].':'.$data['player2']['legs'];
?>

<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col">
            <div class="h2 text-center">Статистика</div>
        </div>
    </div>
    <div class="row justify-content-center align-items-center text-center border-top " style="height: 50px;">
        <div class="col-5 col-lg-4 border-bottom h4 text-truncate text-uppercase h-100 d-flex align-items-center justify-content-end"><?php echo $player1Name;?></div>
        <div class="col-2 col-lg-1 border-bottom h1 h-100 d-flex align-items-center justify-content-center"><?php echo $gameResult;?></div>
        <div class="col-5 col-lg-4 border-bottom h4 text-truncate text-uppercase h-100 d-flex align-items-center justify-content-start"><?php echo $player2Name;?></div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 50px;">
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-end"><?php echo $data['player1']['avg']?></div>
        <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">AVG</div>
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-start"><?php echo $data['player2']['avg']?></div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 50px;">
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-end"><?php echo implode(", ", $data['player1']['allDarts']); ?></div>
        <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">Darts</div>
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-start"><?php echo implode(", ", $data['player2']['allDarts']);?></div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 50px;">
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-end"><?php echo implode(", ", $data['player1']['allScores']); ?></div>
        <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">Scores</div>
        <div class="col-5 col-lg-2 h-100 d-flex align-items-center justify-content-start"><?php echo implode(", ", $data['player2']['allScores']);?></div>
    </div>
</div>
<?php

$setCount = count($data['stat']['player1']['sets']);

for ($set_i=1; $set_i <= $setCount; $set_i++) {
    $legCount = count($data['stat']['player1']['sets'][$set_i-1]['legs']);
    
    for ($i = 1; $i <= $legCount; $i++) {
        ${"player1LegBegin_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['begin'];
  	    ${"player1LegDarts_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['darts'];
        ${"player1LegS20_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s20'];
        ${"player1LegS19_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s19'];
        ${"player1LegS18_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s18'];
        ${"player1LegS17_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s17'];
        ${"player1LegS16_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s16'];
        ${"player1LegS15_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['s15'];
        ${"player1LegSBull_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['sBull'];
        ${"player2LegBegin_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['begin'];
  	    ${"player2LegDarts_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['darts'];
        ${"player2LegS20_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s20'];
        ${"player2LegS19_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s19'];
        ${"player2LegS18_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s18'];
        ${"player2LegS17_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s17'];
        ${"player2LegS16_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s16'];
        ${"player2LegS15_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['s15'];
        ${"player2LegSBull_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['sBull'];
        ?>
        <div class="container">   
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-3 col-lg-1"><?php echo ${"player1LegBegin_$i"};?></div>
                <div class="col-2 h3 text-center">
                    <?php if ($setCount > 1){ echo 'Set '.$set_i.' | Leg '. $i;} else { echo 'Leg '. $i;}?>
                </div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegBegin_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS20_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end d-flex align-items-center  justify-content-center" >20</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS20_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS19_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">19</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS19_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS18_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center  justify-content-center">18</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS18_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS17_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">17</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS17_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS16_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">16</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS16_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegS15_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">15</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegS15_$i"};?></div>
            </div>
            <div class="row justify-content-center align-items-center text-center" >
                <div class="col-3 col-lg-1"><?php echo ${"player1LegSBull_$i"};?></div>
                <div class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center" >Bull</div>
                <div class="col-3 col-lg-1"><?php echo ${"player2LegSBull_$i"};?></div>
            </div>
        </div>
<?php
    
    }
}
}