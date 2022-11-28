<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.php';



if (isset($_REQUEST["admUsers"]) {
    $admUsers = $_REQUEST["admUsers"];
} else {
    $admUsers = false;
}

if (isset($_REQUEST["admGames"]) {
    $admGames = $_REQUEST["admGames"];
} else {
    $admGames = false;
}


// Администрирование пользователей

// Форма поиска пользователей

// Получаем пользователей из базы

// Выводим список попавших под поисковый запрос

// Редактируем пользователя 

// Отправляем изменения в БД





require_once __DIR__.'/template/footer.php';
?>