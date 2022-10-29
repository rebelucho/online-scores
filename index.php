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
?>
<?php 
if ($user['role'] == 1 ) { 
// показываем меню для админа
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
	<a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#addpage">Добавить страницу</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#editpage">Редактировать</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#deletepage">Удалить страницу</a>
        </li>
      </ul>
    </div>
	<form class="d-flex" method="post" action="do_logout.php">
	<span class="navbar-text">
      Welcome back, <?=htmlspecialchars($user['username'])?>!  
	</span>
      <button type="submit" class="btn btn-outline-success me-2">Выйти</button>
    </form>
  </div>
</nav>

<?php }


function curdate() {
    return date('Y-m-d');
}

require_once __DIR__.'/template/header.tpl';

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
	let dategame = '<?php echo $dategame; ?>'
	getgame()
</script>

<?php
require_once('template/footer.tpl');
?>
