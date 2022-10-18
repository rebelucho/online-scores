<?php
require_once('template/header.tpl');
include 'inc/db.php';

$id = $_GET["id"];

if(isset($_POST['last_update'])){
    $last_update = $_POST['last_update'];
}else{
    $last_update = "";
}

$player1AvgArray = array();
$player2AvgArray = array();
$legCounter = array();

// формируем запрос к БД

$sql = "SELECT gamer1_name, gamer2_name, json, last_update, end_match FROM games WHERE id='$id'";
$result = mysqli_query($conn, $sql);

// Разбираем запрос
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    $json=$row["json"];
    $player1Name=$row['gamer1_name'];
    $player2Name=$row['gamer2_name'];
    $timestamp=$row["last_update"];
    $endGame=$row["end_match"];
  }
} else {
  echo "0 results";
}

mysqli_close($conn);

$data = json_decode($json, true);

//Собираем название игры
if ($data['gameData']['bestOf'] > '0') {
    $gameName = 'Лучший из '.$data['gameData']['bestOf'].' легов';
    $viewSets = false;
    }
elseif ($data['gameData']['firstToSets'] > '1') {
    $gameName = 'До '.$data['gameData']['firstToSets'].' сетов из '.$data['gameData']['firstToLegs'].'';
    $viewSets = true;
    }
else {
    $gameName = 'До '.$data['gameData']['firstToLegs'].' побед';
    $viewSets = false;
    }

// if ($endGame == true) {
    ?>
    <div class="container">
    <div class="row bg-dark text-white">
        <div class="col-md-12"><strong><?php echo($gameName)?></strong></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 2vmax;"><?php echo($player1Name);?></div>
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 2vmax;"><?php echo($player2Name);?></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-6" style="width: 50%; padding: 0px 0px;text-align: center;font-weight: bold;font-size: 100px;">
            <?php 
            if ($viewSets == true){
                echo($data['player1']['sets']);
            } else {
                echo($data['player1']['legs']);
            }
            ?>
        </div>
        <div class="col-6" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 100px;">
            <?php 
            if ($viewSets == true){
                echo($data['player2']['sets']);
            } else {
                echo($data['player2']['legs']);
            }
            ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row bg-dark text-white">
        <div class="col-md-12"><strong><?php echo($data['gameData']['tournamentName'].'&nbsp;');?><?php echo($data['gameData']['stage']);?></strong></div>
    </div>
</div>

<div class="container" style="font-weight: bold;font-size: 20px;">
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><?php echo($data['player1']['avg']) ?></div>
        <div class="col-4 col-md-2 text-center">Набор</div>
        <div class="col-4 col-md-5 text-start"><?php echo($data['player2']['avg']) ?></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><?php echo($data['player1']['first9Avg']) ?></div>
        <div class="col-4 col-md-2 text-center">Первые 9</div>
        <div class="col-4 col-md-5 text-start"><?php echo($data['player2']['first9Avg']) ?></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><?php echo($data['player1']['score180Count']) ?></div>
        <div class="col-4 col-md-2 text-center">180</div>
        <div class="col-4 col-md-5 text-start"><?php echo($data['player2']['score180Count']) ?></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><?php echo($data['player1']['score140Count']) ?></div>
        <div class="col-4 col-md-2 text-center">140+</div>
        <div class="col-4 col-md-5 text-start"><?php echo($data['player2']['score140Count']) ?></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><?php echo($data['player1']['score100Count']) ?></div>
        <div class="col-4 col-md-2 text-center">100+</div>
        <div class="col-4 col-md-5 text-start"><?php echo($data['player2']['score100Count']) ?></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 d-flex flex-wrap justify-content-end"><?php foreach ($data['player1']['allDartsLegs'] as $key => $value) {
          echo('<span>&nbsp;'.$value.'</span>');
        } ?>
        </div>
        <div class="col-4 col-md-2 text-center">Леги</div>
        <div class="col-4 col-md-5 text-start d-flex flex-wrap"><?php foreach ($data['player2']['allDartsLegs'] as $key => $value) {
          echo('<span>'.$value.'&nbsp;</span>');
        } ?>
        </div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end d-flex flex-wrap justify-content-end"><?php foreach ($data['player1']['allCheck'] as $key => $value) {
          echo('<span>&nbsp;'.$value.'</span>');
        }?>
        </div>
        <div class="col-4 col-md-2 text-center">Окончания</div>
        <div class="col-4 col-md-5 text-start d-flex flex-wrap"><?php foreach ($data['player2']['allCheck'] as $key => $value) {
          echo('<span>'.$value.'&nbsp;</span>');
        }?>
        </div>
    </div>
