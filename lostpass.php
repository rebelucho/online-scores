<?php
ini_set('display_errors','On');
error_reporting('E_ALL');
require_once __DIR__.'/inc/boot.php';
require_once __DIR__.'/template/header.html';
require_once __DIR__.'/assets/apmail.php';
require_once __DIR__.'/inc/mailconf.php';
// Инициализируем функцию отправки писем
Mailer()->init($mailConfig);


?>

<div class="container">
    <h1>Восстановление пароля</h1>
    <p>Для восстановления пароля введите ваш электронный адрес, указанный при регистрации и нажмите кнопку "Оправить".</br> 
    Проверьте Ваш почтовый ящик. </br>
    Следуйте инструкции в полученном письме.</p>
    <p>Если при регистрации Вы не указали почтовый ящик, то сообщите свой логин и почтовый ящик администратору сайта на почту info@darts28.ru</p>
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
        <p>Введите ваш EMail: <input type="email" name="email"></p>
        <p><input type="submit" value="Отправить" name="doGo"></p>
    </form>
</div>
<div class="container">
<?php

// Проверяем нажата ли кнопка отправки формы
if (isset($_REQUEST['doGo'])) {
    // Проверка что email введён
    if ($_REQUEST['email']) {
        $email = $_REQUEST['email'];
        
        // хешируем хеш, который состоит из email и времени
        $hash = md5($email . time());

        // Собираем письмо для отправки
        $messageSubject = "Восстановление пароля для online-scores.darts28.ru";
        $messageFrom = 'robot@darts28.ru';
        $messageText = '<p>Для генерации нового пароля перейдите по <a href="http://online-scores.darts28.ru/newpass.php?hash=' . $hash . '">ссылке</a></p>';
        $message = Mailer()->newHtmlMessage();
        $message->setSubject($messageSubject);
        $message->setSenderEmail($messageFrom);
        $message->addRecipient($email);
        $message->addContent(file_get_contents('/template/mail/mail-header.html'));
        $message->addContent($messageText);
        $message->addContent(file_get_contents('/template/mail/mail-footer.html')); 
        

        // Меняем хеш в БД
        $stmt = pdo()->prepare("UPDATE `users` SET hash=:hash, `not_confirm`=true WHERE email=:email");
        $stmt->execute([
            'hash' => $hash,
            'email' => $email,
        ]);
        // Проверяем отправилась ли почта
        if (Mailer()->sendMessage($message)) {

            // Если да, то выводим сообщение
            echo '
                <div class="alert alert-success mb-3">
                    Ссылка для восстановления пароля отправленна на Вашу почту
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-danger mb-3">
                    Произошла какая то ошибка, письмо не отправилось
                </div>
            ';
        }
    } else {
        // Если ошибка есть, то выводим её 
        echo '
            <div class="alert alert-danger mb-3"> 
                Вы не ввели e-mail
            </div>
        '; 
    }
}

require_once __DIR__.'/template/header.html';
?>
</div>