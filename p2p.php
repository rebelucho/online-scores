<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';
$id = $_GET["id"];
?>

<div class="container">
<?php flash() ?>
</div>
<div class="container">

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
                    <label for="username" class="form-label">Ваше Имя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="guid" class="form-label">GUID</label>
                    <input type="guid" class="form-control" id="guid" name="guid" required>
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