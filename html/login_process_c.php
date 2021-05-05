<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'registration.php';

session_start();

$user_id = get_post('user_id');
$email = get_post('email');

$db = get_db_connect();

$login_check = select_user($db, $user_id, $email);


if(!empty($login_check) === TRUE) {
    set_session('user', $user_id);
    $main_workplace = select_main_workplace($db, $user_id);
    $now_workplace = select_now_workplace($db, $user_id);
    if(!empty($main_workplace)) {
      set_session('main_workplace', $main_workplace['workplace_name']);
    }
    if (!empty($now_workplace)) {
        set_session('now_workplace', $now_workplace['workplace_name']);
    }
    set_message('ログインに成功しました。');
    redirect_to(HOME_URL);
} else {
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
}

?>