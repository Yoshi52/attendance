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
$workplace_name = get_post('workplace_name');
$mid_sta = date(get_post('mid_sta') . ':00:00');
$mid_fin = date(get_post('mid_fin') . ':00:00');
$wage = get_post('wage');
$midnight_wage = get_post('midnight_wage');
$separation = (int)get_post('separation');

$duplicate_workplace_name = check_duplicate_workplace_name($db, $user_id, $workplace_name);

if(!empty($duplicate_workplace_name)) {
    set_error('登録済みの職場です。');
}

if(isset($_POST['regist'])){
    error_check_workplace_name($workplace_name);
    error_check_wage($wage);    
    error_check_midnight_wage($midnight_wage);

    if(empty($_SESSION['errors'])) {
        insert_workplace($db, $user_id, $workplace_name, $mid_sta, $mid_fin, $wage, $midnight_wage, $separation);
        set_message('職場の登録が完了しました。');
        redirect_to(REGIST_URL);
    } else {
        set_error('職場の登録に失敗しました。');
        redirect_to(REGIST_URL);
    }
}

$workplaces = select_workplace($db, $user_id);

include_once VIEW_PATH . 'regist.php';
?>