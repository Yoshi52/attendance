<?php

function select_year_month_attendance($db, $user_id, $year_month) {
    $sql = "
      SELECT
        attendance_history.history_id,
        begin,
        separate_finish,
        worktime,
        wage,
        workplace_name
      FROM
        attendance_history
      JOIN
        wages
      ON
        attendance_history.history_id = wages.history_id
      RIGHT JOIN
        regist_workplace
      ON
        attendance_history.regist_id = regist_workplace.regist_id
      WHERE
        attendance_history.user_id = :user_id
      AND
        DATE_FORMAT(begin, '%Y%c') = :year_month
      ORDER BY
        begin
    ";
    $params = array(':user_id' => $user_id, ':year_month' => $year_month);
    return fetch_all_query($db, $sql, $params);
}

function select_first_attendance($db, $user_id) {
  $sql = "
    SELECT
      MIN(begin) as first
    FROM
      attendance_history
    WHERE
      user_id = :user_id
    LIMIT 1
  ";
  $params = array('user_id' => $user_id);
  return fetch_query($db, $sql, $params);
}

function select_last_attendance($db, $user_id) {
  $sql = "
    SELECT
      MAX(begin) as last
    FROM
      attendance_history
    WHERE
      user_id = :user_id
    LIMIT 1
  ";
  $params = array('user_id' => $user_id);
  return fetch_query($db, $sql, $params);
}

function select_specific_attendance($db, $user_id, $year, $month) {
  $sql = "
    SELECT
      begin
    FROM
      attendance_history
    WHERE
      user_id = :user_id
    AND
      DATE_FORMAT(begin, '%Y%c') = :year_month
  ";
  $params = array(':user_id' => $user_id, ':year_month' => $year . $month);
  return fetch_all_query($db, $sql, $params);
}

function select_sum_wage_month($db, $user_id, $year_month) {
  $sql = "
    SELECT
      SUM(wage) as sum_wage
    FROM
      wages
    JOIN
      attendance_history
    ON
      wages.history_id = attendance_history.history_id
    WHERE
      user_id = :user_id
    AND
      DATE_FORMAT(begin, '%Y%c') = :year_month
  ";
  $params = array(':user_id' => $user_id, ':year_month' => $year_month);
  return fetch_query($db, $sql, $params);
}

function select_sum_wage_year($db, $user_id, $year) {
  $sql = "
    SELECT
      SUM(wage) as sum_wage
    FROM
      wages
    JOIN
      attendance_history
    ON
      wages.history_id = attendance_history.history_id
    WHERE
      user_id = :user_id
    AND
      DATE_FORMAT(begin, '%Y') = :year
  ";
  $params = array(':user_id' => $user_id, ':year' => $year);
  return fetch_query($db, $sql, $params);
}

function select_regist_id($db, $user_id, $workplace_name) {
  $sql = "
    SELECT
      regist_id
    FROM
      regist_workplace
    WHERE
      user_id = :user_id
    AND
      workplace_name = :workplace_name
  ";
  $params = array(':user_id' => $user_id, ':workplace_name' => $workplace_name);
  return fetch_query($db, $sql, $params);
}

function insert_history_attendance($db, $user_id, $regist_id, $begin, $separate_finish) {
  $sql = "
    INSERT INTO
      attendance_history(
        user_id,
        regist_id,
        begin,
        separate_finish
      )
    VALUES(:user_id, :regist_id, :begin, :separate_finish)
  ";
  $params = array(':user_id' => $user_id, ':regist_id' => $regist_id, ':begin' => $begin, ':separate_finish' => $separate_finish);
  return execute_query($db, $sql, $params);
  }

function error_check_year_month($begin, $finish, $separation) {
  $start = new DateTime($begin);
  $end = new DateTime($finish);
  $diff = $start->diff($end);
  $different = $diff->d*24*60+$diff->h*60+$diff->i;

  if($different <= 0 || $diff->invert === 1) {
    set_error('終了日時は開始日時より後に設定してください。');
  } else if(0 < $different && $different < $separation) {
    set_error($separation . '分以上の勤務時間が必要です。');
  } else if($different > 2000) {
    set_error('無効な勤務時間です。');
  }
}

?>