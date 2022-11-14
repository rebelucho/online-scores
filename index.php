<?php
require_once __DIR__.'/inc/boot.php';
// session_start();

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

require_once __DIR__.'/template/header.php';

flash();

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


// echo the date to screen
?>
<div>
<h1 class="text-center">Список трансляций</h1>
</div>
<div class="container">

</div>
<div class="container">
<div class="row row-cols-1 row-cols-md-auto align-items-end justify-content-center">
	<div class="col">
		<label for="dateValue" class="form-label">Дата игры</label>
		<input class="form-control" id="dateValue" type="date" value="<?php echo $dategame; ?>" />
	</div>
	<div class="col">
		<label for="nameValue" class="form-label">Имя игрока</label>
		<input class="form-control" id="nameValue" type="text" value="<?php echo $name; ?>" />
	</div>
	<div class="col">
		<label for="tagValue" class="form-label">Тэг игры</label>
		<input class="form-control" id="tagValue" type="text" value="<?php echo $tag; ?>" />
	</div>
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
	
	getgame()

</script>

<?php
require_once __DIR__.'/template/footer.php';
?>
