<?php
################
## Скрипт для формирования списка игр
## 
##
// Подключаем обязательные файлы
require_once __DIR__ . '/inc/boot.php';

################### НАСТРОЙКИ ###################
// Назначаем количество игр на одной странице
$size_page = $page_on_list; // берется из конфига

if (isset($_POST['name'])) {
    $_SESSION["name"] = $_POST['name'];
    $name = '%' . $_POST['name'] . '%';
} else {
    $name = "";
}

if (isset($_POST['date'])) {
    $_SESSION["dategame"] = $_POST['date'];
    $dategame = '%' . $_POST['date'] . '%';
} else {
    $dategame = "%";
}

if (isset($_POST['tag'])) {
    $_SESSION["tag"] = $_POST['tag'];
    $tag = '%' . trim($_POST['tag'], '#') . '%';
} else {
    $tag = "%";
}

if (isset($_POST['admGames'])) {
    $admGames = $_POST['admGames'];
} else
    $admGames = false;

if (isset($_POST['delete'])) {
    $deleteGame = $_POST['delete'];
    echo $deleteGame;
} else {
    $deleteGame = false;
}

if (isset($_POST['list'])) {
    $list = $_POST['list'];
} else {
    $list = "all";
}


if ($list === "p2p") {
    $stmt = pdo()->prepare("SELECT * FROM p2p_games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND privateStartGame = 0 AND last_update >= now() - interval 3 hour ORDER BY last_update DESC");
    $stmt->execute();
    $empty = $stmt->rowCount() === 0;

    echo '<div class="container">';
    if ($empty) {
        echo '
        <div class="text-center"><h2>Нет игр для отображения</h2></div>

        ';
    } else {

        // echo 'true';
        foreach ($stmt as $row) {
            if ($row['gamer2_name']) {
                $stmh = pdo()->prepare("SELECT * FROM games WHERE `guid`=?");
                $stmh->execute([$row['guid_gamer1']]);
                $idView = $stmh->fetch(PDO::FETCH_ASSOC);
                // print_r($idView);
                ?>
                <div class="container">
                    <div class="row justify-content-center align-items-center g-2">
                        <div class="col">
                            <?php echo $row['gamer1_name'];?> VS <?php echo $row['gamer2_name']; ?>
                        </div>
                        <div class="col">
                            <?php if ($idView['game_type'] == 'Cricket' && $idView['end_match'] != true) { ?>
                            <div class="d-block d-md-none">Игра уже идёт. <a href=cricket.php?id=<?php echo $idView['id'];?>&view=phone>смотреть</a></div>
                            <div class="d-none d-sm-block">Игра уже идёт. <a href=cricket.php?id=<?php echo $idView['id'];?>&view=desktop>смотреть</a></div>
                            <?php } ?>
                            <?php if ($idView['game_type'] == 'Cricket' && $idView['end_match'] == true) { ?>
                            <div class="d-block d-md-none">Игра закончилась. <a href=finalcricket.php?id=<?php echo $idView['id'];?>&view=phone>>>> РЕЗУЛЬТАТ </a></div>
                            <div class="d-none d-sm-block">Игра закончилась. <a href=finalcricket.php?id=<?php echo $idView['id'];?>&view=desktop>>>> РЕЗУЛЬТАТ </a></div>
                            <?php } ?>
                            <?php if ($idView['game_type'] == 'x01' && $idView['end_match'] != true) { ?>
                            <div class="d-block d-md-none">Игра уже идёт. <a href=score.php?id=<?php echo $idView['id'];?>&view=phone>смотреть</a></div>
                            <div class="d-none d-sm-block">Игра уже идёт. <a href=score.php?id=<?php echo $idView['id'];?>&view=desktop>смотреть</a></div>
                            <?php } ?>
                            <?php if ($idView['game_type'] == 'x01' && $idView['end_match'] == true) { ?>
                            <div class="d-block d-md-none">Игра закончилась. <a href=finalscore.php?id=<?php echo $idView['id'];?>&view=phone>>>> РЕЗУЛЬТАТ </a></div>
                            <div class="d-none d-sm-block">Игра закончилась. <a href=finalscore.php?id=<?php echo $idView['id'];?>&view=desktop>>>> РЕЗУЛЬТАТ </a></div>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php echo $row['require1'];?> : <?php echo $row['require2']; ?> 
                        </div>
                    </div>
                </div>
                <?php
            } else {
?>

                <div class="container">
                    <div class="row justify-content-center align-items-center g-2">
                        <div class="col"><?php echo $row['gamer1_name']; ?> готов играть. </div>
                        <div class="col">
                        <a href="p2p.php?stage=answer&key=<?php echo $row['key'];?>">Присоединиться к игре</a></div>
                        <div class="col">Ключ игры: <?php echo $row['key']; ?></div>
                    </div>
                </div>

                <?php
            }
        }
    }
    die;
}



// Удаляем игру
if ($deleteGame == true && $_SESSION['user_role'] == 1) {
    $stmt = pdo()->prepare("DELETE FROM games WHERE id=:id");
    $stmt->execute(['id' => $_POST['id']]);
    // print_r($stmt);
    die;
} 


// ПАГИНАЦИЯ    
// Рисуем пагинацию, определим пришел ли нам номер страницы
if (isset($_POST['pageno'])) {
    // Если да то переменной $pageno его присваиваем
    $pageno = $_POST['pageno'];
} else { // Иначе
    // Присваиваем $pageno один
    $pageno = 1;
}
// Вычисляем с какого объекта начать выводить
$offset = ($pageno - 1) * $size_page;
// Посчитаем сколько вообще записей по нашему запросу
$sth = pdo()->prepare("SELECT COUNT(*) as count FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' AND game_delete!=1 ORDER BY last_update DESC");
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$total_rows = $row['count'];
// Вычисляем количество страниц
$total_pages = ceil($total_rows / $size_page);

// $stmt = pdo()->prepare("SELECT id, game_type, game_type_name gamer1_name, legs1, gamer2_name, legs2, last_update, tag, end_match, video, code_version FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' ORDER BY last_update DESC LIMIT $offset, $size_page");
$stmt = pdo()->prepare("SELECT * FROM games WHERE (gamer1_name LIKE '$name' OR gamer2_name LIKE '$name') AND game_delete!=1 AND (tag LIKE '$tag' OR tag IS NULL) AND last_update LIKE '%$dategame%' ORDER BY last_update DESC LIMIT $offset, $size_page");
$stmt->execute();
$empty = $stmt->rowCount() === 0;

echo '<div class="container">';
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
            if ($row['game_type'] == 'Cricket') {
                if ($_SESSION['user_role'] == 1) {
                    echo '
                <div class=""><a href="#' . $row["id"] . '" onclick=deleteGame(' . $row["id"] . ') <i class="bi bi-trash" style="color:red;"></i></a></div>
                <div class=""><a href="#" onclick=editGame(id=' . $row["id"] . ') <i class="bi bi-pencil-square" style="color:green;"></i></a></div>
                <div class="d-block d-md-none"><a href=cricket.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                <div class="d-none d-sm-block"><a href=cricket.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                ';
                } else if ($_SESSION["user_role"] == 2 && $row['end_match'] != 1) {
                    echo '               
                <div class=""><a href=https://' . $_SERVER['HTTP_HOST'] . '/video.php?id=' . $row['id'] . '><i class="bi bi-camera-reels" style="color:green;"></i></a></div>
                <div class="d-block d-md-none" ><a href=cricket.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                <div class="d-none d-sm-block" ><a href=cricket.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                ';
                } else if ($_SESSION["user_role"] == 2 && $row['end_match'] == 1) {
                    echo '               
               <div class=""><a href="#"><i class="bi bi-camera-reels" style="color:red;"></i></a></div>
               <div class="d-block d-md-none" ><a href=finalcricket.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
               <div class="d-none d-sm-block" ><a href=finalcricket.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
               ';
                } else {
                    if ($row['end_match'] == 1) {
                        echo '
                    <div class="d-block d-md-none" ><a href=finalcricket.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone-fill" style="color:green;"></i></a></div>
                    <div class="d-none d-sm-block" ><a href=finalcricket.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display-fill" style="color:green;"></i></a></div>
                    ';
                    } else {
                        echo '
                    <div class="d-block d-md-none"><a href=cricket.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                    <div class="d-none d-sm-block"><a href=cricket.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                    ';
                    }
                }
            } else {
                if ($_SESSION['user_role'] == 1) {
                    echo '
                <div class=""><a href="#' . $row["id"] . '" onclick=deleteGame(' . $row["id"] . ') <i class="bi bi-trash" style="color:red;"></i></a></div>
                <div class=""><a href="#" onclick=editGame(id=' . $row["id"] . ') <i class="bi bi-pencil-square" style="color:green;"></i></a></div>
                <div class="d-block d-md-none"><a href=score.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                <div class="d-none d-sm-block"><a href=score.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                ';
                } else if ($_SESSION["user_role"] == 2 && $row['end_match'] != 1) {
                    echo '               
                <div class=""><a href=https://' . $_SERVER['HTTP_HOST'] . '/video.php?id=' . $row['id'] . '><i class="bi bi-camera-reels" style="color:green;"></i></a></div>
                <div class="d-block d-md-none" ><a href=score.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                <div class="d-none d-sm-block" ><a href=score.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                ';
                } else if ($_SESSION["user_role"] == 2 && $row['end_match'] == 1) {
                    echo '               
               <div class=""><a href="#"><i class="bi bi-camera-reels" style="color:red;"></i></a></div>
               <div class="d-block d-md-none" ><a href=finalscore.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
               <div class="d-none d-sm-block" ><a href=finalscore.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
               ';
                } else {
                    if ($row['end_match'] == 1) {
                        echo '
                    <div class="d-block d-md-none" ><a href=finalscore.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone-fill" style="color:green;"></i></a></div>
                    <div class="d-none d-sm-block" ><a href=finalscore.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display-fill" style="color:green;"></i></a></div>
                    ';
                    } else {
                        echo '
                    <div class="d-block d-md-none"><a href=score.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                    <div class="d-none d-sm-block"><a href=score.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                    ';
                    }
                }
            }
            echo '
        </div>
        ';
            echo '
            <div class="col-5 d-flex flex-column">
                <div class="text-truncate  text-end" >' . $row['gamer1_name'] . '</div>
                <div class="text-end">' . $row['legs1'] . '</div>
            </div>
            <div class="col-1 align-items-center text-center">
                VS
            </div>
            <div class="col-5 flex-column justify-content-end">
                <div class=" text-truncate">' . $row['gamer2_name'] . '</div>
                <div>' . $row['legs2'] . '</div>
            </div> ';
            echo '
        </div>';

            if (!empty($row['tag'])) {
                echo '
        <div class="row d-flex text-center align-items-top justify-content-center">';
            ?>
                <div class="offset-1 col-12 mt-0 pt-0 mb-0 pb-0"><a href="#<?php echo ($row['tag']) ?>" id="tagInValue" onclick="setTag('<?php echo ($row['tag']) ?>'); return false">#<?php echo ($row['tag']) ?></a></div>
                </div>

    <?php
            }

            echo '</div>';
        } else {
            echo '
        <div class="row d-flex align-items-center border-bottom">
            <div class="col-1 d-flex flex-column align-items-center" style="font-size: 25px;"> 
            ';
            if ($_SESSION['user_role'] == 1) {
                echo '
                <div><a href="#' . $row["id"] . '" onclick=deleteGame(' . $row["id"] . ') <i class="bi bi-trash" style="color:red;"></i></a></div>
                <div><a href="#" onclick=editGame(id=' . $row["id"] . ') <i class="bi bi-pencil-square" style="color:green;"></i></a></div>
                ';
            } else {
                echo '
                <div><a href=score.php?id=' . $row['id'] . '&view=phone><i class="bi bi-phone"></i></a></div>
                <div><a href=score.php?id=' . $row['id'] . '&view=desktop><i class="bi bi-display"></i></a></div>
                ';
            }
            echo '
            </div>
    	    <div class="col-5 justify-content-end text-end align-items-center">
                <div class="text-truncate">' . $row['gamer1_name'] . '</div>
                <div>' . $row['legs1'] . '</div>
            </div>
            <div class="col-1 align-items-center text-center">
                VS
            </div>
            <div class="col-5 flex-column justify-content-end">
                <div class=" text-truncate">' . $row['gamer2_name'] . '</div>
                <div>' . $row['legs2'] . '</div>
            </div>
        </div>
        ';
        }
    }
}
?>

