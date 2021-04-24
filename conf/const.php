<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/www/model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/www/view/');
define('STYLESHEET_PATH', '../html/css/');

define('SIGNUP_URL', '../html/signup_c.php');
define('LOGIN_URL', '../html/login_c.php');
define('LOGOUT_URL', '../html/logout_c.php');
define('HOME_URL', '../html/attendance_c.php');
define('MANAGE_URL', '../html/manage_c.php');
define('REGIST_URL', '../html/workplace_regist_c.php');

define('DB_HOST', 'mysql12012.xserver.jp');
define('DB_NAME', 'ysmy_work');
define('DB_USER', 'ysmy_ys');
define('DB_PASS', 'yoshito52');
define('DB_CHARSET', 'utf8');

define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 20);
define('USER_EMAIL_LENGTH_MIN', 3);
define('USER_EMAIL_LENGTH_MAX', 254);
define('WORKPLACE_NAME_LENGTH_MIN', 1);
define('WORKPLACE_NAME_LENGTH_MAX', 30);
define('WAGE_LENGTH_MIN', 3);
define('WAGE_LENGTH_MAX', 4);

?>