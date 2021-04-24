<?php

function insert_wages($db, $last_id, $minutes, $wage) {
    $sql = "
      INSERT INTO
        wages(
            history_id,
            worktime,
            wage)
    VALUES(:history_id, :worktime, :wage)
    ";
    $params = array(':history_id' => $last_id, ':worktime' => $minutes, ':wage' => $wage);
    return execute_query($db, $sql, $params);
}

function select_last_history($db, $last_id) {
    $sql = "
      SELECT
        begin,
        separate_finish
      FROM
        attendance_history
      WHERE
        history_id = :history_id
      LIMIT 1
    ";
    $params = array(':history_id' => $last_id);
    return fetch_query($db, $sql, $params);
}

function select_last_workplace($db, $regist_id) {
    $sql = "
      SELECT
        mid_start,
        mid_finish,
        hourly_wage,
        midnight_wage,
        separation
      FROM
        regist_workplace
      WHERE
        regist_id = :regist_id
    ";
    $params = array(':regist_id' => $regist_id);
    return fetch_query($db, $sql, $params);
  }

function min_diff($fin, $sta) {
    $t1 = new DateTime($fin);
    $t2 = new DateTime($sta);
    if($t1 < $t2) {
        return 1440 - ($t2->getTimestamp() - $t1->getTimestamp()) / 60;
    } else {
        return ($t1->getTimestamp() - $t2->getTimestamp()) / 60;
    }
}

function daywage($workday, $hourly_wage, $midnight_wage, $mid_sta, $mid_fin) {
    $wage = $hourly_wage / 60;
    $mid = $midnight_wage / 60;
    if($workday !== 0){
        return $workday * ($wage * min_diff($mid_sta, $mid_fin) + $mid * min_diff($mid_fin, $mid_sta));
    } else {
        return 0;
    }
}

function get_workday($db, $last_id) {
    $sql = "
      SELECT
        TIMESTAMPDIFF(day, begin, separate_finish) AS workday
      FROM
        attendance_history
      WHERE
        history_id = :history_id
      LIMIT 1
    ";
    $params = array(':history_id' => $last_id);
    return fetch_query($db, $sql, $params);
}

function wage($begin, $finish, $workday, $mid_sta, $mid_fin, $midnight_wage, $hourly_wage) {
    $mid_finish_hour = date('H', strtotime($mid_fin));
    $mid_start_hour = date('H', strtotime($mid_sta));
    $wage = $hourly_wage / 60;
    $mid_wage = $midnight_wage / 60;

    $begin_time = new DateTime($begin);
    $finish_time = new DateTime($finish);
    $time1 = new DateTime(date('00:00:00'));
    $time2 = new DateTime(date($mid_finish_hour . ':00:00'));
    $time3 = new DateTime(date($mid_start_hour . ':00:00'));
    $time4 = new DateTime(date('24:00:00'));
    $day_wage = daywage($workday, $hourly_wage, $midnight_wage, $mid_sta, $mid_fin);
    
    if($time1 <= $begin_time && $begin_time < $time2) {
        if($time1 <= $finish_time && $finish_time < $time2) {
            if($begin_time <= $finish_time) {
                return ($day_wage + $mid_wage * min_diff($finish, $begin));
            } else {
                return ($day_wage + $mid_wage * (min_diff($mid_fin, $begin) + min_diff($finish, $mid_sta)) + $wage * min_diff($mid_sta, $mid_fin));
            }
        } else if($time2 <= $finish_time && $finish_time < $time3) {
            return ($day_wage + $mid_wage * min_diff($mid_fin, $begin) + $wage * min_diff($finish, $mid_fin));
        } else if($time3 <= $finish_time && $finish_time < $time4) {
            return ($day_wage + $mid_wage * (min_diff($mid_fin, $begin) + min_diff($finish, $mid_sta)) + $wage * min_diff($mid_sta, $mid_fin));
        }
    } else if($time2 <= $begin_time && $begin_time < $time3) {
        if($time1 <= $finish_time && $finish_time < $time2) {
            return ($day_wage + $wage * min_diff($mid_sta ,$begin) + $mid_wage * min_diff($finish, $mid_sta));
        } else if($time2 <= $finish_time && $finish_time < $time3) {
            if($begin_time <= $finish_time) {
                return ($day_wage + $wage * min_diff($finish, $begin));
            } else {
                return ($day_wage + $wage * (min_diff($mid_sta, $begin) + min_diff($finish, $mid_fin)) + $mid_wage * (min_diff($mid_fin, $mid_sta)));
            }
        } else if($time3 <= $finish_time  && $finish_time <= $time4) {
            return ($day_wage + $wage * min_diff($mid_sta, $begin) + $mid_wage * min_diff($finish, $mid_sta));
        }
    } else if($time3 <= $begin_time  && $begin_time < $time4) {
        if($time1 <= $finish_time && $finish_time<= $time2) {
            return ($day_wage + $mid_wage * (min_diff($finish, $begin)));
        } else if($time2 <= $finish_time && $finish_time < $time3) {
            return ($day_wage + $mid_wage * min_diff($mid_fin, $begin) + $wage * min_diff($finish, $mid_fin));
        } else if($time3 <= $finish_time && $finish_time < $time4) {
            if ($begin_time <= $finish_time) {
                return ($day_wage + $mid_wage * min_diff($finish, $begin));
            } else {
                return ($day_wage + $mid_wage * (min_diff($mid_fin, $begin) + min_diff($finish, $mid_sta)) + $wage * min_diff($mid_sta, $mid_fin));
            }
        }
    }
    return '';
}

function get_minutes($db, $last_id) {
    $sql = "
      SELECT
      TIMESTAMPDIFF(minute, begin, separate_finish) AS worktime
      FROM
        attendance_history
      WHERE
        history_id = :history_id
      LIMIT 1
    ";
    $params = array(':history_id' => $last_id);
    return fetch_query($db, $sql, $params);
}

?>