<?php
require_once __DIR__.'/inc/boot.php';

// Проверим GUID на уникальность
if ($_SESSION["stage"] == 'register') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `guid_gamer1` = :guid");
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
    $stmt = pdo()->prepare("INSERT INTO `p2p_games` (`gamer1_name`, `guid_gamer1`, `require1`, `require2`, `key`) VALUES (:gamer1_name, :guid, :scores1, :scores2, :key)");
    $stmt->execute([
        'gamer1_name' => $_POST['username'],
        'guid' => $_POST['guid'],
        'scores1' => $_POST['scores'],
        'scores2' => $_POST['scores'],
        'key' => $key,
    ]);
    $_SESSION["stage"] = 'answer';
    flash('Ключ игры: '.$key);
    header('Location: /p2p.php');
    die;
}

if ($_SESSION["stage"] == 'answer') {
    // Проверяем наличие игры с таким ключем
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_POST['key']]);
    if (!$stmt->rowCount()) {
        flash('Игры с таким ключом не найдено!');
        header('Location: p2p.php');
        die;
    }
    // $game = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // $guid2 = $_POST['guid'];
    // $username2 = $_POST['username'];
    // $key2 = $_POST['key'];
    
    $stmt = pdo()->prepare('UPDATE p2p_games SET `guid_gamer2`=:guid, `gamer2_name`=:username WHERE `key`=:key');
    $stmt->execute([
      'guid' => $_POST['guid'],
      'username' => $_POST['username'],
      'key' => $_POST['key'],
    ]);
       $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_POST['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    flash('Да начнется игра! Между '. $game['gamer1_name'] .' и '. $game['gamer2_name']);
    $_SESSION["key"] = $_POST['key'];
    $_SESSION["stage"] = 'throw1Player';
    header('Location: p2p.php');
    die;
}

if ($_SESSION["stage"] == 'throw1Player') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    $require = $game['require1'] - $_POST['scores'];
    $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require1 WHERE `key`=:key');
    $stmt->execute([
        'require1' => $require,
        'key' => $_SESSION['key'],
      ]);
      $_SESSION["stage"] = 'throw2Player';
      flash('Игрок 1 набрал '. $_POST['scores'] .', бросает игрок 2');
      header('Location: p2p.php');
      die;
}

if ($_SESSION["stage"] == 'throw2Player') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute(['key' => $_SESSION['key']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    $require = $game['require2'] - $_POST['scores'];
    $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require2 WHERE `key`=:key');
    $stmt->execute([
        'require2' => $require,
        'key' => $_SESSION['key'],
      ]);
      $_SESSION["stage"] = 'throw1Player';
      flash('Игрок 2 набрал '. $_POST['scores'] .', бросает игрок 1');
      header('Location: p2p.php');
      die;
}