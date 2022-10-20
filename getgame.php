<?php
include 'inc/db.php';

if(isset($_POST['name'])){
    $name = '%'.$_POST['name'].'%';
}else{
    $name = "";
}

if(isset($_POST['date'])){
    $dategame = '%'.$_POST['date'].'%';
}else{
    $dategame = "%";
}

if(isset($_POST['tag'])){
    $tag = '%'.trim($_POST['tag'], '#').'%';
}else{
    $tag = "%";
}

$sql = "SELECT id, gamer1_name, legs1, gamer2_name, legs2, last_update, tag, end_match, code_version FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' ORDER BY last_update DESC";	

$result = mysqli_query($conn, $sql);

$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);


echo'<div class="container">';
if (empty($rows)) {
    echo '
    <div class="text-center"><h2>Нет игр для отображения</h2></div>
    ';
} else {


foreach ($rows as $row) {

    if (!empty($row['code_version'])) {
        echo '
        <div class="row d-flex align-items-center border-bottom">
            <div class="row d-flex align-items-center">
            <div class="col-1 d-flex flex-column align-items-center align-content-center" style="font-size: 25px;">
            ';
            if ($row['end_match'] == 1){
                echo '
                <div><a href=finalscore.php?id='.$row['id'].'&view=phone><i class="bi bi-phone"></i></a></div>
                <div><a href=finalscore.php?id='.$row['id'].'&view=desktop><i class="bi bi-display"></i></a></div>
                ';
            } else { 
                echo '
                <div><a href=score.php?id='.$row['id'].'&view=phone><i class="bi bi-phone"></i></a></div>
                <div><a href=score.php?id='.$row['id'].'&view=desktop><i class="bi bi-display"></i></a></div>
                ';
            }
            echo '
            </div>
        	<div class="col-5 d-flex flex-column">
                <div class="text-truncate  text-end" >'.$row['gamer1_name'].'</div>
                <div class="text-end">'.$row['legs1'].'</div>
            </div>
            <div class="col-1 align-items-center text-center">
                VS
            </div>
            <div class="col-5 flex-column justify-content-end">
                <div class=" text-truncate">'.$row['gamer2_name'].'</div>
                <div>'.$row['legs2'].'</div>
            </div>
            </div>';

        if (!empty($row['tag'])){
            echo'
            <div class="row d-flex text-center align-items-top justify-content-center">';
        ?>
                <div class="offset-1 col-12 mt-0 pt-0 mb-0 pb-0"><a href="#<?php echo($row['tag']) ?>" id="tagInValue" onclick="setTag('<?php echo($row['tag']) ?>'); return false">#<?php echo($row['tag']) ?></a></div>
            </div>
          
        <?php    
        }

        echo '</div>';    
    } else {
        echo '
            <div class="row d-flex align-items-center border-bottom">
                <div class="col-1 d-flex flex-column align-items-center" style="font-size: 25px;"> 
                    <div><a href=score.php?id='.$row['id'].'&view=phone><i class="bi bi-phone"></i></a></div>
                    <div><a href=score.php?id='.$row['id'].'&view=desktop><i class="bi bi-display"></i></a></div>
                </div>
        	    <div class="col-5 justify-content-end text-end align-items-center">
                    <div class="text-truncate">'.$row['gamer1_name'].'</div>
                    <div>'.$row['legs1'].'</div>
                </div>
                <div class="col-1 align-items-center text-center">
                    VS
                </div>
                <div class="col-5 flex-column justify-content-end">
                    <div class=" text-truncate">'.$row['gamer2_name'].'</div>
                    <div>'.$row['legs2'].'</div>
                </div>
            </div>
            ';
    }
	
}
}
?>
</div>