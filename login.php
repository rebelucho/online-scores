<?php

require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.tpl';

if (check_auth()) {
    header('Location: /');
    die;
}
?>
<div class="container">


<h1 class="mb-5">Login</h1>

<?php flash() ?>

<form method="post" action="do_login.php">
    <div class="mb-3">
        <label for="username" class="form-label">Имя</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Войти</button>
        <a class="btn btn-outline-primary" href="index1.php">Регистрация</a>
    </div>
</form>
</div>