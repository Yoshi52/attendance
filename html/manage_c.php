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

$my_workplace = select_workplace($db, $user_id);

$first_year_month = select_first_attendance($db, $user_id);
$last_year_month = select_last_attendance($db, $user_id);

if(($last_year_month['last']) !== NULL) {
    $first_year = (int)date('Y', strtotime($first_year_month['first']));
    $last_year = (int)date('Y', strtotime($last_year_month['last']));
    $last_month = (int)date('m', strtotime($last_year_month['last']));
}
$year_month_attendance = array();
//初期値の取得
$year_month = $last_year . $last_month;
$year_month_attendance = select_year_month_attendance($db, $user_id, $year_month);
$sum_wage_month = select_sum_wage_month($db, $user_id, $year_month);
$sum_wage_year = select_sum_wage_year($db, $user_id, $last_year);
$selected_year = mb_substr($year_month,0,4);
$selected_month = mb_substr($year_month,4,6);

if(isset($_POST['change'])) {
    if(isset($_POST['year_month'])) {
        $year_month = get_post('year_month');
        $selected_year = mb_substr($year_month,0,4);
        $selected_month = mb_substr($year_month,4,6);
        $year_month_attendance = select_year_month_attendance($db, $user_id, $year_month);
        $sum_wage_month = select_sum_wage_month($db, $user_id, $year_month);
        $sum_wage_year = select_sum_wage_year($db, $user_id, $selected_year);
    }
}

include_once VIEW_PATH . 'manage.php';
?>