<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';

$user = null;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<?php if ($user) { ?>
  <div class="container">
    <h1>Привет, <?=htmlspecialchars($user['username'])?>!</h1>

    <form class="mt-5" method="post" action="do_logout.php">
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>
  </div>
<?php } else { ?>
<div class="container">
  <div class="row text-center justify-content-center align-items-center g-2">
    <h1 class="mb-3">Регистрация</h1>
  </div>
<?php flash(); ?>
  <div class="row justify-content-center align-items-center g-2">
    <div class="col-md-3"></div>
      <div class="col-12 col-md-6">
        <form method="post" action="do_register.php">
          <div class="mb-3">
            <label for="username" class="form-label">Имя</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">E-MAIL</label>
            <input type="text" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
        </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</div>
<?php } ?>

<?php
require_once __DIR__.'/template/footer.php';
?>