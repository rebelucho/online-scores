<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Darts28.ru: Score System</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <link rel="stylesheet" href="/css/main.css">

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
  </head>
  <body>
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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            P2P
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="p2p.php?stage=list">Список игр</a></li>
            <li><a class="dropdown-item" href="p2p.php?stage=answer">Ответить на вызов</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="p2p.php?stage=videoReg">Видео P2P</a></li>
          </ul>
        </li>
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
