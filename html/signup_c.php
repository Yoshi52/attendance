<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'function.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'signup.php';

?>