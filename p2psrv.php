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
    $stmt = pdo()->prepare("INSERT INTO `p2p_games` (`gamer1_name`, `guid_gamer1`, `game_type`, `last_update`, `key`) VALUES (:playerName, :guid, :game_type, :curtime, :key)");
    $stmt->execute([
        'playerName' => $data['playerName'],
        'guid' => $data['guid'],
        'game_type' => $data['game'],
        'curtime' => $curtime,
        'key' => $key,
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

// ОТВЕЧАЕМ НА ЗАПРОСЫ wait обоим участникам

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
    } elseif ($wait['guid_gamer2'] == $data['guid'] && $wait['gameData']) { // Если запрос от второго игрока, и есть инфа по игре, то отдаём фину по игре
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

    $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:setGame, `current_throw` = :current_throw, require1 = :startScores1, require2 = :startScores2 WHERE `key`=:key');
    $stmt->execute([
        'setGame' => json_encode($arr),
        'current_throw' => $data['beginGame'],
        'startScores1' => $data['startScores1'],
        'startScores2' => $data['startScores2'],
        'key' => $data['key']
    ]);
    echo 'OK';
    die;
}

// РАБОТАЕМ с запросами game с обоими участникам

if ($stage == 'game') {
    if (isset($data['score'])) { // Если в дате пришёл набор, то запишем этот набор в базу и передадим ход второму игроку
        $stmt = pdo()->prepare("SELECT *, COALESCE(player1, '') AS pl1, COALESCE(player2, '') AS pl2 FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
        $stmt->execute([
            'key' => $data['key']
        ]);
        if (!$stmt->rowCount()) {
            echo 'error_Не найдено игры с таким ключом за последние сутки';
            die;
        }
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 2) {
            echo 'error_Сейчас ход игрока 2';
            die;
        } else if ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 1) {
            echo 'error_Сейчас ход игрока 1';
            die;
        }
        if (!isset($data['setGame'])) { // если в дате нет установок игры, то передаем:
            if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 1) { // Узнаём кто прислал и проверяем на окончание лега
                if ($data['require'] > 0) $cuthrow = 2; // если остаток не равен нулю, то передадим ход
                else $cuthrow = 0; // если равен, то никому ход не передаём
                // Обрабатываем добавление максимальных наборов в оперативный доступ
                $player = json_decode($game['player1'],true);
                if ($data['score'] >= 100) {
                    $player['max'][] = $data['score']; // добавляем очки в массив максимумов
                    $playerMax = $player['max'];
                }  else if (!$game['pl1']) {
                    $playerMax = null;
                } else {
                    $playerMax = $player['max'];
                }
                $player = [
                    'max' => $playerMax
                ];
                // if (isset($game['remove']) == true) {
                //     $remove = 0;
                // }
                $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `player1` =:player1, `current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                $stmt->execute([
                    'require' => $data['require'],
                    'score' => $data['score'],
                    'darts' => $data['darts'],
                    'doubleAttempts' => $data['doubleAttempts'],
                    'currentThrow' => $cuthrow,
                    'remove' => 0,
                    'player1' => json_encode($player),
                    'key' => $data['key'],
                ]);
                echo 'OK';
                die;
            } elseif ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 2) {
                if ($data['require'] > 0) $cuthrow = 1;
                else $cuthrow = 0;
                // Обрабатываем добавление максимальных наборов в оперативный доступ
                $player = json_decode($game['player2'],true);
                if ($data['score'] >= 100) {
                    $player['max'][] = $data['score']; // добавляем очки в массив максимумов
                    $playerMax = $player['max'];
                }  else if (!$game['pl2']) {
                    $playerMax = null;
                } else {
                    $playerMax = $player['max'];
                }
                $player = [
                    'max' => $playerMax
                ];
                $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `player2` =:player2, `current_throw` = :currentThrow, `remove` = :remove WHERE `key`=:key');
                $stmt->execute([
                    'require' => $data['require'],
                    'score' => $data['score'],
                    'darts' => $data['darts'],
                    'doubleAttempts' => $data['doubleAttempts'],
                    'currentThrow' => $cuthrow,
                    'remove' => 0,
                    'player2' => json_encode($player),
                    'key' => $data['key'],
                ]);
                echo 'OK';
                die;
            } elseif ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 9 && $data['require'] > 0 ){
                $cuthrow = 2; // если остаток не равен нулю, то передадим ход
                // Проверяем, что необходимые данные переданы
                if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
                    echo 'error_Нет данных о счете между игроками';
                    die;
                }                
                // Обрабатываем добавление максимальных наборов в оперативный доступ
                $player = json_decode($game['player1'],true);
                if ($data['score'] >= 100) {
                    $player['max'][] = $data['score']; // добавляем очки в массив максимумов
                    $playerMax = $player['max'];
                }  else if (!$game['pl1']) {
                    $playerMax = null;
                } else {
                    $playerMax = $player['max'];
                }
                $player = [
                    'max' => $playerMax
                ];
                // подготовленный запрос для нового лега с данными о сетах/легах
                $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `player1` =:player1, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2 WHERE `key`=:key');
                // пока действующий запрос
                // $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `player1` =:player1, `current_throw` = :currentThrow WHERE `key`=:key');
                $stmt->execute([
                    'require' => $data['require'],
                    'score' => $data['score'],
                    'darts' => $data['darts'],
                    'doubleAttempts' => $data['doubleAttempts'],
                    'player1' => json_encode($player),
                    'currentThrow' => $cuthrow,
                    'sets1' => $data['setsPlayer1'],
                    'legs1' => $data['legsPlayer1'],
                    'sets2' => $data['setsPlayer2'],
                    'legs2' => $data['legsPlayer2'],
                    'key' => $data['key'],
                ]);
                echo 'OK';
                die;
            } elseif ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 9 && $data['require'] > 0 ){
                $cuthrow = 1;
                // Проверяем, что необходимые данные переданы
                if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
                    echo 'error_Нет данных о счете между игроками';
                    die;
                }
                // Обрабатываем добавление максимальных наборов в оперативный доступ
                $player = json_decode($game['player2'],true);
                if ($data['score'] >= 100) {
                    $player['max'][] = $data['score']; // добавляем очки в массив максимумов
                    $playerMax = $player['max'];
                }  else if (!$game['pl2']) {
                    $playerMax = null;
                } else {
                    $playerMax = $player['max'];
                }
                $player = [
                    'max' => $playerMax
                ];
                // подготовленный запрос для нового лега с данными о сетах/легах
                $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `player2` = :player2, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2 WHERE `key`=:key');
                // пока действующий запрос
                // $stmt = pdo()->prepare('UPDATE p2p_games SET `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `player2` = :player2, `current_throw` = :currentThrow WHERE `key`=:key');
                $stmt->execute([
                    'require' => $data['require'],
                    'score' => $data['score'],
                    'darts' => $data['darts'],
                    'doubleAttempts' => $data['doubleAttempts'],
                    'player2' => json_encode($player),
                    'currentThrow' => $cuthrow,
                    'sets1' => $data['setsPlayer1'],
                    'legs1' => $data['legsPlayer1'],
                    'sets2' => $data['setsPlayer2'],
                    'legs2' => $data['legsPlayer2'],
                    'key' => $data['key'],
                ]);
                echo 'OK';
                die; 
            }
        } elseif (isset($data['setGame']) && $game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 2) {
            echo 'error_Настройки может изменить только игрок начинающий игру';
            die;
        } elseif (isset($data['setGame']) && $game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 1) {
            if ($data['require'] > 0) $cuthrow = 2;
            else $cuthrow = 0;

            $arr = [
                'firstToSets' => $data['setGame']['firstToSets'],
                'firstToLegs' => $data['setGame']['firstToLegs'],
                'bestOf' => $data['setGame']['bestOf'],
                'doublesCount' => $data['setGame']['doublesCount']
            ];
            // Проверяем, что необходимые данные переданы
            // if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
            //     echo 'error_Нет данных о счете между игроками';
            //     die;
            // }
            // подготовленный запрос для нового лега с данными о сетах/легах
            // $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2 WHERE `key`=:key');
            // пока действующий запрос

            $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow WHERE `key`=:key');
            $stmt->execute([
                'gameData' => json_encode($arr),
                'setting' => true,
                'require' => $data['require'],
                'score' => $data['score'],
                'darts' => $data['darts'],
                'doubleAttempts' => $data['doubleAttempts'],
                'currentThrow' => $cuthrow,
                // 'sets1' => $data['setsPlayer1'],
                // 'legs1' => $data['legsPlayer1'],
                // 'sets2' => $data['setsPlayer2'],
                // 'legs2' => $data['legsPlayer2'],
                'key' => $data['key'],
            ]);
            echo 'OK';
            die;
        } elseif (isset($data['setGame']) && $game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 9 && $data['require'] > 0 ){
            if ($data['require'] > 0) $cuthrow = 2; // если остаток не равен нулю, то передади ход
            else $cuthrow = 0; // если равен, то никому ход не передаём
            $arr = [
                'firstToSets' => $data['setGame']['firstToSets'],
                'firstToLegs' => $data['setGame']['firstToLegs'],
                'bestOf' => $data['setGame']['bestOf'],
                'doublesCount' => $data['setGame']['doublesCount']
            ];
            // Проверяем, что необходимые данные переданы
            // if (!$data['setsPlayer1'] && !$data['legsPlayer1'] && $data['setsPlayer2'] && !$data['legsPlayer2']){
            //     echo 'error_Нет данных о счете между игроками';
            //     die;
            // }
            // подготовленный запрос для нового лега с данными о сетах/легах
            // $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `require2`=:require, `score2`=:score, `darts2`=:darts, `doubleAttempts2`=:doubleAttempts, `current_throw` = :currentThrow, `sets1`=:sets1, `legs1`=:legs1, `sets2`=:sets2, `legs2`=:legs2 WHERE `key`=:key');
            // пока действующий запрос
            $stmt = pdo()->prepare('UPDATE p2p_games SET `gameData`=:gameData, `setGame`=:setting, `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `current_throw` = :currentThrow WHERE `key`=:key');
            $stmt->execute([
                'gameData' => json_encode($arr),
                'setting' => true,
                'require' => $data['require'],
                'score' => $data['score'],
                'darts' => $data['darts'],
                'doubleAttempts' => $data['doubleAttempts'],
                'currentThrow' => $cuthrow,
                // 'sets1' => $data['setsPlayer1'],
                // 'legs1' => $data['legsPlayer1'],
                // 'sets2' => $data['setsPlayer2'],
                // 'legs2' => $data['legsPlayer2'],
                'key' => $data['key'],
            ]);
            echo 'OK';
            die;
        }
    }

    if (!isset($data['score'])) { // Если запрос пришел с пустым 'score'
        $stmt = pdo()->prepare("SELECT * FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
        $stmt->execute([
            'key' => $data['key']
        ]);
        if (!$stmt->rowCount()) {
            echo 'error_Не найдено игры с таким ключом за последние сутки';
            die;
        }
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // То что мы делаем пока ход переходит между игроками
        
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

        if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 1) {
          
            if ($game['remove'] == true){
                $arr = [
                    'remove' => $game['remove']
                ];
                echo json_encode($arr);

                // $stmt = pdo()->prepare("UPDATE p2p_games SET `remove` = ? WHERE `key`= ?");
                // $stmt->execute([0, $data['key']]);
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
            
            $arr = [
                'score' => $game['score2'],
                'darts' => $game['darts2'],
                'doubleAttempts' => $game['doubleAttempts2']
            ];
            echo json_encode($arr);
            die;

        } elseif ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 2) {
            if ($game['remove'] == true){
                $arr = [
                    'remove' => $game['remove']
                ];
                echo json_encode($arr);
                // $stmt = pdo()->prepare("UPDATE p2p_games SET `remove` = ? WHERE `key`= ?");
                // $stmt->execute([0, $data['key']]);
                die;
            }
            if (isset($data['remove']) == true) {
                if ($game['remove'] == 1) {
                    echo 'error_Отмена хода уже активирована, жду набор';
                    die;
                }
                $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ?, `remove` = ? WHERE `key`= ?");
                $stmt->execute([1, 1, $data['key']]);
                echo 'OK';
                die;
            }
            if ($game['setGame'] != 1) {
                $arr = [
                    'score' => $game['score1'],
                    'darts' => $game['darts1'],
                    'doubleAttempts' => $game['doubleAttempts1']
                ];
            } else {
                $arr = [
                    'score' => $game['score1'],
                    'darts' => $game['darts1'],
                    'doubleAttempts' => $game['doubleAttempts1'],
                    'setGame' => json_decode($game['gameData'])
                ];
            }
            echo json_encode($arr);
            $stmt = pdo()->prepare("UPDATE p2p_games SET `setGame` = ? WHERE `key`= ?");
            $stmt->execute([0, $data['key']]);
            
            die;
        // Если перехода хода нет, то надо закончить Лег и сообщить об этом второму игроку

        } elseif ($game['require2'] == 0 && $game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 0) { // закончил второй игрок
            $arr = [
                'score' => $game['score2'],
                'darts' => $game['darts2'],
                'doubleAttempts' => $game['doubleAttempts2']
            ];
            echo json_encode($arr);
            $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ? WHERE `key`= ?");
            $stmt->execute([9, $data['key']]);
            die;

        } elseif ($game['require1'] == 0 && $game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 0) { // закончил первый игрок
            if ($game['setGame'] != 1) {
                $arr = [
                    'score' => $game['score1'],
                    'darts' => $game['darts1'],
                    'doubleAttempts' => $game['doubleAttempts1']
                ];
            } else {
                $arr = [
                    'score' => $game['score1'],
                    'darts' => $game['darts1'],
                    'doubleAttempts' => $game['doubleAttempts1'],
                    'setGame' => json_decode($game['gameData'])
                ];
            }
            echo json_encode($arr);
            $stmt = pdo()->prepare("UPDATE p2p_games SET `current_throw` = ? WHERE `key`= ?");
            $stmt->execute([9, $data['key']]);
            die;
        }
    }
}
