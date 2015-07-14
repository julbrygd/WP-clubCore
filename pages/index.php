<h1><?php _e('Club Plugin', ClubCore::$TEXT_DOMAIN) ?></h1>

<p>
    <?php _e('Willkommen beim Club Plugin ihnen stehen folgende Möglichkeiteten zurverfügung:', ClubCore::$TEXT_DOMAIN) ?>
    <li>
        <ul><a href="<?php echo add_query_arg(array('page' => 'club_menu_rights'), admin_url('admin.php'));?>">Test</a></ul>
    </li>
</p>