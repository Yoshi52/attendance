<!DOCTYPE html>
<html lang="ja">
<?php include VIEW_PATH . 'templates/head.php'; ?>
<head>
    <title>勤怠登録</title>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . 'all.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <h1>勤怠登録</h1>
    <?php if($is_start_work === FALSE) { ?>
        <form method="post"  class="text-center" action="attendance_c.php">
        <input type="hidden" name="start">
        <input class="btn-circle-border-simple" type="submit" value="出勤">
        </form>
    <?php } else if($is_start_work === TRUE){ ?>
        <form method="post" class="text-center" action="attendance_finish_c.php">
        <input type="hidden" name="finish">
        <input class="btn-circle-border-simple" type="submit" value="退勤">
        </form>
    <?php } ?>

    <div class="row mt-5 ml-5 text-center">
        <table class="table col-md-10">
            <thead>
                <tr>
                    <th>勤務先</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($workplaces as $value) { ?>
                    <tr>
                        <td><?php print h($value['workplace_name']); ?></td>
                        <td>
                            <form method="post" action="attendance_set_main_c.php">
                                <input type="hidden" name="set_main_workplace">
                                <input type="hidden" name="main_workplace" value="<?php print $value['workplace_name']; ?>">
                                <input type="submit" value="勤務先に設定">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>