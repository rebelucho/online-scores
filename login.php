<?php

require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.html';

if (check_auth()) {
    header('Location: /');
    die;
}
?>
<div class="container">
<?php flash() ?>
</div>
<div class="container">

<div class="row text-center justify-content-center align-items-center g-2">
    <h1 class="mb-3">Авторизация</h1>
</div>
</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
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
                    <a class="btn btn-outline-primary" href="register.php">Регистрация</a>
                </div>
            </form>
            </br>
            <p>Забыли пароль? Можно <a href="lostpass.php">тут</a> его восстановить.</p>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<div class="container p-3" style="width:390px">


</div>
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
    
    </div>
</div>

<?php
require_once __DIR__.'/template/footer.html';
?>