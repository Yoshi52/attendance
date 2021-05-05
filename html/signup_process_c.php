<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'db.php';

session_start();

$user_id = get_post('user_id');
$email = get_post('email');

$db = get_db_connect();

error_check_user_id($user_id);
error_check_email($email);

$duplicate_email = check_duplicate_email($db, $email);
$duplicate_user_id = check_duplicate_user_id($db, $user_id);

if(!empty($duplicate_user_id)){
    set_error('そのユーザーIDは既に登録されています。');
}
if(!empty($duplicate_email)){
    set_error('そのメールアドレスは既に登録されています。');
}

if (empty($_SESSION['errors'])) {
    insert_user($db, $user_id, $email);
    set_session('user', $user_id);
    set_message('ユーザー登録が完了しました。');
    set_message('ログインしました。');
    redirect_to(HOME_URL);
} else {
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
}

?>