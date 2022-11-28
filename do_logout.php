<?php

require_once __DIR__.'/inc/boot.php';

$_SESSION['user_id'] = null;
$_SESSION['user_role'] = null;
header('Location: /');