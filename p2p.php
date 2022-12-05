<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';
// $id = $_GET["id"];

$user = null;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
function curdate() {
    return date('Y-m-d');
}

if (isset($_GET['stage']))
$_SESSION['stage'] = $_GET['stage'];

if (isset($_GET['key']))
$key = $_GET['key'];
else
$key = 0;


if (isset($_SESSION["stage"])){
    $stage = $_SESSION["stage"];
} else {
    $stage = 'list';
    $_SESSION["stage"] = 'list';
}

if (isset($_SESSION["dategame"])){
	$dategame = $_SESSION["dategame"];
} else {
	$dategame = curdate();
}

if (isset($_SESSION["name"])){
	$name = $_SESSION["name"];
} else {
	$name = "";
}

if (isset($_SESSION["tag"])){
	$tag = $_SESSION["tag"];
} else {
	$tag = "";
}

if (isset($_GET['admGame'])){
	$admGames = $_GET['admGames'];
} else {
	$admGames = false;
} 
	

if (isset($_GET['pageno'])) {
    // Если да то переменной $pageno его присваиваем
    $pageno = $_GET['pageno'];
} else { // Иначе
    // Присваиваем $pageno один
    $pageno = 1;
}


# stage
# list - список активных игр
# register - регистрация игры
# answer - регистрация второго участника
# start1Player or start2Player - кто из игроков бросает первым ???
# throw1Player - подход первого игрока
# throw2Player - подход второго игрока

?>


<div class="container">
<!-- <div class="currentThrowIcon"></div>
<div class="startPlayerIcon"></div> -->

<?php flash() ?>
</div>
<div class="container">

<?php 
if ($stage == 'list') {
?>

<div>
<h1 class="text-center">Список активных игр</h1>
<div class="h1 small text-muted text-center">за последний час</div>
</div>
<div class="container">

</div>
<div class="container">
<div class="row row-cols-1 row-cols-md-auto align-items-end justify-content-center">
	<!-- <div class="col" hidden>
		<label for="dateValue" class="form-label">Дата игры</label>
		<input class="form-control" id="dateValue" type="date" value="<?php echo $dategame; ?>" />
	</div> -->
	<div class="col">
		<label for="nameValue" class="form-label">Имя игрока</label>
		<input class="form-control" id="nameValue" type="text" value="<?php echo $name; ?>" />
	</div>
	<!-- <div class="col">
		<label for="tagValue" class="form-label">Тэг игры</label>
		<input class="form-control" id="tagValue" type="text" value="<?php echo $tag; ?>" />
	</div> -->
    <div class="col align-bottom justify-items-end">
        <input type="button" class="btn btn-primary btn_click_attr" value="Найти" onclick=setVar()>
    </div>
  </div>
</div>
</br>

<div id="game_list"></div>
<script type="text/javascript">
	let tag = ''
	let name = ''
	let admGames = '<?php echo $admGames; ?>'
	let pageno = '<?php echo $pageno; ?>'
	let dategame = '<?php echo $dategame; ?>'
    let listSet = 'p2p'
	
	getgame()

</script>

<?php 
}
?>


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
                    <label for="username" class="form-label">Ваше имя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID устройства</label>
                    <input type="text" class="form-control" id="guid" name="guid" value="<?php echo gen_password(14);?>" required>
                </div>
                <div class="mb-3">
                    <label for="key" class="form-label">Ключ игры</label>
                    <input type="text" class="form-control" id="key" name="key" value="<?php echo $key;?>" required>
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

    if (is_null($_SESSION['key'])){
        flash('Не выбран ключ игры, или сессия протухла');
        $_SESSION['stage'] = 'list';
        header('Location: /p2p.php');
        die;
    }

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
                <div class="mb-3">
                    <label for="darts" class="form-label">Кол-во дротиков</label>
                    <input type="text" class="form-control" id="darts" name="darts" value="3" required>
                </div>
                <div class="mb-3">
                    <label for="doubleAttempts" class="form-label">Попыток удвоения</label>
                    <input type="text" class="form-control" id="doubleAttempts" name="doubleAttempts" value="0">
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