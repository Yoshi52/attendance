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
$user_id = get_session('user');
$is_start_work = is_start_work($db, $user_id);
$attendance = select_attendance($db, $user_id);
$main_workplace = select_main_workplace($db, $user_id);
$workplaces = select_workplace($db, $user_id);

if(isset($_POST['start'])) {
    if(!empty($main_workplace)) {
        $begin_time = start_work_daytime();
        start_work($db, $user_id, $main_workplace['regist_id'],$begin_time);
        $now_workplace = select_now_workplace($db, $user_id);
        set_session('now_workplace', foreach_assosiative_array($now_workplace));
        set_message('出勤しました。');
        $is_start_work = TRUE;
        redirect_to(HOME_URL);
    } else {
        set_error('勤務先を設定してください。');
    }
}


include_once VIEW_PATH . 'attendance.php';
?>