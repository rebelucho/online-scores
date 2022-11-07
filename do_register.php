<?php

require_once __DIR__.'/inc/boot.php';

// Проверим, не занято ли имя пользователя
$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `username` = :username OR `email` = :email");
$stmt->execute([
    'username' => $_POST['username'],
    'email' => $_POST['email']
]);
if ($stmt->rowCount() > 0) {
    flash('Это имя или адрес электронной почты пользователя уже заняты.');
    header('Location: /register.php'); // Возврат на форму регистрации
    die; // Остановка выполнения скрипта
}

// Добавим пользователя в базу
$stmt = pdo()->prepare("INSERT INTO `users` (`username`, `email`, `password`) VALUES (:username, :email, :password)");
$stmt->execute([
    'username' => $_POST['username'],
    'email' => $_POST['email'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
]);

header('Location: login.php');