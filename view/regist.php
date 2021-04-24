<!DOCTYPE html>
<html lang="ja">
<?php include VIEW_PATH . 'templates/head.php'; ?>
<head>
    <title>職場登録</title>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . 'all.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <h1>職場登録</h1>
    <form method="post" action="workplace_regist_c.php">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <spqn class="input-group-text" id="text1a">職場名:</spqn>
        </div>
        <input type="text" class="form-control" name="workplace_name" aria-describedby="text1a">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="text1b">時給:</span>
        </div>
        <input type="text" class="form-control" name="wage" aria-describedby="text1b">
        <div class="input-group-append">
            <span class="input-group-text" id="text1a">円</span>
        </div>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="text1c">深夜時給:</span>
        </div>
        <input type="text" class="form-control" name="midnight_wage" aria-describedby="text1c">
        <div class="input-group-append">
            <span class="input-group-text" id="text1a">円</span>
        </div>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="text1d">深夜時間:</span>
        </div>
        <select name="mid_sta" class="form-control" aria-describedby="text1d">
            <?php for($i = 1; $i <= 24; $i++) { ?>
                <option value="<?php print $i; ?>"
                <?php if($i === 22) {echo 'selected';} ?>>
                <?php print $i; ?>時</option>
            <?php } ?>
        </select>
        〜
        <select name="mid_fin" class="form-control" aria-describedby="text1d">
            <?php for($i = 1; $i <= 24; $i++) { ?>
                <option value="<?php print $i; ?>"
                <?php if($i === 5) {echo 'selected';} ?>>
                <?php print $i; ?>時</option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="text1e">給料計算の区切り:</span>
        </div>
            <select name="separation" class="form-control" aria-describedby="text1e">
                <option value="15">15分ごと</option>
                <option value="30">30分ごと</option>
                <option value="60">60分ごと</option>
            </select>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" name="regist" value="登録">
    </div>
    </form>
    <br>

    <h4>登録済みの職場</h4>
    <div class="table-responsive">
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th nowrap>職場名</th>
                <th nowrap>深夜時間</th>
                <th nowrap>時給</th>
                <th nowrap>深夜時給</th>
                <th nowrap>給料計算の区切り</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($workplaces as $workplace) { ?>
            <tr>
                <td nowrap><?php print h($workplace['workplace_name']); ?></td>
                <td nowrap><?php print h($workplace['mid_start'] . '~' . $workplace['mid_finish']); ?></td>
                <td nowrap><?php print h($workplace['hourly_wage']); ?>円</td>
                <td nowrap><?php print h($workplace['midnight_wage']); ?>円</td>
                <td nowrap><?php print h($workplace['separation']); ?>分ごと</td>
                <td nowrap>
                    <form method="post" action="workplace_set_main_c.php">
                    <input type="hidden" name="set_main_workplace">
                    <input type="hidden" name="main_workplace" value="<?php print $workplace['workplace_name']; ?>">
                    <input type="submit" value="メインの職場に設定">
                    </form>
                </td>
                <td nowrap>
                    <form method="post" action="workplace_delete_c.php">
                    <input type="hidden" name="delete_workplace">
                    <input type="hidden" name="delete_id" value="<?php print $workplace['regist_id']; ?>">
                    <input type="hidden" name="delete_name" value="<?php print $workplace['workplace_name']; ?>">
                    <input type="submit" class="btn btn-danger" value="削除">
                    </form>
                </td>
            </tr>
        </tbody>
    <?php } ?> 
    </table>
    </div>
</div>
</body>
</html>