</div>
<?php
// } 

$setCount = count($data['stat']['player1']['sets']);
?>
  <div class="clearfix container d-flex justify-content-center">
  <div  style="max-width: 700px;">
  <table class="table table-light table-borderless fs-5 fw-bold">
    <thead>
      <tr style="font-size: 15px;">
        <!-- <th colspan="2" class="text-end"><?php echo $player1Name; ?></th> -->
        <th colspan="5" class="text-center">Step by Step Stat</th>
        <!-- <th colspan="2" ><?php echo $player2Name; ?></th>             -->
      </tr>
      <tr>
        <th scope="col" class="text-end ">Набор</th>
        <th scope="col" class="text-end">Остаток</th>
        <th scope="col" class="table-dark text-center">Ход</th>
        <th scope="col">Остаток</th>
        <th scope="col">Набор</th>           
      </tr>
    </thead>
    <tbody>
<?php

for ($set_i=1; $set_i <= $setCount; $set_i++) {
  $legCount = count($data['stat']['player1']['sets'][$set_i-1]['legs']);
  
  for ($i = 1; $i <= $legCount; $i++) {
  	  ${"player1LegRnd_$i"} = count($data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['score']);
      ${"player1LegLeft_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]["require"][array_key_last($data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]["require"])];
      ${"player1LegBegin_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['begin'];
      ${"player1LegScore_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['score'];
      ${"player1LegScoreSum_$i"} = array_sum($data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['score']);
      ${"player1LegRequire_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]["require"];    
      ${"player1LegDarts_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['darts'];
      ${"player1LegAttempts_$i"} = $data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]['attempts'];
      ${"player2LegRnd_$i"} = count($data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['score']);
      ${"player2LegLeft_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]["require"][array_key_last($data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]["require"])];
      ${"player2LegBegin_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['begin'];
      ${"player2LegScore_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['score'];
      ${"player2LegScoreSum_$i"} = array_sum($data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['score']);
      ${"player2LegRequire_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]["require"];    
      ${"player2LegDarts_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['darts'];
      ${"player2LegAttempts_$i"} = $data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]['attempts']; 
  } 
  
  for ($i = 1; $i <= $legCount; $i++) { 
  	
    echo('<tr class="table-dark table-borderless align-middle">');
      echo('<td>');
      if ($setCount > 1){
        echo('<font size="3">Set '.$set_i.'<br>Leg '. $i.'</font>');
      } else {
        echo('<font size="3">Leg '. $i.'</font>');
      }
      echo('</td>');

      echo('<td class="text-end">');
      if(${"player1LegBegin_$i"}){  
        echo('<span class="h1 who-move">'.${"player1LegLeft_$i"}.'</span>');
      } else {
        echo('<span class="h1">'.${"player1LegLeft_$i"}.'</span>');
      }
      echo('</td>');

      echo('<td class="text-center">');
      echo('-');
      echo('</td>');  

      echo('<td>');  
      if(${"player2LegBegin_$i"}){
        echo('<span class="h1 who-move">'.${"player2LegLeft_$i"}.'</span>');
      } else {
        echo('<span class="h1">'.${"player2LegLeft_$i"}).'</span>';  
      }
      echo('</td>'); 

      echo('<td>');
      echo('</td>');
  
    echo('<tr>');
    
    echo('<tr>');	
      echo('<td class="table-light text-end ">');	
      if (${"player1LegLeft_$i"} == 0) {
        $player1ScoreArray= ${"player1LegScore_$i"};
        $replacement = array(${"player1LegRnd_$i"}-1 => "X".array_pop($data['stat']['player1']['sets'][$set_i-1]['legs'][$i-1]["darts"])."");
        $player1ScoreView = array_replace($player1ScoreArray, $replacement);
        foreach ($player1ScoreView as $score) {
          if ($score >= 100) {
            echo('<b><font color="red">'.$score.'</font></b><br>');
          } else {
            echo($score.'<br>');
          }
        }
      } else {
      	foreach (${"player1LegScore_$i"} as $score) {
      		if ($score >= 100) {
            echo('<b><font color="red">'.$score.'</font></b><br>');
          } else {
            echo($score.'<br>');
          }
      	}
        }
      echo('</td>');

      echo('<td class="table-light text-end">');  
        foreach (${"player1LegRequire_$i"} as $require) {
          echo($require.'<br>');
        }
      echo('</td>'); 
  
      echo('<td class="table-dark text-center">'); 
      if ( ${"player1LegRnd_$i"} > ${"player2LegRnd_$i"} ){
        $rnd = ${"player1LegRnd_$i"};
      } else {
        $rnd = ${"player2LegRnd_$i"};      
      }
       for ($ii = 1; $ii <= $rnd; $ii++){
        echo($ii.'<br>');
       }
      echo('</td>');	

      echo('<td class="table-light">');  
       foreach (${"player2LegRequire_$i"} as $require) {
        echo($require.'<br>');
       }
      echo('</td>');    

      echo('<td class="table-light">');
      if (${"player2LegLeft_$i"} == 0) {
        $player2ScoreArray= ${"player2LegScore_$i"};
        $replacement = array(${"player2LegRnd_$i"}-1 => "X".array_pop($data['stat']['player2']['sets'][$set_i-1]['legs'][$i-1]["darts"])."");
        $player2ScoreView = array_replace($player2ScoreArray, $replacement);
        foreach ($player2ScoreView as $score) {
          if ($score >= 100) {
            echo('<b><font color="red">'.$score.'</font></b><br>');
          } else {
            echo($score.'<br>');
          }
        }
      } else {	
  	foreach (${"player2LegScore_$i"} as $score) {
  		if ($score >= 100) {
            echo('<b><font color="red">'.$score.'</font></b><br>');
          } else {
            echo($score.'<br>');
          }
  	}}
      echo('</td>');
  	echo('</tr>');	    

    echo('<tr>');
      echo('<td class="text-end">');
      if (${"player1LegLeft_$i"} == 0) {
        echo('<img src="/img/1dart150.png" width="30" height="30">'.array_sum(${"player1LegDarts_$i"}));
      }
      echo('</td>');

      echo('<td class="text-end">');
      echo(round(${"player1LegScoreSum_$i"}/array_sum(${"player1LegDarts_$i"})*3, 2));
      ${"player1AvgStat_$i"} = round(${"player1LegScoreSum_$i"}/array_sum(${"player1LegDarts_$i"})*3, 2);
      array_push($player1AvgArray, ${"player1AvgStat_$i"});
      array_push($legCounter, $legCounter[array_key_last($legCounter)]+1);
      echo('</td>');

      echo('<td class="table-dark text-center">');
      echo('<img src="/img/3dart150white.png" width="30" height="30">');
      echo('</td>');	

      echo('<td>');
      echo(round(${"player2LegScoreSum_$i"}/array_sum(${"player2LegDarts_$i"})*3, 2));
      ${"player2AvgStat_$i"} = round(${"player2LegScoreSum_$i"}/array_sum(${"player2LegDarts_$i"})*3, 2);
      array_push($player2AvgArray, ${"player2AvgStat_$i"});
      echo('</td>');

      echo('<td>');
      if (${"player2LegLeft_$i"} == 0) {
        echo(array_sum(${"player2LegDarts_$i"}).'<img src="/img/1dart150.png" width="30" height="30">');
      }
      echo('</td>');	
  	echo('</tr>');	
  
  }
}
echo('</table>');
?>
		</tbody>
	</table>
</div>
</div>

  
<?php

$arr = [
  'legCounter' => $legCounter,
  'player1Name' => $player1Name,
  'player2Name' => $player2Name,
  'player1Avg' => $player1AvgArray,
  'player2Avg' => $player2AvgArray
];

$jsonJS = json_encode($arr);

if ($endGame == true) {

?>
<div class="container">
<script src="js/chart.min.js"></script>
  <div class="clearfix container d-flex justify-content-center text-center">
    <div  style="max-width: 700px;">
      <h2>Cредний набор по легам</h2>
      <canvas id="myChart" width="400" height="200"></canvas>
    </div>
  </div>
<script>
  var data = JSON.parse('<?php echo $jsonJS; ?>')
  var legCount = data.legCounter
  var player1Avg = data.player1Avg
  var player2Avg = data.player2Avg
    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
    data: {
      datasets: [{
        type: 'line',
        label: data.player1Name,
        data: player1Avg,
        borderColor: '#ff6384',
      }, {       
        type: 'line',
        label: data.player2Name,
        data: player2Avg,
        borderColor: '#36a2eb'
      }],
      labels: legCount
      }
});
</script>

</div><!-- /.container -->
<?php 
}
?>

</div>
</div>
<script src="/js/functions.js"></script>
  </body>
</html>

<?php
?>