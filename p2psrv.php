<?php
header("Content-Type: application/json");
require_once __DIR__ . '/inc/boot.php';
// include 'inc/db.php';

$json = file_get_contents("php://input");

// разбираем JSON-строку на составляющие
$data = json_decode($json, true);

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
    $curtime = date("Y-m-d H:i:s");
    $key = gen_password(5);
    $stmt = pdo()->prepare("INSERT INTO `p2p_games` (`gamer1_name`, `guid_gamer1`, `game_type`, `last_update`, `key`, `privateStartGame`) VALUES (:playerName, :guid, :game_type, :curtime, :key, :privateStartGame)");
    $stmt->execute([
        'playerName' => $data['playerName'],
        'guid' => $data['guid'],
        'game_type' => $data['game'],
        'curtime' => $curtime,
        'key' => $key,
        'privateStartGame' => $data['privateStartGame'],
    ]);
    echo $key;
    die;
}

// РЕГИСТАРЦИЯ ВТОРОГО КЛИЕНТА
if ($stage == 'answer') {
    // Проверим на наличие нужной игры
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
    $stmt->execute([
        'key' => $data['key']
    ]);

    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом';
        die;
    }
    $answer = $stmt->fetch(PDO::FETCH_ASSOC);
    // Проверим на возможнсть регистрации в игре
    if ($answer['guid_gamer2']) {
        echo 'error_Другой игрок уже ответил на вызов';
        die;
    }

    // Игра есть и свободна, поэтому зарегистрируем второго участника
    $stmt = pdo()->prepare('UPDATE p2p_games SET `guid_gamer2`=:guid, `gamer2_name`=:playerName WHERE `key`=:key');
    $stmt->execute([
        'guid' => $data['guid'],
        'playerName' => $data['playerName'],
        'key' => $data['key'],
    ]);
    echo $answer['gamer1_name'];
    die;
}

// ОТВЕЧАЕМ НА ЗАПРОСЫ wait участникам

if ($stage == 'wait') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
    $stmt->execute([
        'key' => $data['key']
    ]);
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом за последние сутки';
        die;
    }
    $wait = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($wait['guid'] == '') {
    //     echo 'error_А где GUID?';
    //     die;
    // }

    if ($wait['guid_gamer1'] == $data['guid'] && $wait['guid_gamer2']) { // Если запрос пришел от инициатора игры и известно имя второго игрока, отдаем имя инициатору
        echo $wait['gamer2_name'];
    } elseif ($wait['guid_gamer2'] == $data['guid'] && $wait['gameData']) { // Если запрос от второго игрока, и есть инфа по игре, то отдаём инфу по игре
        echo $wait['gameData'];
    } else {
        echo '';
    }
    die;
}

// Добавляем параметры игры для второго игрока

if ($stage == 'setGame') {
    $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
    $stmt->execute([
        'key' => $data['key']
    ]);
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом за последние сутки';
        die;
    }
    $setGame = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($setGame['game_type'] == 'x01') {
        $arr = [
            "beginGame" => $data['beginGame'],
            "firstToSets" => $data['firstToSets'],
            "firstToLegs" => $data['firstToLegs'],
            "bestOf" => $data['bestOf'],
            "doublesCount" => $data['doublesCount'],
            "startScores1" => $data['startScores1'],
            "startScores2" => $data['startScores2']
        ];
        $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:setGame, `current_throw` = :current_throw, require1 = :startScores1, require2 = :startScores2 WHERE `key`=:key');
        $stmt->execute([
            'setGame' => json_encode($arr),
            'current_throw' => $data['beginGame'],
            'startScores1' => $data['startScores1'],
            'startScores2' => $data['startScores2'],
            'key' => $data['key']
        ]);
    }
    if ($setGame['game_type'] == 'Cricket') {
        $arr = [
            "beginGame" => $data['beginGame'],
            "firstToSets" => $data['firstToSets'],
            "firstToLegs" => $data['firstToLegs'],
            "bestOf" => $data['bestOf'],
            "cricketWithScores" => $data['cricketWithScores'],
        ];
    
        $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:setGame, `current_throw` = :current_throw WHERE `key`=:key');
        $stmt->execute([
            'setGame' => json_encode($arr),
            'current_throw' => $data['beginGame'],
            'key' => $data['key']
        ]);
    }
    echo 'OK';
    die;
}

