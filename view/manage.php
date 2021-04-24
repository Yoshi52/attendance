<!DOCTYPE html>
<html lang ="ja">
<?php include VIEW_PATH . 'templates/head.php'; ?>
<head>
    <title>勤怠管理</title>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . 'all.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <h1>勤怠管理</h1>
    <h2>勤務履歴追加</h2>
    <form method="post" action="manage_regist_c.php">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <spqn class="input-group-text" id="text1a">勤務先:</spqn>
            </div>
        <select name="workplace" class="form-control" aria-describedby="text1a">
            <?php foreach($my_workplace as $value) { ?>
                <option value="<?php print $value['workplace_name']; ?>" 
                <?php if($_SESSION['main_workplace'] === $value['workplace_name']) {echo 'selected';} ?>>
                <?php print h($value['workplace_name']); ?></option>
            <?php } ?>
        </select>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <spqn class="input-group-text" id="text1b">勤務開始日:</spqn>
            </div>
        <select name="start_year" class="form-control" aria-describedby="text1b">
            <?php $this_year = (int)date('Y');
            while($this_year >= 2020) { ?>
                <option value="<?php print $this_year;
                ?>"><?php print h($this_year); ?></option>
                <?php $this_year--;
            } ?>
        </select>年
        <select name="start_month" class="form-control" aria-describedby="text1b">
            <?php $this_month = (int)date('m');
                for($month = 1; $month <= 12; $month++) { ?>
                <option value="<?php print $month; ?>"
                <?php if($this_month === $month) {echo 'selected';} ?>>
                <?php print h($month); ?></option>
            <?php } ?>
        </select>月
        <select name="start_day" class="form-control" aria-describedby="text1b">
            <?php $today = (int)date('d');
                for($day = 1; $day <= 31; $day++) { ?>
                <option value="<?php print $day; ?>"
                <?php if($today === $day) {echo 'selected';} ?>>
                <?php print h($day); ?></option>
            <?php } ?>
        </select>日
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <spqn class="input-group-text" id="text1c">勤務開始時間:</spqn>
            </div>
        <select name="start_hour" class="form-control" aria-describedby="text1c">
            <?php for($hour = 0; $hour < 24; $hour++) { ?>
                <option value="<?php print $hour; ?>"><?php print h($hour); ?></option>
            <?php } ?>
        </select>時
        <select name="start_minute" class="form-control" aria-describedby="text1c">
            <?php $minute=array('0','15','30','45');
            foreach($minute as $value) { ?>
                <option value="<?php print $value; ?>"><?php print h($value); ?></option>
            <?php } ?>
        </select>分
        </div>
        
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <spqn class="input-group-text" id="text1d">勤務終了日:</spqn>
            </div>
        <select name="finish_year" class="form-control" aria-describedby="text1d">
            <?php $this_year = (int)date('Y'); 
            while($this_year >= 2020) { ?>
                <option value="<?php print $this_year;
                ?>"><?php print h($this_year); ?></option>
                <?php $this_year--;
            } ?>
        </select>年
        <select name="finish_month" class="form-control" aria-describedby="text1d">
            <?php for($month = 1; $month <= 12; $month++) { ?>
                <option value="<?php print $month; ?>"
                <?php if($this_month === $month) {echo 'selected';} ?>>
                <?php print h($month); ?></option>
            <?php } ?>
        </select>月
        <select name="finish_day" class="form-control" aria-describedby="text1d">
            <?php for($day = 1; $day <= 31; $day++) { ?>
                <option value="<?php print $day; ?>"
                <?php if($today === $day) {echo 'selected';} ?>>
                <?php print h($day); ?></option>
            <?php } ?>
        </select>日
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <spqn class="input-group-text" id="text1e">勤務終了時間:</spqn>
            </div>
        <select name="finish_hour" class="form-control" aria-describedby="text1e">
            <?php for($hour = 0; $hour < 24; $hour++) { ?>
                <option value="<?php print $hour; ?>"><?php print h($hour); ?></option>
            <?php } ?>
        </select>時
        <select name="finish_minute" class="form-control" aria-describedby="text1e">
            <?php $minute=array('0','15','30','45');
            foreach($minute as $value) { ?>
                <option value="<?php print $value; ?>"><?php print h($value); ?></option>
            <?php } ?>
        </select>分
        </div>
        <div class="text-center">
            <input type="submit" class="btn btn-primary" name="regist" value="登録">
        </div>
    </form>

    <h2>勤務履歴</h2>
    <form method="post" action="manage_c.php">
        <div class="input-group mb-3">
            <select name="year_month" class="form-control">
                <?php 
                while($first_year <= $last_year) {
                    $month = 1;
                    while($month <= 12) {
                        $data = select_specific_attendance($db, $user_id, $first_year, $month);
                        if(!empty($data)) { ?>
                            <option value ='<?php print $first_year . $month; ?>' 
                                <?php if ($last_year . $last_month === $first_year . $month) {echo 'selected';} ?>>
                            <?php print h($first_year . '年' . $month . '月'); ?>
                            </option>
                        <?php } 
                        $month++;
                    }
                    $first_year++;
                }  ?>
            </select>
        <input type="submit" class="btn btn-primary" name="change" value="表示">
        </div>
    </form>
    <?php if(!empty($selected_year) || !empty($selected_month)) { ?>
    <h4><?php print h($selected_year); ?>年の合計金額:<?php print h($sum_wage_year['sum_wage']); ?>円</h4>
    <h4><?php print h($selected_month); ?>月の合計金額:<?php print h($sum_wage_month['sum_wage']); ?>円</h4>
    <?php } else { ?>
        <p>勤務履歴はありません。</p>
    <?php } ?>
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>勤務先</th>
                <th>労働時間</th>
                <th>給料</th>
                <th></th>
            </tr>
        </thead>
        <?php foreach($year_month_attendance as $value) { ?>
        <tbody>
            <tr>
                <td><?php print h(date('j', strtotime($value['begin']))); ?>日</td>
                <td><?php print h($value['workplace_name']); ?></td>
                <td><?php print h(floor($value['worktime']/60) . '時間' . $value['worktime']%60 . '分'); ?></td>
                <td><?php print h($value['wage']); ?>円</td>
                <td>
                    <form method="post" action="manage_delete_c.php">
                    <input type="hidden" name="history_id" value="<?php print $value['history_id']; ?>">
                    <input type="submit" name="delete" value="削除">
                    </form>
                </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
</div>
</body>
</html>