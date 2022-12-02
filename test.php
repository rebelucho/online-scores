<?php
require_once __DIR__.'/inc/boot.php';

$json = '{ 
    "stage": "game", 
    "key": "FLGTo", 
    "guid": "82f68090-d49a-a246-bf4d-c9a1117eed1f", 
    "score": 180, 
    "require": 205, 
    "darts": 3, 
    "doubleAttempts": 0 
}';

// $json = '{ 
//     "stage": "game", 
//     "key": "FLGTo", 
//     "guid": "faeb8e2d-963f-6b4e-a0b4-ee8bddc13906", 
//     "score": 180, 
//     "require": 241, 
//     "darts": 3, 
//     "doubleAttempts": 0 
// }';



// разбираем JSON-строку на составляющие 
$data = json_decode($json,true);


$stmt = pdo()->prepare("SELECT *, COALESCE(player1, '') AS pl1, COALESCE(player2, '') AS pl2 FROM `p2p_games` WHERE `key` = :key AND last_update >= now() - interval 1 day");
$stmt->execute([
    'key' => $data['key']
]);
if (!$stmt->rowCount()) {
    echo 'error_Не найдено игры с таким ключом за последние сутки';
    die;
}
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game['pl1']) echo 'в player1 пусто<br>';
if (!$game['pl2']) echo 'в player2 пусто<br>';

// var_dump($game);

if (!isset($data['setGame'])) { // если в дате нет установок игры, то передаем:
    if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 2) {
        echo 'error_Сейчас ход игрока 2';
        die;
    } else if ($game['guid_gamer2'] == $data['guid'] && $game['current_throw'] == 1) {
        echo 'error_Сейчас ход игрока 1';
        die;
    }
    if ($game['guid_gamer1'] == $data['guid'] && $game['current_throw'] == 1) { // Узнаём кто прислал и проверяем на окончание лега
        if ($data['require'] > 0) $cuthrow = 2; // если остаток не равен нулю, то передадим ход
        else $cuthrow = 0; // если равен, то никому ход не передаём
        echo $data['score'];
        echo '<br>';
        $player = json_decode($game['player1'],true);
        if ($data['score'] >= 100) {
            $player['max'][] = $data['score']; // добавляем очки в массив максимумов
            $playerMax = $player['max'];
        } else if (!$game['pl1']) {
            $playerMax = null;
        } else {
            $playerMax = $player['max'];
        }
        print_r($playerMax);
        
        $player = [
            'max' => $playerMax
        ];
        
        $stmt = pdo()->prepare('UPDATE p2p_games SET `require1`=:require, `score1`=:score, `darts1`=:darts, `doubleAttempts1`=:doubleAttempts, `player1` =:player1, `current_throw` = :currentThrow WHERE `key`=:key');
        $stmt->execute([
            'require' => $data['require'],
            'score' => $data['score'],
            'darts' => $data['darts'],
            'doubleAttempts' => $data['doubleAttempts'],
            'currentThrow' => $cuthrow,
            'player1' => json_encode($player),
            'key' => $data['key'],
        ]);
        echo 'OK';
        die;
    }
}