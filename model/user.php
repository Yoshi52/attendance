<?php
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'function.php';

function insert_user($db, $user_id, $email){
    $sql = "
        INSERT INTO 
            user(user_id,email)
        VALUES(:user_id, :email)
    ";
    $params = array(':user_id' => $user_id, ':email' => $email);
    return execute_query($db, $sql, $params);
}

function check_duplicate_email($db, $email) {
  $sql = "
    SELECT
      *
    FROM
      user
    WHERE
      email = :email
    LIMIT 1
  ";
  $params = array(':email' => $email);
  return fetch_query($db, $sql, $params);
}

function check_duplicate_user_id($db, $user_id) {
  $sql = "
    SELECT
      *
    FROM
      user
    WHERE
      user_id = :user_id
    LIMIT 1
  ";
  $params = array(':user_id' => $user_id);
  return fetch_query($db, $sql, $params);
}

function select_user($db, $user_id, $email) {
  $sql = "
    SELECT
      user_id,
      email
    FROM
      user
    WHERE
      user_id = :user_id
    AND
      email = :email
  ";
  $params = array(':user_id' => $user_id, ':email' => $email);
  return fetch_query($db, $sql, $params);
}

function error_check_user_id($user_id) {
  if (trim($user_id) === '') {
      set_error('ユーザーIDを入力してください。');
  } else if (is_valid_length_max($user_id,USER_NAME_LENGTH_MAX) === false) {
      set_error('ユーザーIDは' . USER_NAME_LENGTH_MAX . '文字以内で入力してください。');
  } else if (is_valid_length_min($user_id,USER_NAME_LENGTH_MIN) === false) {
      set_error('ユーザーIDは' . USER_NAME_LENGTH_MIN . '文字以上で入力してください。');
  } else if (is_alphanumeric($user_id) === false) {
      set_error('ユーザーIDは半角英数字で入力してください。');
  }
}
  
  function error_check_email($email){
    if (trim($email) === '') {
      set_error('メールアドレスを入力してください。');
    } else if(is_valid_length_max($email,USER_EMAIL_LENGTH_MAX) === false){
      set_error('メールアドレスは' . USER_EMAIL_LENGTH_MAX . '文字以内で入力してください。');
    } else if (is_valid_length_min($email,USER_EMAIL_LENGTH_MIN) === false){
      set_error('メールアドレスは' . USER_EMAIL_LENGTH_MIN . '文字以上で入力してください。');
    } else if(is_true_email($email) === false) {
      set_error('メールアドレスを正しい形式で入力してください。');
    }
  }

  ?>