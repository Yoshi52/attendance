<header>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#headerNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="headerNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php print(HOME_URL);?>">勤怠登録</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php print(MANAGE_URL);?>">勤怠管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php print(REGIST_URL);?>">職場登録</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php print(LOGOUT_URL);?>">ログアウト</a>
                </li>
            </ul>
        </div>
    </nav>

    <p>ユーザー:<?php print h($user_id); ?></p>
    <?php if(!empty(get_session('main_workplace'))) { ?>
    <p>勤務先:<?php print h(get_session('main_workplace'));?></p>
    <?php } else { ?>
    <p>設定されている勤務先はありません。</p>
    <?php } 
    if(!empty(get_session('now_workplace'))) { ?>
        <p>[<?php print h(get_session('now_workplace')); ?>]で出勤中です。</p>
    <?php } ?>
</header>