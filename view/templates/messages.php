<?php
foreach(get_errors() as $error) { ?>
    <p class="alert alert-danger"><?php print $error . '<br>'; ?></p>
<? } ?>
<?php
foreach (get_messages() as $message) { ?>
     <p class="alert alert-success"><?php print $message . '<br>'; ?></p>
<?php } ?>
