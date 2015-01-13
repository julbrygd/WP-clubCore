<?php
$c = ClubCore::getInstance();
include_once $c->PLUGIN_DIR . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "Role.php";
?>
<div class="bootstrap-wrapper">
    <h1>Club Administration - Berechtigung</h1>
</div>
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
        <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
    </div>
</div>

<script>
    $(function () {
        $("#tabs").tabs();
    });
</script>