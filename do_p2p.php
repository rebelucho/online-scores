<?php
require_once __DIR__.'/inc/boot.php';

// Проверим GUID на уникальность

$stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `guid` = :guid");
$stmt->execute([
    'guid' => $_POST['guid']
]);

if ($stmt->rowCount() > 0) {
    flash('Такой GUID уже зарегистрирован в системе и ключ игры выдан');
    header('Location: /p2p.php'); // Возврат на форму регистрации игры
    die; // Остановка выполнения скрипта
}

// Запоминаем GUID, выдаём ключ игры 
// $key = gen_password(6);
$key = gen_password(5);
// $key = '112233';
$stmt = pdo()->prepare("INSERT INTO `p2p_games` (`gamer1_name`, `guid`, `key`) VALUES (:gamer1_name, :guid, :key)");
$stmt->execute([
    'gamer1_name' => $_POST['username'],
    'guid' => $_POST['guid'],
    'key' => $key,
]);

flash('Ключ игры: '.$key);
header('Location: /p2p.php');
