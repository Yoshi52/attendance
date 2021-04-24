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
$workplaces = select_workplace($db, $user_id);

if(isset($_POST['delete_workplace'])) {
    $regist_id = get_post('delete_id');
    $workplace_name = get_post('delete_name');
    delete_workplace($db, $regist_id);
    set_message($workplace_name . 'を削除しました。');
    redirect_to(REGIST_URL);
}

include_once VIEW_PATH . 'regist.php';
?>