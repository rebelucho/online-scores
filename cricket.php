<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';
$id = $_GET["id"];

if(isset($_GET['view'])){
    $view = $_GET['view'];
}else{
    $view = "full";
}

if (isset($_GET['video']))
    $video = $_GET['video'];
else
    $video = false;

// if ($view == "phone") {

?>
<!--
Отображение для телефонов
-->

<div class="container">
    <div class="row justify-content-center align-items-center text-center fs-5">
    <div id="gameName" class="col-12 col-lg-5"></div>
    </div>

    <div class="row justify-content-center align-items-center text-center border-top " style="height: 50px;">
        <div id="player1Name" class="col-5 col-lg-2 border-bottom h2 text-truncate text-uppercase">Player1</div>
        <div id="scoresId" class="col-2 col-lg-1 border-bottom h2">VS</div>
        <div id="player2Name" class="col-5 col-lg-2 border-bottom h2 text-truncate text-uppercase">Player2</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 50px;">
        <div id="player1PTSCurrent" class="col-2 col-lg-1"></div>
        <div id="player1PTS" class="col-3 col-lg-1"></div>
        <div id="rowPts" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center"></div>
        <div id="player2PTS" class="col-3 col-lg-1"></div>
        <div id="player2PTSCurrent" class="col-2 col-lg-1"></div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s20Score" class="col-2 col-lg-1"></div>
        <div id="player1s20" class="col-3 col-lg-1">-</div>
        <div id="s20" class="col-2 col-lg-1 border-start border-end d-flex align-items-center  justify-content-center" style="height: 75px;">20</div>
        <div id="player2s20" class="col-3 col-lg-1">-</div>
        <div id="player2s20Score" class="col-2 col-lg-1"></div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s19Score" class="col-2 col-lg-1">0</div>
        <div id="player1s19" class="col-3 col-lg-1">-</div>
        <div id="s19"class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">19</div>
        <div id="player2s19" class="col-3 col-lg-1">-</div>
        <div id="player2s19Score" class="col-2 col-lg-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s18Score" class="col-2 col-lg-1">0</div>
        <div id="player1s18" class="col-3 col-lg-1">-</div>
        <div id="s18" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center  justify-content-center">18</div>
        <div id="player2s18" class="col-3 col-lg-1">-</div>
        <div id="player2s18Score" class="col-2 col-lg-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s17Score" class="col-2 col-lg-1">0</div>
        <div id="player1s17"  class="col-3 col-lg-1">-</div>
        <div id="s17" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">17</div>
        <div id="player2s17" class="col-3 col-lg-1">-</div>
        <div id="player2s17Score" class="col-2 col-lg-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s16Score" class="col-2 col-lg-1">0</div>
        <div id="player1s16" class="col-3 col-lg-1">-</div>
        <div id="s16" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">16</div>
        <div id="player2s16" class="col-3 col-lg-1">-</div>
        <div id="player2s16Score" class="col-2 col-lg-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s15Score" class="col-2 col-lg-1">0</div>
        <div id="player1s15" class="col-3 col-lg-1">-</div>
        <div id="s15" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center">15</div>
        <div id="player2s15" class="col-3 col-lg-1">-</div>
        <div id="player2s15Score" class="col-2 col-lg-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1sBullScore" class="col-2 col-lg-1">0</div>
        <div id="player1sBull" class="col-3 col-lg-1">-</div>
        <div id="sBull" class="col-2 col-lg-1 border-start border-end h-100 d-flex align-items-center justify-content-center" >Bull</div>
        <div id="player2sBull" class="col-3 col-lg-1">-</div>
        <div id="player2sBullScore" class="col-2 col-lg-1">0</div>
    </div>
</div>

<div class="container d-flex flex-row align-items-end align-content-end" style="height: 50px">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="toggle">
      <label class="form-check-label" for="toggle">Не отключать экран</label>
    </div>
    <div class="flex-fill justify-content-end text-end" hidden>
        <div class="d-block d-md-none"><a href="/finalcricket.php?id=<?php echo $id; ?>&view=phone" target="_blank">Финальная статистика</a></div>
        <div class="d-none d-lg-block"><a href="/finalcricket.php?id=<?php echo $id; ?>" target="_blank">Финальная статистика</a>
    </div>
</div>

<?php 
// }




?>
<script>
  let id = <?php echo $id ?>;
</script>
<script src="/js/cricket.js"></script>
<?php
require_once __DIR__.'/template/footer.php';
?>

