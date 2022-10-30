<?php
require_once __DIR__.'/inc/boot.php';
require_once('template/header.tpl');


$addpage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
    . "://$_SERVER[HTTP_HOST]"."/add.php";

?>

<div class="container">
<h1>Как подключить приложение к сервису</h1>
</div>
<div class="container">
    <p>Для подключения вашего устрйства с установленным ПО "Дартс база" необходимо:</p>
    <ul>
        <li>
            Открыть приложение "Дартс база"
        </li>
        <li>
            Нажать кнопку "Логин"        
        </li>
        <li>
            Нажать "Показать дополнительные настройки" 
        </li>
        <li>
            В разделе "Собственный графический сервис" нажать на изображение QR кода
        </li>
        <li>
            Отсканировать QR код указанный ниже, либо ввести адрес вручную: <?php echo $addpage; ?>
        </li>
        <li>
            Проверить работоспособность сервиса с помощью кнопки "проверить"
        </li>
    </ul>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <img class="img-fluid" src="/img/qr.jpg">
        </div>
    </div>
</div>

<?php
require_once('template/footer.tpl');
?>
