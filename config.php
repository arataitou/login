<?php
define('DSN', 'mysql:host=localhost;dbname=sns');
define('DB_USER', 'root');
define('DB_PASSWORD', 'camp2014');
define('SITE_URL', 'http://192.168.33.10/sns/');
define('PASSWORD_KEY', 'good');
error_reporting(E_ALL & ~E_NOTICE);
session_set_cookie_params(0, '/sns/');
?>
