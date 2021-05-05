<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'management.php';
require_once MODEL_PATH . 'registration.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user_id = get_session('user');

if(isset($_POST['delete'])) {
    $history_id = get_post('history_id');
    delete_history($db, $history_id);
    delete_wage($db, $history_id);
    set_message('削除しました。');
}

redirect_to(MANAGE_URL);
?>