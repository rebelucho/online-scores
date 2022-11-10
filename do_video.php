<?php
require_once __DIR__.'/inc/boot.php';

if (isset($_POST['id']))
    $id = $_POST['id'];
else 
    return 'Error, not ID';

if (isset($_POST['enable']))
    $enable = $_POST['enable'];
else 
    $enable = false; 

$stmt = pdo()->prepare('UPDATE `games` SET `video` = :video WHERE `id` = :id');
$stmt->execute([
    'id' => $id,
    'video' => $enable
]);


