<?php
include 'template/header.php';
include 'inc/db.php';
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
            Отсканируйте QR код указанный ниже
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
include 'template/footer.php';
?>