// РАБОТАЕМ с запросами game 
if ($stage == 'game') {
    // die('OK');
    $stmt = pdo()->prepare("SELECT *, COALESCE(player1, '') AS pl1, COALESCE(player2, '') AS pl2 FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
    $stmt->execute([
        'key' => $data['key']
    ]);
    // Если игры не найдено, выдадим ошибку и прекратим работу скрипта
    if (!$stmt->rowCount()) {
        echo 'error_Не найдено игры с таким ключом за последние сутки';
        die;
    }
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    ///////////////////////////////
    ////// ОБЩЕЕ ДЛЯ УЧАСТНИКОВ
    ///////////////////////////////
    
    // Проверяем очередность хода, если ход другого игрока - отправляем ошибку. 
    if (isset($data['score'])) { // Если пытаемся отправить набор не в свою очередь
        if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 2) {
            echo 'error_Сейчас ход игрока 2';
            die;
        } else if ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 1) {
            echo 'error_Сейчас ход игрока 1';
            die;
        }
    }
    // Завершаем игру, если пришел запрос на окончание игры, пишем полученную статистику по базу.
    if ($game['current_throw'] == 9 && isset($data['endGame']) == true ){
        $stmt = pdo()->prepare('UPDATE p2p_games SET `end_match`= :end_match, `gameData` = :gameData, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2 WHERE `key`=:key');
        $stmt->execute([
            'gameData' => json_encode($data['stat']),
            'end_match' => $data['endGame'],
            'sets1' => $data['setsPlayer1'],
            'legs1' => $data['legsPlayer1'],
            'sets2' => $data['setsPlayer2'],
            'legs2' => $data['legsPlayer2'],
            'key' => $data['key'],
        ]);
        echo 'OK. Игра окончена';
        die;
    }

    // Пришел запрос на удаление до стадии назначения начинающего игрока? Удаляем игру сразу если второй игрок еще не зарегистрирован
    if (isset($data['deleteGame']) === true && is_null($game['gamer2_name'])){
        $stmt = pdo()->prepare("DELETE FROM p2p_games WHERE `key`= ?");
        $stmt->execute([$data['key']]);
        $stmt = pdo()->prepare("DELETE FROM games WHERE `guid`=?")->execute([$data['guid']]);
        echo 'OK';
        die;
    }
    
    // Удаляем игру, если удаление активировано. 
    if ($game['deleteGame'] == 1){
        $arr = [
            'deleteGame' => $game['deleteGame']
        ];
        echo json_encode($arr); // Отправляем команду на удаление игроку
        // Удаляем игру из БД
        $stmt = pdo()->prepare("DELETE FROM p2p_games WHERE `key`= ?");
        $stmt->execute([$data['key']]);
        die;
    }
    
    // Ход первого игрока
    if ($game['guid_gamer1'] == $data['guid']) {
        // Набор очков
        if (isset($data['score'])) {
            // Если пришли настройки игры
            if (isset($data['setGame']) && $game['current_throw'] == 1) {
                if ($data['require'] > 0) // Если в остатке больше чем 0 очков, то передадим ход дальше
                    $cuthrow = 2;
                else $cuthrow = 0; // Иначе переведем в фазу окончания лега
                if ($game['game_type'] == 'x01') {
                    $arr = [
                        'firstToSets' => $data['setGame']['firstToSets'],
                        'firstToLegs' => $data['setGame']['firstToLegs'],
                        'bestOf' => $data['setGame']['bestOf'],
                        'doublesCount' => $data['setGame']['doublesCount']
                    ];
                
                    $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow, `remove`= :remove WHERE `key`=:key');
                    $stmt->execute([
                        'gameData' => json_encode($arr),
                        'setting' => true,
                        'require' => $data['require'],
                        'score' => $data['score'],
                        'darts' => $data['darts'],
                        'doubleAttempts' => $data['doubleAttempts'],
                        'currentThrow' => $cuthrow,
                        'remove' => 0,
                        'key' => $data['key'],
                    ]);
                }
                if ($game['game_type'] == 'Cricket') {
                    $arr = [
                        'firstToSets' => $data['setGame']['firstToSets'],
                        'firstToLegs' => $data['setGame']['firstToLegs'],
                        'bestOf' => $data['setGame']['bestOf'],
                        // 'cricketWithScores' => $data['setGame']['cricketWithScores']
                    ];
                    $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `player1`=:score, `darts1`=:darts, `current_throw` = :currentThrow, `remove`= :remove WHERE `key`=:key');
                    $stmt->execute([
                        'gameData' => json_encode($arr),
                        'setting' => true,
                        'score' => json_encode($data['score']),
                        'darts' => $data['darts'],
                        'currentThrow' => $cuthrow,
                        'remove' => 0,
                        'key' => $data['key'],
                    ]);
                }
                echo 'OK';
                die;
            } 
            // Если пришли очки без настроек игры
            if (!isset($data['setGame'])) {
                if ($game['current_throw'] == 1) { // В свой ход по очереди
                    if ($data['require'] > 0) $cuthrow = 2; // если остаток не равен нулю, то передадим ход
                    else $cuthrow = 0; // если равен, то никому ход не передаём
                    if ($game['game_type'] == 'x01') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                        $stmt->execute([
                            'require' => $data['require'],
                            'score' => $data['score'],
                            'darts' => $data['darts'],
                            'doubleAttempts' => $data['doubleAttempts'],
                            'currentThrow' => $cuthrow,
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET  `player1`=:score, `darts1`=:darts,`current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                        $stmt->execute([
                            'score' => json_encode($data['score']),
                            'darts' => $data['darts'],
                            'currentThrow' => $cuthrow,
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    echo 'OK';
                    die;
                } elseif ($game['current_throw'] == 9) { // Начало нового лега
                    if ($data['require'] <= 0) die('error_Остаток очков 0 или меньше'); // Проверяем передаваемый остаток на положительное число.
                    $cuthrow = 2; // передадим ход второму игроку
                    // Проверяем, что необходимые данные переданы
                    if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
                        echo 'error_Нет данных о счете между игроками';
                        die('error_Нет данных о счете между игроками');
                    }                               
                    // подготовленный запрос для нового лега с данными о сетах/легах
                    if ($game['game_type'] == 'x01') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2, `remove`=:remove WHERE `key`=:key');
                        $stmt->execute([
                            'require' => $data['require'],
                            'score' => $data['score'],
                            'darts' => $data['darts'],
                            'doubleAttempts' => $data['doubleAttempts'],
                            'currentThrow' => $cuthrow,
                            'sets1' => $data['setsPlayer1'],
                            'legs1' => $data['legsPlayer1'],
                            'sets2' => $data['setsPlayer2'],
                            'legs2' => $data['legsPlayer2'],
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `player1`=:score, `darts1`=:darts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2, `remove`=:remove WHERE `key`=:key');
                        $stmt->execute([
                            'score' => json_encode($data['score']),
                            'darts' => $data['darts'],
                            'currentThrow' => $cuthrow,
                            'sets1' => $data['setsPlayer1'],
                            'legs1' => $data['legsPlayer1'],
                            'sets2' => $data['setsPlayer2'],
                            'legs2' => $data['legsPlayer2'],
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    echo 'OK';
                    die;
                } 
            }
        } if (!isset($data['score'])) { // Если в запросе нет информации о наборе очков
            
            // Помечаем игру на удаление, если пришел запрос, и удаляем сразу из БД, если нет второго игрока. 
            if (isset($data['deleteGame']) == true){
                if ($game['current_throw'] != 1) {
                    die('error_Невозможно удалить игру в ход соперника.');
                }
                if ($game['deleteGame'] == 1) {
                echo 'error_Удаление игры уже активировано другим игроком';
                die;
                }
                if ($game['end_match'] == 1) {
                    die('error_Удаление завершенной игры невозможно');
                }
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ?, `deleteGame` = ? WHERE `key`= ?");
                $stmt->execute([2, 1, $data['key']]);
                echo 'OK';
                die;
            }
            
            
            // Переводим в новый лег, если соперник закрылся
            if ($game['require2'] == 0 && $game['current_throw'] == 0) { 
                if ($game['game_type'] == 'x01') {
                    $arr = [
                        'score' => $game['score2'],
                        'darts' => $game['darts2'],
                        'doubleAttempts' => $game['doubleAttempts2']
                    ];
                }
                if ($game['game_type'] == 'Cricket') {
                    $arr = [
                        'score' => json_decode($game['player2']),
                        'darts' => $game['darts2'],
                    ];
                }
                echo json_encode($arr); // передаём набор второго игрока
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ? WHERE `key`= ?");
                $stmt->execute([9, $data['key']]);
                die;
            }

            // Возврат хода
            if ($game['remove'] == true && $game['current_throw'] == 1){
                $arr = [
                    'remove' => $game['remove']
                ];
                echo json_encode($arr); 
                die;
            }

            if (isset($data['remove']) == true) {
                if ($game['remove'] == true) {
                    echo 'error_Отмена хода уже активирована, жду набор';
                    die;
                }
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ?, `remove` = ? WHERE `key`= ?");
                $stmt->execute([2, 1, $data['key']]);
                echo 'OK';
                die;
            }
            
            // Отправляем набор очков соперника
            if ($game['current_throw'] == 1){
                if ($game['game_type'] == 'x01') {
                    $arr = [
                        'score' => $game['score2'],
                        'darts' => $game['darts2'],
                        'doubleAttempts' => $game['doubleAttempts2']
                    ];
                }
                if ($game['game_type'] == 'Cricket') {
                    $arr = [
                        'score' => json_decode($game['player2']),
                        'darts' => $game['darts2'],
                    ];
                }
                echo json_encode($arr);
                die;
            }
        }
    }

    // Ход второго игрока
    if ($game['guid_gamer2'] == $data['guid']) {
        // Набор очков
        if (isset($data['score'])) {
            // Если пришли настройки игры
            if (isset($data['setGame']) && $game['current_throw'] == 2) {
                echo 'error_Настройки может изменить только игрок начинающий игру';
                die;
            } 
            // Если пришли очки без настроек игры
            if (!isset($data['setGame'])) {
                if ($game['current_throw'] == 2) { // В свой ход по очереди
                    if ($data['require'] > 0) $cuthrow = 1; // если остаток не равен нулю, то передадим ход
                    else $cuthrow = 0; // если равен, то никому ход не передаём
                    if ($game['game_type'] == 'x01') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                        $stmt->execute([
                            'require' => $data['require'],
                            'score' => $data['score'],
                            'darts' => $data['darts'],
                            'doubleAttempts' => $data['doubleAttempts'],
                            'currentThrow' => $cuthrow,
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `player2`=:score, `darts2`=:darts, `current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                        $stmt->execute([
                            'score' => json_encode($data['score']),
                            'darts' => $data['darts'],
                            'currentThrow' => $cuthrow,
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    echo 'OK';
                    die;
                } elseif ($game['current_throw'] == 9) { // Начало нового лега
                    if ($data['require'] <= 0) die('error_Остаток очков 0 или меньше'); // Проверяем передаваемый остаток на положительное число.
                    $cuthrow = 1; // передадим ход первому игроку
                    // Проверяем, что необходимые данные переданы
                    if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
                        echo 'error_Нет данных о счете между игроками';
                        die('error_Нет данных о счете между игроками');
                    }                               
                    // подготовленный запрос для нового лега с данными о сетах/легах
                    if ($game['game_type'] == 'x01') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2, `remove`=:remove WHERE `key`=:key');
                        $stmt->execute([
                            'require' => $data['require'],
                            'score' => $data['score'],
                            'darts' => $data['darts'],
                            'doubleAttempts' => $data['doubleAttempts'],
                            'currentThrow' => $cuthrow,
                            'sets1' => $data['setsPlayer1'],
                            'legs1' => $data['legsPlayer1'],
                            'sets2' => $data['setsPlayer2'],
                            'legs2' => $data['legsPlayer2'],
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $stmt = pdo()->prepare('UPDATE p2p_games SET `player2`=:score, `darts2`=:darts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2, `remove`=:remove WHERE `key`=:key');
                        $stmt->execute([
                            'score' => json_encode($data['score']),
                            'darts' => $data['darts'],
                            'currentThrow' => $cuthrow,
                            'sets1' => $data['setsPlayer1'],
                            'legs1' => $data['legsPlayer1'],
                            'sets2' => $data['setsPlayer2'],
                            'legs2' => $data['legsPlayer2'],
                            'remove' => 0,
                            'key' => $data['key'],
                        ]);
                    }
                    echo 'OK';
                    die;
                } 
            }
        } if (!isset($data['score'])) { // Если в запросе нет информации о наборе очков
           
            // Помечаем игру на удаление. 
            if (isset($data['deleteGame']) === true){
                if ($game['current_throw'] != 2) {
                    die('error_Невозможно удалить игру в ход соперника.');
                }
                if ($game['deleteGame'] == 1) {
                echo 'error_Удаление игры уже активировано другим игроком';
                die;
                }
                if ($game['end_match'] == 1) {
                    die('error_Удаление завершенной игры невозможно');
                }
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ?, `deleteGame` = ? WHERE `key`= ?");
                $stmt->execute([1, 1, $data['key']]);
                echo 'OK';
                die;
            }

            // Переводим в новый лег, если соперник закрылся
            if ($game['require1'] == 0 && $game['current_throw'] == 0) { 
                if ($game['game_type'] == 'x01') {
                    $arr = [
                        'score' => $game['score1'],
                        'darts' => $game['darts1'],
                        'doubleAttempts' => $game['doubleAttempts1']
                    ];
                }
                if ($game['game_type'] == 'Cricket') {
                    $arr = [
                        'score' => json_decode($game['player1']),
                        'darts' => $game['darts1'],
                    ];
                }
                echo json_encode($arr); // передаём набор второго игрока
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ? WHERE `key`= ?");
                $stmt->execute([9, $data['key']]);
                die;
            }

            // Возврат хода
            if ($game['remove'] == true && $game['current_throw'] == 2){
                $arr = [
                    'remove' => $game['remove']
                ];
                echo json_encode($arr); 
                die;
            }

            if (isset($data['remove']) == true) {
                if ($game['remove'] == true) {
                    echo 'error_Отмена хода уже активирована, жду набор';
                    die;
                }
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ?, `remove` = ? WHERE `key`= ?");
                $stmt->execute([1, 1, $data['key']]);
                echo 'OK';
                die;
            }
            if ($game['current_throw'] == 2){
                if ($game['setGame'] != 1) {
                    if ($game['game_type'] == 'x01') {
                        $arr = [
                             'score' => $game['score1'],
                             'darts' => $game['darts1'],
                             'doubleAttempts' => $game['doubleAttempts1']
                        ];
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $arr = [
                             'score' => json_decode($game['player1']),
                             'darts' => $game['darts1'],
                        ];
                    }
                 } else {
                    if ($game['game_type'] == 'x01') {
                        $arr = [
                            'score' => $game['score1'],
                            'darts' => $game['darts1'],
                            'doubleAttempts' => $game['doubleAttempts1'],
                            'setGame' => json_decode($game['gameData'])
                        ];
                    }
                    if ($game['game_type'] == 'Cricket') {
                        $arr = [
                            'score' => json_decode($game['player1']),
                            'darts' => $game['darts1'],
                            'setGame' => json_decode($game['gameData'])
                        ];
                    }
                 }
                 echo json_encode($arr);
                 $stmt = pdo()->prepare("UPDATE p2p_games SET `setGame` = ? WHERE `key`= ?");
                 $stmt->execute([0, $data['key']]);
                 die;
            }
        }
    }

}