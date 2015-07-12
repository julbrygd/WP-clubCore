<?php
    $wpRoles = get_editable_roles();
    $numRoles = 0;
    $roles = array();
    foreach ($wpRoles as $key=>$role) {
        $roles[$numRoles] = array(
            "name" => $wpRoles[$key]["name"],
            "caps" => $wpRoles[$key]["capabilities"],
            "key" => $key
        );
        $numRoles++;
    }
?>

<div class="bootstrap-wrapper">
    <h2>Rollen</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php _e("Namen", ClubCore::$TEXT_DOMAIN) ?></th>
            <?php foreach($roles as $key=>$role) { ?>
                <th><?php echo $role["name"] ?></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach (Cap::findAll() as $key => $cap) { ?>
                <tr>
                    <td id="capName_<?php echo $cap->cid ?>"><?php echo $cap->name ?></td>
                <?php foreach($roles as $key=>$role) { ?>
                    <td>
                        <?php
                        $hasCap = isset($role['caps'][$cap->name]);
                        $disable = "";
                        if($role["key"] == 'administrator'){
                            $disable = ' disabled="disabled"';
                        }
                        if($hasCap &&  $role['caps'][$cap->name]) { ?>
                        <input<?php echo $disable?> class="roleCap" data-role="<?php echo $role["key"]?>" data-cap="<?php echo $cap->name ?>" type="checkbox" checked="checked"/>
                        <?php } else { ?>
                        <input<?php echo $disable?> class="roleCap" data-role="<?php echo $role["key"]?>" data-cap="<?php echo $cap->name ?>" type="checkbox" />
                        <?php } ?>
                    </td>
                <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br />
    <button type="button" class="btn btn-primary btn-lg" id="btnSaveCap"><?php _e("Speichern", ClubCore::$TEXT_DOMAIN) ?></button>
</div>

<script type="text/javascript">
    var wpAjaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
    var capChanges = {}

    $(document).ready(function () {
        
        $('.roleCap').on('change', function(){
            var role = $(this).data("role");
            var cap = $(this).data('cap');
            var checked = $(this).is(":checked");
            if(capChanges[cap] === undefined) {
                capChanges[cap] = {};
            }
            if(capChanges[cap][role] !== undefined){ 
                delete capChanges[cap][role];
            } else {
                capChanges[cap][role] = checked;
            }           
        });
        
        $("#btnSaveCap").on("click", function(event){
            event.preventDefault();
            var data = {
                'action': 'club_save_cap_changes',
                changes: capChanges
            };
            $.post(wpAjaxUrl, data, function (response) {
                if (response === "error") {
                    alert(JSON.stringify(response));
                }
            });
        });
    });
</script>