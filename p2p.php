<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';
// $id = $_GET["id"];

if (isset($_SESSION["stage"])){
    $stage = $_SESSION["stage"];
} else {
    $stage = 'register';
    $_SESSION["stage"] = 'register';
}


# stage
# register - регистрация игры
# answer - регистрация второго участника
# start1Player or start2Player - кто из игроков бросает первым ???
# throw1Player - подход первого игрока
# throw2Player - подход второго игрока
//  $stage = 'register';
?>


<div class="container">
<div class="currentThrowIcon"></div>
<div class="startPlayerIcon"></div>

<?php flash() ?>
</div>
<div class="container">

<?php 
if ($stage == 'register') {
?>
<div class="row text-center justify-content-center align-items-center g-2">
    <h1 class="mb-3">Запрос на игру</h1>
</div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="username" class="form-label">PLAYER 1 NAME</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="scores" class="form-label">Начальное кол-во очков</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID PLAYER 1</label>
                    <input type="text" class="form-control" id="guid" name="guid" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Получить ключ игры</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php } 


if ($stage == 'answer') {
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="username" class="form-label">PLAYER 2 NAME</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID 2 NAME</label>
                    <input type="text" class="form-control" id="guid" name="guid" required>
                </div>
                <div class="mb-3">
                    <label for="key" class="form-label">key of game</label>
                    <input type="text" class="form-control" id="key" name="key" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Ответить на вызов</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php
}
if ($stage == 'throw1Player') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-2"><?php echo $game['require1'];?> : <?php echo $game['require2'];?></div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="scores" class="form-label">Набор игрок 1</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>


<?php
}
if ($stage == 'throw2Player') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-2"><?php echo $game['require1'];?> : <?php echo $game['require2'];?></div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <form method="post" action="do_p2p.php">
                <div class="mb-3">
                    <label for="scores" class="form-label">Набор игрок 2</label>
                    <input type="text" class="form-control" id="scores" name="scores" required>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
            </br>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>


<?php
}

?>