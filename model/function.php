<?php

function v($string) {
    var_dump($string);
}

function redirect_to($url) {
    header('Location: ' . $url);
    exit;
}

function get_post($name) {
    if(isset($_POST[$name]) === true) {
      return $_POST[$name];
    };
    return '';
}

function get_session($name){
    if(isset($_SESSION[$name]) === true){
      return $_SESSION[$name];
    };
    return '';
}

function set_session($name, $value) {
    $_SESSION[$name] = $value;
}

function set_one_session($name, $value) {
    if($_SESSION[$name] === ''){
        set_session($name, $value);
    } else {
        set_session($name, '');
        set_session($name, $value);
    }
}

function unset_session($name) {
    if(!empty($_SESSION[$name])) { 
        unset($_SESSION[$name]);
    }
}

function set_error($error) {
    $_SESSION['errors'][] = $error;
}

function get_errors(){
    $errors = get_session('errors');
    if($errors === ''){
      return array();
    }
    set_session('errors',  array());
    return $errors;
}

function set_message($message) {
    $_SESSION['messages'][] = $message;
}

function get_messages(){
    $messages = get_session('messages');
    if($messages === ''){
      return array();
    }
    set_session('messages',  array());
    return $messages;
}

function is_logined() {
    return get_session('user_id') !== '';
}

function is_alphanumeric($string) {
    if(preg_match("/^[a-zA-Z0-9]+$/",$string)) {
        return true;
    } else {
        return false;
    }
}

function is_half_size_number($string) {
    if(preg_match("/^[0-9]+$/",$string)) {
        return true;
    } else {
        return false;
    }
}

function is_valid_length_max($string, $max_length) {
    if(mb_strlen($string) > $max_length) {
        return false;
    }
    return true;
}

function is_valid_length_min($string, $min_length) {
    if(mb_strlen($string) < $min_length) {
        return false;
    }
    return true;
}

function is_true_email($email) {
    $pattern = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";
    if(preg_match($pattern,$email)) {
        return true;
    } else {
        return false;
    }
}

function h($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
  }

function foreach_assosiative_array($name) {
    if ($name === '') {
      return '';
    }
    foreach($name as $value) {
          return $value;
      }
}

function get_hour ($date) {
    return date('H', strtotime($date));
}

?>