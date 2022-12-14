<?php
require_once __DIR__.'/inc/boot.php';

require_once __DIR__.'/template/header.php';


$addpage = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'On') ? "https" : "http") 
    . "://$_SERVER[HTTP_HOST]"."/add.php";

?>


<!-- <div class="container">
<h1>Список трансляций</h1>

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
</div> -->

<?php
$scriptName = trim($_SERVER["SCRIPT_NAME"], "/");
$stmt = pdo()->prepare("SELECT * FROM `articles` WHERE `page` = :scriptName");
$stmt->execute(['scriptName' => $scriptName]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$content = sprintf($result['content'], $addpage);

echo $content;


require_once __DIR__.'/template/footer.php';
?>