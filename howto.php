<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'header.html';


$addpage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'On' ? "https" : "http") 
    . "://$_SERVER[HTTP_HOST]"."/add.php";

?>

<div class="container">
<h1>Список трансляций</h1>
</div>
<div class="container">
    <p>Для подключения вашего устрйства с установленным ПО "Дартс база" необходимо:</p>
    <ul>
        <li>
            Откройте приложение "Дартс база"
        </li>
        <li>
            Нажмите кнопку "Логин"        
        </li>
        <li>
            Нажмите "Показать дополнительные настройки" 
        </li>
        <li>
            В разделе "Собственный графический сервис" нажмите на изображение QR кода
        </li>
        <li>
            Отсканируйте QR код указанный ниже, либо введите адрес вручную: <?php echo $addpage; ?>
        </li>
        <li>
            Проверьте работоспособность сервиса с помощью кнопки "проверить"
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
require_once __DIR__.'/template/footer.html';
?>