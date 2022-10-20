<?php
require_once('template/header.tpl');
$id = $_GET["id"];

if(isset($_GET['view'])){
    $view = $_GET['view'];
}else{
    $view = "full";
}

if ($view == "phone") {

?>
<!--
Отображение для телефонов
-->

<div class="container">
    <div class="row bg-dark text-white">
        <div class="col-md-12"><strong><span id="gameType"></span>&nbsp;<span id="gameName"></span></strong></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 2vmax;"><span id="namePlayer1">Player 1</span></div>
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 2vmax;"><span id="namePlayer2">Player 2</span></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-6" style="width: 50%; padding: 0px 0px;text-align: center;font-weight: bold;font-size: 100px;"><span id="requirePlayer1">501</span></div>
        <div class="col-6" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 100px;"><span id="requirePlayer2">501</span></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-6 d-flex flex-row align-content-center align-items-center justify-content-center" style="height: 70px;width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 25px;"><span id="throwPlayer1"></span><span id="beginLeg1"></span><span id="scorePlayer1">0</span></div>

        <div class="col-md-6 d-flex flex-row align-content-center align-items-center justify-content-center" style="height: 70px;width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 25px;"><span id="scorePlayer2">0</span> <span id="beginLeg2"></span> <span id="throwPlayer2"></span></div>
    </div>
</div>
<div class="container">
    <div class="row bg-dark text-white">
        <div class="col-md-12"><strong><span id="tournamentName"></span>&nbsp;<span id="stage"></span></strong></div>
    </div>
</div>

<div class="container" style="font-weight: bold;font-size: 20px;">
    <div class="row d-flex align-items-center border-bottom" >
        <div class="col-4 col-md-5 d-flex flex-column text-end justify-content-end"><span id="setsPlayer1"></span><span id="legsPlayer1"></span></div>
        <div class="col-4 col-md-2 text-center">Счет</div>
        <div class="col-4 col-md-5 d-flex flex-column text-start"><span id="setsPlayer2"></span><span id="legsPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="avgPlayer1"></span></div>
        <div class="col-4 col-md-2 text-center">Набор</div>
        <div class="col-4 col-md-5 text-start"><span id="avgPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="avg9Player1"></span></div>
        <div class="col-4 col-md-2 text-center">Первые 9</div>
        <div class="col-4 col-md-5 text-start"><span id="avg9Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="c180Player1"></span></div>
        <div class="col-4 col-md-2 text-center">180</div>
        <div class="col-4 col-md-5 text-start"><span id="c180Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="c140Player1"></span></div>
        <div class="col-4 col-md-2 text-center">140+</div>
        <div class="col-4 col-md-5 text-start"><span id="c140Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="c100Player1"></span></div>
        <div class="col-4 col-md-2 text-center">100+</div>
        <div class="col-4 col-md-5 text-start"><span id="c100Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="adlPlayer1"></span></div>
        <div class="col-4 col-md-2 text-center">Леги</div>
        <div class="col-4 col-md-5 text-start"><span id="adlPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 col-md-5 text-end"><span id="acheckPlayer1"></span></div>
        <div class="col-4 col-md-2 text-center">Окончания</div>
        <div class="col-4 col-md-5 text-start"><span id="acheckPlayer2"></span></div>
    </div>


    <div class="row" id="hiddenDivAvg" style="display:none">
        <div class="col-4 col-md-4 text-end align-self-start"><span id="avgPlayer1"></span></div>
        <div class="col-4 col-md-4 text-center align-self-start"></div>
        <div class="col-4 col-md-4 text-start align-self-start"><span id="avgPlayer2"></span></div>
    </div>

</div>

<?php
} elseif ($view == "desktop") {

?>
<!--
Отображение для компьютеров и больших экранов
-->
<div class="container">
    <div class="row bg-dark text-white" style="font-size:20px;">
        <div class="col-md-12"><strong><span id="gameType"></span>&nbsp;<span id="gameName"></span></strong></div>
    </div>
</div>
<div class="container ">
    <div class="row d-flex align-items-center">
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 4vmin;"><span id="namePlayer1">Player 1</span></div>
        <div class="col-md-6 text-truncate text-uppercase" style="width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 4vmin;"><span id="namePlayer2">Player 2</span></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex flex-row align-items-center" style="padding: 0px 0px 0px 0px;">
        <div class="col-1 justify-content-end text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer1"></span></div>
        <div class="col-4 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer1">501</span></div>
        <div class="col"></div>
        <div class="col-4 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer2">501</span></div>
        <div class="col-1 justify-content-start text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer2"></span></div>
    </div>
</div>
<div class="container">
    <div class="row d-flex align-items-center">
        <div class="col-md-6 d-flex flex-row align-content-center align-items-center justify-content-center" style="height: 70px;width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="beginLeg1"></span><span id="scorePlayer1">0</span></div>

        <div class="col-md-6 d-flex flex-row align-content-center align-items-center justify-content-center" style="height: 70px;width: 50%;padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="scorePlayer2">0</span> <span id="beginLeg2"></span> </div>
    </div>
</div>
<div class="container">
    <div class="row bg-dark text-white" style="font-size: 20px;">
        <div class="col-md-12"><strong><span id="tournamentName"></span>&nbsp;<span id="stage"></span></strong></div>
    </div>
</div>

<div class="container" style="font-weight: bold;font-size: 25px;">
<!--     <div class="row d-flex border-bottom"  style="display:none; font-weight: bold;font-size: 3vmin">
        <div class="col-5 col-md-5 text-end align-self-start text-uppercase text-truncate"><span id="namePlayer1Stat"></span></div>
        <div class="col-2 col-md-2 text-center align-self-start"></div>        
        <div class="col-5 col-md-5 text-start align-self-start text-uppercase text-truncate"><span id="namePlayer2Stat"></span></div>
    </div> -->
    
    <div class="row d-flex align-items-center border-bottom" style="font-weight: bold;font-size: 40px;">
        <div class="col-4 d-flex flex-column text-end justify-content-end"><span id="setsPlayer1"></span><span id="legsPlayer1"></span></div>
        <div class="col-4 text-center">Счет</div>
        <div class="col-4 d-flex flex-column text-start"><span id="setsPlayer2"></span><span id="legsPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="avgPlayer1"></span></div>
        <div class="col-4 text-center">Набор</div>
        <div class="col-4 text-start"><span id="avgPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="avg9Player1"></span></div>
        <div class="col-4 text-center">Первые 9</div>
        <div class="col-4 text-start"><span id="avg9Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="c180Player1"></span></div>
        <div class="col-4 text-center">180</div>
        <div class="col-4 text-start"><span id="c180Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="c140Player1"></span></div>
        <div class="col-4 text-center">140+</div>
        <div class="col-4 text-start"><span id="c140Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="c100Player1"></span></div>
        <div class="col-4 text-center">100+</div>
        <div class="col-4 text-start"><span id="c100Player2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="adlPlayer1"></span></div>
        <div class="col-4 text-center">Леги</div>
        <div class="col-4 text-start"><span id="adlPlayer2"></span></div>
    </div>
    <div class="row d-flex align-items-center border-bottom">
        <div class="col-4 text-end"><span id="acheckPlayer1"></span></div>
        <div class="col-4 text-center">Окончания</div>
        <div class="col-4 text-start"><span id="acheckPlayer2"></span></div>
    </div>


    <div class="row" id="hiddenDivAvg" style="display:none">
        <div class="col-4 col-md-4 text-end align-self-start"><span id="avgPlayer1"></span></div>
        <div class="col-4 col-md-4 text-center align-self-start"></div>
        <div class="col-4 col-md-4 text-start align-self-start"><span id="avgPlayer2"></span></div>
    </div>

</div>


<?php 
} else {

    echo '
        <div class="container">
        <h1>Не выбран вид отображения игры, вы точно попали по адресу?</h1>
        </div>
    ';
}
?>
<div class="container d-flex flex-row align-items-end align-content-end" style="height: 50px">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="toggle">
      <label class="form-check-label" for="toggle">Не отключать экран</label>
    </div>
<?php
    if ($view == "phone") {
?>
    <div class="flex-fill justify-content-end text-end">
        <a href="/finalscore.php?id=<?php echo $id; ?>&view=phone" target="_blank">Пошаговая статистика</a>
    </div>
<?php } else {
    ?>
    <div class="flex-fill justify-content-end text-end">
        <a href="/finalscore.php?id=<?php echo $id; ?>" target="_blank">Пошаговая статистика</a>
    </div>
  <?php
}
?>
</div>
</div>
<script>
  let id = <?php echo $id ?>;
</script>
<script src="/js/script.js"></script>
</body>
</html>





