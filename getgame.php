<?php
require_once __DIR__.'/inc/boot.php';

if(isset($_POST['name'])){
    $_SESSION["name"] = $_POST['name'] ;
    $name = '%'.$_POST['name'].'%';
}else{
    $name = "";
}

if(isset($_POST['date'])){
    $_SESSION["dategame"] = $_POST['date'];
    $dategame = '%'.$_POST['date'].'%';
}else{
    $dategame = "%";
}

if(isset($_POST['tag'])){
    $_SESSION["tag"] = $_POST['tag'];
    $tag = '%'.trim($_POST['tag'], '#').'%';
}else{
    $tag = "%";
}

if(isset($_POST['delete'])){
    $deleteGame = $_POST['delete'];
    echo $deleteGame;
} else {
    $deleteGame = false;
}

// Удаляем игру
if ($deleteGame == true) {
 $stmt = pdo() -> prepare("DELETE * FROM games WHERE id=:id");
 $stmt->execute(['id'->$_POST['gameId']]);
 print_r($stmt);

} else {


// Рисуем пагинацию, определим пришел ли нам номер страницы
if (isset($_POST['pageno'])) {
    // Если да то переменной $pageno его присваиваем
    $pageno = $_POST['pageno'];
} else { // Иначе
    // Присваиваем $pageno один
    $pageno = 1;
}
 
// Назначаем количество игры на одной странице
$size_page = 10;
// Вычисляем с какого объекта начать выводить
$offset = ($pageno-1) * $size_page;

// Посчитаем сколько вообще записей по нашему запросу
$sth = pdo()->prepare("SELECT COUNT(*) as count FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' ORDER BY last_update DESC");
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$total_rows = $row['count'];
// Вычисляем количество страниц
$total_pages = ceil($total_rows / $size_page);


$stmt = pdo()->prepare("SELECT id, gamer1_name, legs1, gamer2_name, legs2, last_update, tag, end_match, code_version FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' ORDER BY last_update DESC LIMIT $offset, $size_page");
$stmt->execute();
$empty = $stmt->rowCount() === 0;

echo'<div class="container">';
if ($empty) {
    echo '
    <div class="text-center"><h2>Нет игр для отображения</h2></div>
    ';
} else {


foreach ($stmt as $row) {

    if (!empty($row['code_version'])) {
        echo '
        <div class="row d-flex align-items-center border-bottom">
            <div class="row d-flex align-items-center">
            <div class="col-1 d-flex flex-column align-items-center align-content-center" style="font-size: 25px;">
            ';
            if ($_SESSION["user_role"] == 1) {
                echo '
                <div><a href="#'.$row["id"].'" onclick=deleteGame('.$row["id"].') <i class="bi bi-trash" style="color:red;"></i></a></div>
                <div><a href="#" onclick=editGame(id='.$row["id"].') <i class="bi bi-pencil-square" style="color:green;"></i></a></div>
                ';
            } else if ($_SESSION["user_role"] == 2) {
                echo '
                <div><a href=https://video.darts28.ru/webkit/index.php?id='.$row['id'].'><i class="bi bi-camera-reels" style="color:red;"></i></a></div>
                ';
            } else {
                if ($row['end_match'] == 1){
                    echo '
                    <div><a href=finalscore.php?id='.$row['id'].'&view=phone><i class="bi bi-phone-fill" style="color:green;"></i></a></div>
                    <div><a href=finalscore.php?id='.$row['id'].'&view=desktop><i class="bi bi-display-fill" style="color:green;"></i></a></div>
                    ';
                } else { 
                    echo '
                    <div><a href=score.php?id='.$row['id'].'&view=phone><i class="bi bi-phone"></i></a></div>
                    <div><a href=score.php?id='.$row['id'].'&view=desktop><i class="bi bi-display"></i></a></div>
                    ';
                }
            }
            echo '
            </div>
            ';
            echo '
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
                </div> ';
            echo '
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
<?php if($total_pages > 1){?>
</div>
<!-- <div class="container py-3">
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" onclick='javascript:getgame(pageno=1)' href="#">Первая</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } else {echo "page-item";} ?>">
            <a class="page-link" onclick='javascript:getgame(pageno=<?php if($pageno <= 1){ echo "#";} else { echo ($pageno - 1); } ?>);' href="#">Назад</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } else {echo "page-item";} ?>">
            <a class="page-link" onclick='javascpript:getgame(pageno=<?php if($pageno >= $total_pages){ echo "#"; } else { echo ($pageno + 1); } ?>)' href="#">Вперед</a>
        </li>
        <li class="page-item"><a class="page-link" onclick='javascpript:getgame(pageno=<?php echo $total_pages; ?>)' href="#">Последняя</a></li>
    </ul>
    </nav>
</div> -->

<div class="container py-3">
    <nav>
        <ul class="pagination justify-content-center">
<?php for ($pageNum = 1; $pageNum <= $total_pages; $pageNum++): ?>
            <li class="page-item"><a class="page-link" onclick='javascript:getgame(pageno=<?php echo $pageNum;?>);' href="#"><?php echo $pageNum;?></a></li>
<?php endfor; ?>
        </ul>
    </nav>
</div>

<?php }
}
?>