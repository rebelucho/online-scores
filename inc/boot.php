<?php
###########################################################
# Функции используемые в фукнционале
# Подключение к БД настраивается в файле config.php
# Настройка почты в файле mailconf.php
#
// Инициализируем сессию
session_start();


$_SESSION['user_role'] ?? $_SESSION['user_role'] = 0;

// Переменные
$config = include __DIR__.'/config.php';
$page_on_list = $config['page_on_list'];

// Подключение в БД

function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        $config = include __DIR__.'/config.php';
        // Подключение к БД
        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}


// Функция передачи сообщений через Сессии

function flash(?string $message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
          <div class="alert alert-danger mb-3">
              <?=$_SESSION['flash']?>
          </div>
        <?php }
        unset($_SESSION['flash']);
    }
}


// Функция проверки авторизации

function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}

// Функция генератора пароля
function gen_password($length = 6)
{				
	$chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP'; 
	$size = strlen($chars) - 1; 
	$password = ''; 
	while($length--) {
		$password .= $chars[random_int(0, $size)]; 
	}
	return $password;
}  