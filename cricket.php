<!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <!-- Bootstrap JS + Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <!-- JS -->
    <script src="/assets/js/NoSleep.min.js"></script> 
    <script src="/js/functions.js"></script>

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

if ($view == "phone") {

?>
<!--
Отображение для телефонов
-->

<div class="container">
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1Name" class="col-5 col-md-2">Player1</div>
        <div id="scoresId" class="col-2 col-md-1">VS</div>
        <div id="player2Name" class="col-5 col-md-2">Player2</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1PTS" class="col-5 col-md-2">0</div>
        <div class="col-2 col-md-1">Scores</div>
        <div id="player2PTS" class="col-5 col-md-2">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s20Score" class="col-2 col-md-1">0</div>
        <div id="player1s20" class="col-3 col-md-1">-</div>
        <div id="s20" class="col-2 col-md-1">20</div>
        <div id="player2s20" class="col-3 col-md-1">-</div>
        <div id="player2s20Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s19Score" class="col-2 col-md-1">0</div>
        <div id="player1s19" class="col-3 col-md-1">-</div>
        <div id="s19"class="col-2 col-md-1">19</div>
        <div id="player2s19" class="col-3 col-md-1">-</div>
        <div id="player2s19Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s18Score" class="col-2 col-md-1">0</div>
        <div id="player1s18" class="col-3 col-md-1">-</div>
        <div id="s18" class="col-2 col-md-1">18</div>
        <div id="player2s18" class="col-3 col-md-1">-</div>
        <div id="player2s18Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s17Score" class="col-2 col-md-1">0</div>
        <div id="player1s17"  class="col-3 col-md-1">-</div>
        <div id="s17" class="col-2 col-md-1">17</div>
        <div id="player2s17" class="col-3 col-md-1">-</div>
        <div id="player2s17Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s16Score" class="col-2 col-md-1">0</div>
        <div id="player1s16" class="col-3 col-md-1">-</div>
        <div id="s16" class="col-2 col-md-1">16</div>
        <div id="player2s16" class="col-3 col-md-1">-</div>
        <div id="player2s16Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1s15Score" class="col-2 col-md-1">0</div>
        <div id="player1s15" class="col-3 col-md-1">-</div>
        <div id="s15" class="col-2 col-md-1">15</div>
        <div id="player2s15" class="col-3 col-md-1">-</div>
        <div id="player2s15Score" class="col-2 col-md-1">0</div>
    </div>
    <div class="row justify-content-center align-items-center text-center" style="height: 75px;">
        <div id="player1sBullScore" class="col-2 col-md-1">0</div>
        <div id="player1sBull" class="col-3 col-md-1">-</div>
        <div id="sBull" class="col-2 col-md-1">Bull</div>
        <div id="player2sBull" class="col-3 col-md-1">-</div>
        <div id="player2sBullScore" class="col-2 col-md-1">0</div>
    </div>
</div>

<?php }


?>
<script>
  let id = <?php echo $id ?>;
</script>
<script src="/js/cricket.js"></script>
<?php
require_once __DIR__.'/template/footer.php';
?>

