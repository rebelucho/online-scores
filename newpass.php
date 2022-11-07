<?php
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.html';
?>
<div class="container">

<h1>Восстановление пароля</h1>

<?php
// Проверка есть ли хеш
if ($_REQUEST['hash']) {
    $stmt = pdo()->prepare("SELECT id, not_confirm FROM users WHERE hash=:hash ");
        $stmt->execute([
            // 'hash' => $hash,
            'hash' => $_REQUEST['hash'],
        ]);
    
    foreach($stmt as $row){
        // print_r($row);
        // echo 'ID : '. $row["id"] .'</br>';
        // echo $row['not_confirm'];
        if ($row['not_confirm'] == 1) {
            $pass = gen_password(8);
            $stmt = pdo()->prepare("UPDATE `users` SET `password`=:password, `not_confirm`=:notconfirm WHERE `id`=:id");
            $stmt->execute([
                'password' => password_hash($pass, PASSWORD_DEFAULT),
                'notconfirm' =>  false,
                'id' => $row['id'],
            ]);
            echo '
            <div class="alert alert-success mb-3">
                Ваш новый пароль: ' . $pass . '
            </div>
            ';
        } else {
            echo '
            <div class="alert alert-danger mb-3"> 
                А где вы взяли этот код восстановления? Верните обратно!
            </div>
            ';
        }
    } 
} else {
    echo '
    <div class="alert alert-danger mb-3"> 
        Ошибка! А где код восстановления?
    </div>
    ';
}


?>
</div>

<?php
require_once __DIR__.'/template/footer.html';
?>