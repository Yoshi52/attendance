<?php

function insert_workplace($db, $user_id, $workplace_name, $mid_sta, $mid_fin, $wage, $midnight_wage, $separation) {
    $sql = "
        INSERT INTO
          regist_workplace(
            user_id,
            workplace_name,
            mid_start,
            mid_finish,
            hourly_wage,
            midnight_wage,
            separation)
        VALUES(:user_id, :workplace_name, :mid_start, :mid_finish, :hourly_wage, :midnight_wage, :separation)
    ";
    $params = array(':user_id' => $user_id, ':workplace_name' => $workplace_name, ':mid_start' => $mid_sta, ':mid_finish' => $mid_fin, ':hourly_wage' => $wage, ':midnight_wage' => $midnight_wage, ':separation' => $separation);
    return execute_query($db, $sql, $params);
}

function select_workplace($db, $user_id) {
    $sql = "
      SELECT
        regist_id,
        workplace_name,
        mid_start,
        mid_finish,
        hourly_wage,
        midnight_wage,
        separation
      FROM
        regist_workplace
      WHERE
        user_id = :user_id
    ";
    $params = array(':user_id' => $user_id);
    return fetch_all_query($db, $sql, $params);
}

function error_check_workplace_name($workplace_name) {
    if (trim($workplace_name) === '') {
        set_error('職場名を入力してください。');
    } else if (is_valid_length_max($workplace_name,WORKPLACE_NAME_LENGTH_MAX) === false) {
        set_error('職場名は' . WORKPLACE_NAME_LENGTH_MAX . '文字以内で入力してください。');
    } else if (is_valid_length_min($workplace_name,WORKPLACE_NAME_LENGTH_MIN) === false) {
        set_error('職場名は' . WORKPLACE_NAME_LENGTH_MIN . '文字以上で入力してください。');
    } 
}

function error_check_wage($wage) {
    if (trim($wage) === '') {
        set_error('時給を入力してください。');
    } else if (is_valid_length_max($wage,WAGE_LENGTH_MAX) === false) {
        set_error('時給は' . WAGE_LENGTH_MAX . '桁以内で入力してください。');
    } else if (is_valid_length_min($wage,WAGE_LENGTH_MIN) === false) {
        set_error('時給は' . WAGE_LENGTH_MIN . '桁以上で入力してください。');
    } else if (is_half_size_number($wage) === false) {
        set_error('時給は半角数字で入力してください。');
    }
}

function error_check_midnight_wage($wage) {
  if (trim($wage) === '') {
      set_error('深夜時給を入力してください。');
  } else if (is_valid_length_max($wage,WAGE_LENGTH_MAX) === false) {
      set_error('深夜時給は' . WAGE_LENGTH_MAX . '桁以内で入力してください。');
  } else if (is_valid_length_min($wage,WAGE_LENGTH_MIN) === false) {
      set_error('深夜時給は' . WAGE_LENGTH_MIN . '桁以上で入力してください。');
  } else if (is_half_size_number($wage) === false) {
      set_error('深夜時給は半角数字で入力してください。');
  }
}

function check_duplicate_workplace_name($db, $user_id, $workplace_name){
    $sql = "
      SELECT
        *
      FROM
        regist_workplace
      WHERE
        user_id = :user_id
      AND
        workplace_name = :workplace_name
      LIMIT 1
    ";
    $params = array(':user_id' => $user_id, ':workplace_name' => $workplace_name);
    return fetch_query($db, $sql, $params);
}

function update_main_workplace($db, $user_id, $workplace_name) {
    $sql = "
      UPDATE 
        regist_workplace
      SET
        main = 1
      WHERE
        user_id = :user_id
      AND
        workplace_name = :workplace_name
      LIMIT 1
    ";
    $params = array(':user_id' => $user_id, ':workplace_name' => $workplace_name);
    return execute_query($db, $sql, $params);
}

function update_not_main_workplace($db, $user_id) {
    $sql = "
      UPDATE
        regist_workplace
      SET
        main = 0
      WHERE
        user_id = :user_id
      AND
        main = 1
    ";
    $params = array(':user_id' => $user_id);
    return execute_query($db, $sql, $params);
}

function select_main_workplace($db, $user_id) {
    $sql = "
      SELECT
        regist_id,
        workplace_name,
        hourly_wage
      FROM
        regist_workplace
      WHERE
        user_id = :user_id
      AND
        main = 1
      LIMIT 1
    ";
    $params = array(':user_id' => $user_id);
    return fetch_query($db, $sql, $params);
}

