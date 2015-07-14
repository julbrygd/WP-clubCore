<?php
$c = ClubCore::getInstance();
include_once $c->PLUGIN_DIR . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "Role.php";
?>
<div class="bootstrap-wrapper">
    <h1>Club Administration - Berechtigung</h1>
</div>
<div id="club_messages"></div>
<br />
<div id="tabs">
    <ul>
        <li><a href="#rights"><?php _e("Rollen", ClubCore::$TEXT_DOMAIN) ?></a></li>
        <li><a href="#roles"><?php _e("Rechte", ClubCore::$TEXT_DOMAIN) ?></a></li>
    </ul>
    <div id="rights">
        <p>
            <?php include dirname(__FILE__) . "/rights/roles.php"; ?>
        </p>
    </div>
    <div id="roles">
        <p>
            <?php include dirname(__FILE__) . "/rights/caps.php"; ?>
        </p>
    </div>
</div>

<script>
    $(function () {
        $("#tabs").tabs();
        $('#club_messages').hide();
    });
</script>