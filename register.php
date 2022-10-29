<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.tpl';

$user = null;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<?php if ($user) { ?>

    <h1>Привет, <?=htmlspecialchars($user['username'])?>!</h1>

    <form class="mt-5" method="post" action="do_logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>

<?php } else { ?>
<div class="container">
<h1 class="mb-5">Регистрация</h1>

<?php flash(); ?>

<form method="post" action="do_register.php">
  <div class="mb-3">
    <label for="username" class="form-label">Имя</label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Пароль</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
</form>
</div>
<?php } ?>