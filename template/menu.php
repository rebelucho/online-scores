<?php 
if (check_auth()) {
  // Получим данные пользователя по сохранённому идентификатору
  $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
  $stmt->execute(['id' => $_SESSION['user_id']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<header class="header">
      <div class="container header-container">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/"><img src="/img/apple-touch-icon.png" width="40px" height="40px"> Online Scores</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link" href="/">Текущие игры</a>
        <a class="nav-link" href="/howto.php">Как добавить устройство</a>  
        <a class="nav-link" href="https://darts28.ru" target="_blank">darts28.ru</a>
      
<?php 
if (isset($user['role'])) { // проверяем переменную на наличие данных
  if ($user['role'] == 1 ) { // Если роль 1 (Админ), то показываем меню Администрирования 
?>    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Admin</a>
        <ul class="dropdown-menu">
          <li><a class="nav-link" href="/admin.php?admUsers=true">Редактировать пользователей</a></li>
          <li><a class="nav-link" href="/index.php?admGames=true">Редактировать игры</a></li>
        </ul>
      </li>
<?php
  }
}
?> 
      </div>
    </div>
<?php
if (isset($user['id'])) { 
?>
    <form class="d-flex" method="post" action="do_logout.php">
	  <span class="navbar-text">
        Welcome back, <?=htmlspecialchars($user['username'])?>!  
	  </span>
        <button type="submit" class="btn btn-outline-success me-2">Выйти</button>
      </form>
    </div>
<?php
  } else {
?>
    <form class="d-flex" method="post" action="login.php">
      <button type="submit" class="btn btn-outline-success me-2" id="loginButton">Вход</button>
    </form>
<?php 
  }
?>

  </div>
</nav>
</div>
</header>