<?php if ($total_pages > 1) { ?>
        </div>
        <!-- <div class="container py-3">
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" onclick='javascript:getgame(pageno=1)' href="#">Первая</a></li>
        <li class="<?php if ($pageno <= 1) { echo 'disabled';} else {echo "page-item";} ?>">
            <a class="page-link" onclick='javascript:getgame(pageno=<?php if ($pageno <= 1) {echo "#";} else {echo ($pageno - 1);} ?>);' href="#">Назад</a>
        </li>
        <li class="<?php if ($pageno >= $total_pages) {echo 'disabled';} else {echo "page-item";} ?>">
            <a class="page-link" onclick='javascpript:getgame(pageno=<?php if ($pageno >= $total_pages) {echo "#";} else {echo ($pageno + 1);} ?>)' href="#">Вперед</a>
        </li>
        <li class="page-item"><a class="page-link" onclick='javascpript:getgame(pageno=<?php echo $total_pages; ?>)' href="#">Последняя</a></li>
    </ul>
    </nav>
</div> -->

        <div class="container py-3">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($pageNum = 1; $pageNum <= $total_pages; $pageNum++) : ?>
                        <li class="page-item"><a class="page-link" onclick='javascript:getgame(pageno=<?php echo $pageNum; ?>);' href="#"><?php echo $pageNum; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

<?php 
}

?>
<script>

</script>
<!-- <script src="/js/gamelist.js"></script> -->