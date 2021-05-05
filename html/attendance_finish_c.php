<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'registration.php';
require_once MODEL_PATH . 'calculation.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user_id = get_session('user');
$attendance = select_attendance($db, $user_id);
$is_start_work = is_start_work($db, $user_id);

if(isset($_POST['finish'])) {
    finish_work($db, $user_id);

    $workplace = select_last_workplace($db, $attendance['regist_id']);

    //finish時間の取得
    $date = date('Y-m-d H:i:s');
    $separate_finish = get_separate_finish($db, $date, $workplace['separation']);
    insert_history($db, $user_id, $attendance['regist_id'], $attendance['begin'], $date, $separate_finish);
    
    $last_id = select_last_history_id($db, $user_id);
    $minutes = get_minutes($db, $last_id['history_id']);

    //begin<finishの場合を除く
    if($minutes['worktime'] <= 0 || $minutes['worktime'] === FALSE) {
        set_error('勤務情報の取得に失敗しました。');
        delete_history($db, $last_id['history_id']);
    } else {
        $workday = get_workday($db, $last_id['history_id']);

        //最新のhistory_idから$wage計算用の時刻を取得
        $last_history = select_last_history($db, $last_id['history_id']);
        $begin = date('H:i:s', strtotime($last_history['begin']));
        $finish = date('H:i:s', strtotime($last_history['separate_finish']));
        
        $wage = round(wage($begin, $finish, 
                    $workday['workday'],
                    $workplace['mid_start'],
                    $workplace['mid_finish'],
                    $workplace['midnight_wage'],
                    $workplace['hourly_wage']));

            insert_wages($db, $last_id['history_id'], $minutes['worktime'], $wage);
            set_message($_SESSION['now_workplace'] .  'を退勤しました。');
            set_message('本日の勤務時間は' . floor($minutes['worktime']/60) . '時間' . floor($minutes['worktime']%60) . '分です。');
            set_message('給料は' . $wage . '円です。');
    }

    unset_session('now_workplace');
    $is_start_work = FALSE;
    redirect_to(HOME_URL);
}
include_once VIEW_PATH . 'attendance.php';
?>