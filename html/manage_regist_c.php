<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'management.php';
require_once MODEL_PATH . 'registration.php';
require_once MODEL_PATH . 'calculation.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user_id = get_session('user');

$workplace = get_post('workplace');
$workplace_info = check_duplicate_workplace_name($db, $user_id, $workplace);

$start_year = get_post('start_year');
$start_month = get_post('start_month');
$start_day = get_post('start_day');
$start_hour = get_post('start_hour');
$start_minute = get_post('start_minute');

$finish_year = get_post('finish_year');
$finish_month = get_post('finish_month');
$finish_day = get_post('finish_day');
$finish_hour = get_post('finish_hour');
$finish_minute = get_post('finish_minute');

if(isset($_POST['regist'])) {
    //postされた値をattendance_historyにinsert
    $start_daytime = date('Y-m-d H:i:s', strtotime($start_year . '-' . $start_month . '-' . $start_day . ' ' .
                     $start_hour . ':' . $start_minute . ':00'));
    $finish_daytime = date('Y-m-d H:i:s', strtotime($finish_year . '-' . $finish_month . '-' . $finish_day . ' ' .
                      $finish_hour . ':' . $finish_minute . ':00'));
    error_check_year_month($start_daytime, $finish_daytime, $workplace_info['separation']);

    if(empty($_SESSION['errors'])) {
        $regist_id = select_regist_id($db, $user_id, $workplace);
        insert_history_attendance($db, $user_id, $regist_id['regist_id'], $start_daytime, $finish_daytime);

        //wageを計算
        $last_id = select_last_history_id($db, $user_id);
        $workday = get_workday($db, $last_id['history_id']);
        $begin = date('H:i:s', strtotime($start_daytime));
        $finish = date('H:i:s', strtotime($finish_daytime));
        $workplace = select_last_workplace($db, $regist_id['regist_id']);
        $minutes = get_minutes($db, $last_id['history_id']);
        $wage = round(wage($begin, $finish, 
                        $workday['workday'],
                        $workplace['mid_start'],
                        $workplace['mid_finish'],
                        $workplace['midnight_wage'],
                        $workplace['hourly_wage']));
        insert_wages($db, $last_id['history_id'], $minutes['worktime'], $wage);

        set_message('勤怠情報を登録しました。');
    } else {
        set_error('勤怠情報の登録に失敗しました。');
        redirect_to(MANAGE_URL);
    }
}

redirect_to(MANAGE_URL);
?>