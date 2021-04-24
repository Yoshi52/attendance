<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'registration.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user_id = get_session('user_id');

if(isset($_POST['set_main_workplace'])) {
    $main_workplace = get_post('main_workplace');
    update_not_main_workplace($db, $user_id);
    update_main_workplace($db, $user_id, $main_workplace);
    set_one_session('main_workplace', $main_workplace);
    set_message($main_workplace . 'を勤務先に設定しました。');
    redirect_to(HOME_URL);
}

//$workplaces = select_workplace($db, $user_id);

include_once VIEW_PATH . 'attendance.php';
?>