function delete_workplace($db, $regist_id) {
    $sql = "
      DELETE FROM
        regist_workplace
      WHERE
        regist_id = :regist_id
    ";
    $params = array(':regist_id' => $regist_id);
    return execute_query($db, $sql, $params);
}

function start_work_daytime() {
  $time = new DateTime(date('H:i:s'));
  $time1 = new DateTime(date('H') . ':00:59');
  $time2 = new DateTime(date('H') . ':15:59');
  $time3 = new DateTime(date('H') . ':30:59');
  $time4 = new DateTime(date('H') . ':45:59');
  $time5 = new DateTime(date('H:00:59', strtotime('+1 hour')));
  if($time1 < $time  && $time <= $time2) {
    return date('Y/m/d H:15:00');
  } else if($time2 < $time && $time <= $time3) {
    return date('Y/m/d H:30:00');
  } else if($time3 < $time && $time <= $time4) {
    return date('Y/m/d H:45:00');
  } else if($time4 < $time && $time <= $time5) {
    if(date('H') === '23') {
      if(date('d', strtotime('+1 day')) === '1') {
        return date('Y/m/1' . ' ' . '00:00:00');
      } else {  
        $date = date('d') + 1;
        return date('Y/m/' . $date . ' ' . '00:00:00');
      }
    } else {
      $hour = date('H') + 1;
      return date('Y/m/d' . ' ' . $hour . ':00:00');
    }
  }
}

function start_work($db, $user_id, $regist_id, $begin) {
  $sql = "
    INSERT INTO
      attendance
        (user_id, regist_id, begin)
    VALUES(:user_id, :regist_id, :begin)
  ";
  $params = array(':user_id' => $user_id, ':regist_id' => $regist_id, ':begin' => $begin);
  return execute_query($db, $sql, $params);
}

function finish_work($db, $user_id) {
$sql = "
  DELETE FROM
    attendance
  WHERE
    user_id = :user_id
";
$params = array(':user_id' => $user_id);
return execute_query($db, $sql, $params);
}

function select_attendance($db, $user_id) {
$sql = "
  SELECT
    *
  FROM
    attendance
  WHERE
    user_id = :user_id
  LIMIT 1
";
$params = array(':user_id' => $user_id);
return fetch_query($db, $sql, $params);
}

function is_start_work($db, $user_id) {
$is_start = select_attendance($db, $user_id);
if(empty($is_start)) {
  return FALSE;
} else {
  return TRUE;
}
}

function insert_history($db, $user_id, $regist_id, $begin, $finish, $separate_finish) {
$sql = "
  INSERT INTO
    attendance_history(
      user_id,
      regist_id,
      begin,
      finish,
      separate_finish
    )
  VALUES(:user_id, :regist_id, :begin, :finish, :separate_finish)
";
$params = array(':user_id' => $user_id, ':regist_id' => $regist_id, ':begin' => $begin, ':finish' => $finish, ':separate_finish' => $separate_finish);
return execute_query($db, $sql, $params);
}


function get_separate_finish($db, $finish, $separation) {
    $finish_min = date('i', strtotime($finish));
    $min = floor($finish_min / $separation);
    if(!$min) {
      return date('Y-m-d H:00:00', strtotime($finish));
    } else {
      return date('Y-m-d H:' . $min * $separation . ':00', strtotime($finish));
    }
}

function select_now_workplace($db, $user_id) {
$sql = "
  SELECT
    regist_workplace.workplace_name
  FROM
    regist_workplace
  JOIN
    attendance
  ON
    regist_workplace.regist_id = attendance.regist_id
  WHERE
    attendance.user_id = :user_id
";
$params = array(':user_id' => $user_id);
return fetch_query($db, $sql, $params);
}

function select_last_history_id($db, $user_id) {
  $sql = "
    SELECT
      MAX(history_id) AS history_id
    FROM
      attendance_history
    WHERE
      user_id = :user_id
  ";
  $params = array(':user_id' => $user_id);
  return fetch_query($db, $sql, $params);
}

function delete_history($db, $last_id) {
  $sql = "
    DELETE FROM
      attendance_history
    WHERE
      history_id = :history_id
  ";
  $params = array(':history_id' => $last_id);
  return execute_query($db, $sql, $params);
}

function delete_wage($db, $last_id) {
  $sql = "
    DELETE FROM
      wages
    WHERE
      history_id = :history_id
  ";
  $params = array(':history_id' => $last_id);
  return execute_query($db, $sql, $params);
}

?>