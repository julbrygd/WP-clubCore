<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$mods = get_option(ClubCore::$MODEL_KEY);
?>
<div class="bootstrap-wrapper">
    <h1><?php echo __("Club Modul Administration", ClubCore::$TEXT_DOMAIN) ?></h1>
    <br />
    <div class="container-fluid" style="background-color: white">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($mods as $key=>$mod) { ?>
                <tr>
                    <td><?php echo $mod->name ?></td>
                    <?php 
                    $checked = '';
                    if($mod->active) {
                        $checked = ' checked="checked"';
                    }
                    ?>
                    <td><input type="checkbox"<?php echo $checked?> /></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <br />
        <button class="btn btn-default">Speichern</button>
        <br />
    </div>
</div>