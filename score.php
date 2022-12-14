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
    <div class="row bg-dark text-white">
        <div class="col-md-12"><strong><span id="gamePlayersCount"></span>&nbsp;<span id="gameName"></span></strong></div>
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

<?php
if ($video == true ) {
?>
<div class="container">
    <div id="videoframe"></div>
        <!-- Load OvenPlayer via CDN -->
        <script src="https://cdn.jsdelivr.net/npm/ovenplayer/dist/ovenplayer.js"></script>
        <script>
               // Initialize OvenPlayer
               const player = OvenPlayer.create('videoframe', {
                   sources: [
           {
               label: 'Трасляция игры',
               autoStart: 'true',
               // Set the type to 'webrtc'
               type: 'webrtc',
               // Set the file to WebRTC Signaling URL with OvenMediaEngine
               file: 'wss://video.darts28.ru:3334/vidme/' + <?php echo($id);?>
           }
                   ]
               });
        </script>
</div>
<?php
}
?>



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
        <div class="col-md-12"><strong><span id="gamePlayersCount"></span>&nbsp;<span id="gameName"></span></strong></div>
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
    <?php 
        if ($video == true ) {
            ?>
        <div class="col-1 justify-content-end text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer1"></span></div>
        <div class="col-3 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer1">501</span></div>
        <div class="col-4">
        <div id="videoframe">
        </div>        
            <!-- Load OvenPlayer via CDN -->
            <script src="https://cdn.jsdelivr.net/npm/ovenplayer/dist/ovenplayer.js"></script>

            <script>
            
                            // Initialize OvenPlayer
                            const player = OvenPlayer.create('videoframe', {
                                sources: [
                        {
                            label: 'label_for_webrtc',
                            autoStart: 'true',
                            // Set the type to 'webrtc'
                            type: 'webrtc',
                            // Set the file to WebRTC Signaling URL with OvenMediaEngine 
                            file: 'wss://video.darts28.ru:3334/vidme/' + <?php echo($id);?>
                        }
                                ]
                            });
            </script>
        </div>
        <div class="col-3 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer2">501</span></div>
        <div class="col-1 justify-content-start text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer2"></span></div>
        <?php 
        } else { ?>
        <div class="col-1 justify-content-end text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer1"></span></div>
        <div class="col-4 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer1">501</span></div>
        <div class="col"></div>
        <div class="col-4 text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: 800;font-size: 150px;"><span id="requirePlayer2">501</span></div>
        <div class="col-1 justify-content-start text-uppercase" style="padding: 0px 0px;text-align: center;font-weight: bold;font-size: 50px;"><span id="throwPlayer2"></span></div>
        <?php }?>
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
} else if ($view='obs') {
    ?>
    <div class="container-fluid" style="font-size: 30px;">
    <div class="row bg-dark text-white">
        <div class="col-6">
            <span id="gamePlayersCount"></span>&nbsp;<span id="gameName"></span>
        </div>
        <div class="col-2 text-center">PTS</div>
        <div class="col-2 text-center">Score</div>
        <div class="col-2">Throw</div>
    </div>
    <div class="row border-bottom align-items-center">
        <div class="col-6 d-flex flex-row">
            <span id="namePlayer1" class="flex-grow-1">Player 1</span>
            <span id="beginLeg1"></span>
            <span id='throwPlayer1' style='display: none;'></span>
        </div>
        <div class="col-2 text-center">
            <span id="setsPlayer1"></span>
            <span id="legsPlayer1">
        </div>
        <div class="col-2 text-center">
            <span id="requirePlayer1">501</span> 
        </div>
        <div class="col-2">
               <span id="scorePlayer1">0</span>
        </div>
    </div>

    <div class="row border-bottom align-items-center">
        <div class="d-flex flex-row col-6">
            <span id="namePlayer2" class="flex-grow-1">Player 2</span> 
            <span id="beginLeg2"></span>
            <span id='throwPlayer2'  style='display: none;'></span>
        </div>
        <div class="col-2 text-center">
            <span id="setsPlayer2"></span>
            <span id="legsPlayer2">
        </div>
        <div class="col-2 text-center">
            <span id="requirePlayer2">501</span>
        </div>
        <div class="col-2">
               <span id="scorePlayer2">0</span>
        </div>
    </div>
    
    <div class="row text-light bg-dark">
        <div class="col"><span id="tournamentName"></span>&nbsp;<span id="stage"></span></div>
    </div>
</div>

<div class="container" style="display: none; font-weight: bold;font-size: 25px;">
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
    <div class="flex-fill justify-content-end text-end" hidden>
        <a href="/finalscore.php?id=<?php echo $id; ?>&view=phone" target="_blank">Пошаговая статистика</a>
    </div>
<?php } else {
    ?>
    <div class="flex-fill justify-content-end text-end" hidden>
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
<?php
require_once __DIR__.'/template/footer.php';
?>