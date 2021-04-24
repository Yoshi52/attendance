<!DOCTYPE html>
<html lang="ja">
<?php include VIEW_PATH . 'templates/head.php'; ?>
<head>
    <title>ログイン</title>
    <link rel="stylesheet" href="<?php print (STYLESHEET_PATH . 'all.css'); ?>">
</head>
<body>
<?php include VIEW_PATH . 'templates/header.php'; ?>
<div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <h1>ログイン</h1>
    <form method="post" action="login_process_c.php">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <spqn class="input-group-text" id="text1a">ユーザーID:</span>
        </div>
            <input type="text" class="form-control" name="user_id" aria-describedby="text1a">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <spqn class="input-group-text" id="text1b">メールアドレス:</span>
        </div>
            <input type="email" class="form-control" name="email" aria-describedby="text1b">
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="ログイン">
    </div>
    </form>
</div>
</body>
</html>