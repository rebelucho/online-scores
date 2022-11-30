<?php
header("Content-Type: application/json");
require_once __DIR__.'/inc/boot.php';
// include 'inc/db.php';

$json = file_get_contents("php://input");

// разбираем JSON-строку на составляющие
$data = json_decode($json,true);

if (isset($data['stage']))
    $stage = $data['stage']; 
else {
    echo 'error_Not required Stage';
    die;
}

// РЕГИСТРАЦИЯ ИГРЫ. 
// Проверим GUID на уникальность
if ($stage == 'register') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `guid_gamer1` = :guid");
    $stmt->execute([
        'guid' => $data['guid']
    ]);

    if ($stmt->rowCount() > 0) {
        echo 'error_Такой GUID уже зарегистрирован в системе и ключ игры выдан';
        die; // Остановка выполнения скрипта
    }

    // Запоминаем GUID, выдаём ключ игры 
    $key = gen_password(5);
    $stmt = pdo()->prepare("INSERT INTO `p2p_games` (`gamer1_name`, `guid_gamer1`, `key`) VALUES (:playerName, :guid, :key)");
    $stmt->execute([
        'playerName' => $data['playerName'],
        'guid' => $data['guid'],
        'key' => $key,
    ]);
    echo $key;
    die;
}

// РЕГИСТАРЦИЯ ВТОРОГО КЛИЕНТА
if ($stage == 'answer') {
    // Проверим на наличие нужной игры
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute([
        'key' => $data['key']
    ]);
    
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом, ';
        die;
    }
    $answer = $stmt->fetch(PDO::FETCH_ASSOC);
    // Игра есть, поэтому зарегистрируем второго участника
    $stmt = pdo()->prepare('UPDATE p2p_games SET `guid_gamer2`=:guid, `gamer2_name`=:playerName WHERE `key`=:key');
    $stmt->execute([
      'guid' => $data['guid'],
      'playerName' => $data['playerName'],
      'key' => $data['key'],
    ]);   
    echo $answer['gamer1_name'];
    die;
}

// ОТВЕЧАЕМ НА ЗАПРОСЫ wait обоим участникам

if ($stage == 'wait') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute([
        'key' => $data['key']
    ]);
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом, ';
        die;
    }
    $wait = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($wait['guid'] == '') {
    //     echo 'error_А где GUID?';
    //     die;
    // }

    if ($wait['guid_gamer1'] == $data['guid'] && $wait['guid_gamer2']){ // Если запрос пришел от инициатора игры и известно имя второго игрока, отдаем имя инициатору
        echo $wait['gamer2_name'];

    } elseif ($wait['guid_gamer2'] == $data['guid'] && $wait['gameData']){ // Если запрос от второго игрока, и есть инфа по игре, то отдаём фину по игре
        echo $wait['gameData'];
    } else {
        echo '';
    }
    die;
}

// Добавляем параметры игры для второго игрока

if ($stage == 'setGame') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
    $stmt->execute([
        'key' => $data['key']
    ]);
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом, ';
        die;
    }
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $arr = [
        "beginGame" => $data['beginGame'], 
        "firstToSets" => $data['firstToSets'], 
        "firstToLegs" => $data['firstToLegs'], 
        "bestOf" => $data['bestOf'], 
        "doublesCount" => $data['doublesCount'], 
        "startScores1" => $data['startScores1'], 
        "startScores2" => $data['startScores2'] 
    ];

    $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:setGame, require1 = :startScores1, require2 = :startScores2 WHERE `key`=:key');
            $stmt->execute([
            'setGame' => json_encode($arr),
            'startScores1' => $data['startScores1'], 
            'startScores2' => $data['startScores2'],
            'key' => $data['key']
            ]);   
}

// РАБОТАЕМ с запросами game с обоими участникам

if ($stage == 'game') {
    if (isset($data['score']) && !isset($data['gameData'])) {
        $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
        $stmt->execute([
            'key' => $data['key']
        ]);
        if (!$stmt->rowCount()) {
            echo 'error_Не найдено игры с таким ключом, ';
            die;
        }
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($game['guid_gamer1'] == $data['guid']){
            if ($data['require'] > 0) $cuthrow = 2; else $cuthrow = 0;
            $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow WHERE `key`=:key');
            $stmt->execute([
              'require' => $data['require'],
              'score' => $data['score'],
              'darts' => $data['darts'],
              'doubleAttempts' => $data['doubleAttempts'],
              'currentThrow' => $cuthrow,
              'key' => $data['key'],
            ]);   
        } else {
            if ($data['require'] > 0) $cuthrow = 1; else $cuthrow = 0;
            $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `current_throw` = :currentThrow WHERE `key`=:key');
            $stmt->execute([
              'require' => $data['require'],
              'score' => $data['score'],
              'darts' => $data['darts'],
              'doubleAttempts' => $data['doubleAttempts'],
              'currentThrow' => $cuthrow,
              'key' => $data['key'],
            ]);   
        }
    }
    if (!isset($data['score'])) {
        $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key");
        $stmt->execute([
            'key' => $data['key']
        ]);
        if (!$stmt->rowCount()) {
            echo 'error_Не найдено игры с таким ключом, ';
            die;
        }
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 1){
            $arr = [
                'score' => $game['score2'],
                'darts' => $game['darts2'],
                'doubleAttempts' => $game['doubleAttempts2']
            ];
            echo json_encode($arr);
        } elseif ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 2){
            $arr = [
                'score' => $game['score1'],
                'darts' => $game['darts1'],
                'doubleAttempts' => $game['doubleAttempts1']
            ];
            echo json_encode($arr);
        } elseif ($game['require2'] == 0 && $game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 0) {
            $arr = [
                'score' => $game['score2'],
                'darts' => $game['darts2'],
                'doubleAttempts' => $game['doubleAttempts2']
            ];
            echo json_encode($arr);

        }elseif ($game['require1'] == 0 && $game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 0) {
            $arr = [
                'score' => $game['score1'],
                'darts' => $game['darts1'],
                'doubleAttempts' => $game['doubleAttempts1']
            ];
            echo json_encode($arr);
            
        }
        
    }
}