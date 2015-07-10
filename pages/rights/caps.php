

<div class="bootstrap-wrapper">
    <h2>Rollen</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php _e("Namen", ClubCore::$TEXT_DOMAIN) ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (Cap::findAll() as $key => $role) { ?>
                <tr>
                    <td id="name_<?php echo $role->rid ?>"><?php echo $role->name ?></td>
                    <td>
                        <span class="editFields" id="editFields_<?php echo $role->rid ?>">
                            <a class="lnkEdit" data-id="<?php echo $role->rid ?>" href="">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>&nbsp;
                            <a href="" class="lnkDel" data-id="<?php echo $role->rid ?>">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
                        </span>
                        <span class="saveFields" id="saveFields_<?php echo $role->rid ?>">
                            <a class="lnkSave" data-id="<?php echo $role->rid ?>" href="" data-toggle="tooltip" data-placement="top" title="<?php _e("Speichern", ClubCore::$TEXT_DOMAIN) ?>">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                            </a>&nbsp;
                            <a class="lnkCancel" data-id="<?php echo $role->rid ?>" href="" data-toggle="tooltip" data-placement="top" title="<?php _e("Abbrechen", ClubCore::$TEXT_DOMAIN) ?>">
                                <span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
                            </a>
                        </span>
                    </td>
                </tr>
                <!--
                <tr>
                    <td colspan="4">
                        <?php
                            var_dump(get_role($role->name));
                        ?>
                    </td>
                </tr>
                -->
            <?php } ?>
        </tbody>
    </table>
    <br />
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#mdlNewCap"><?php _e("Neu", ClubCore::$TEXT_DOMAIN) ?></button>
    <div class="modal fade" id="mdlNewCap" tabindex="-1" role="dialog" aria-labelledby="mdlNewCapLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e("Neue Rechte", ClubCore::$TEXT_DOMAIN) ?></h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label for="txtRoleNameEdit"><?php _e("Namen", ClubCore::$TEXT_DOMAIN) ?></label>
                            <input type="text" class="form-control" id="txtRoleNameEdit" placeholder="<?php _e("Namen", ClubCore::$TEXT_DOMAIN) ?>" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e("Schliessen", ClubCore::$TEXT_DOMAIN) ?></button>
                    <button type="button" id="btnSave" class="btn btn-primary"><?php _e("Speichern", ClubCore::$TEXT_DOMAIN) ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="mdlDel" tabindex="-1" role="dialog" aria-labelledby="mdlDelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e("Neue Rolle", ClubCore::$TEXT_DOMAIN) ?></h4>
                </div>
                <div class="modal-body">
                    <span id="roleQuestion"></span>
                    <input type="hidden" id="txtRoleDeleteId" value="-1" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e("Abbrechen", ClubCore::$TEXT_DOMAIN) ?></button>
                    <button type="button" id="btnAskRoleDel" class="btn btn-primary"><?php _e("Löschen", ClubCore::$TEXT_DOMAIN) ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var roles = {};
    var origData = {};
    var wpAjaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
<?php foreach ($roles as $key => $r) { ?>
        roles["<?php echo $key ?>"] = "<?php echo $r ?>";
<?php } ?>
    $(document).ready(function () {
        $(".editFields").show();
        $(".saveFields").hide();
        
        $("#btnAskRoleDel").on("click", function(event){
            event.preventDefault();
            var data = {
                action: 'club_delete_role',
                id: $("#txtRoleDeleteId").val()
            };
            $.post(wpAjaxUrl, data, function (response) {
                if (response !== "error") {
                    location.reload();
                }
            });
        });
        
        $(".lnkDel").on("click", function (event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            $("#txtRoleDeleteId").val(id);
            var displayName = $("#displayName_" + id).html();
            setRoleDeleteQuestion(displayName, $("#roleQuestion"));
            $("#mdlDel").modal('show');
        });

        $("#btnSave").on("click", function (event) {
            event.preventDefault();
            var data = {
                action: 'club_save_role',
                name: $("#txtNameEdit").val(),
                displayName: $("#txtNameEdit").val(),
                parrent: $("#selParrentEdit").val(),
                id: "-1"
            };
            $.post(wpAjaxUrl, data, function (response) {
                if (response !== "error") {
                    location.reload();
                }
            });
        })

        function cancel(id) {
            $("#name_" + id).html(origData.name);
            $("#displayName_" + id).html(origData.displayName);
            $("#parrent_" + id).html(origData.parrent);
            $("#editFields_" + id).show();
            $("#saveFields_" + id).hide();
            origData = undefined;
        }

        $(".lnkEdit").on("click", function (event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var name = $("#name_" + id).html();
            var displayName = $("#displayName_" + id).html();
            var parrent = $("#parrent_" + id).html();
            if (origData !== undefined) {
                cancel(origData.id);
            }
            origData = {
                "id": id,
                "name": name,
                "displayName": displayName,
                "parrent": parrent
            };
            $("#name_" + id).html("");
            $("#displayName_" + id).html("");
            $("#parrent_" + id).html("");
            var input_name = $("<input>").attr({
                "type": "text",
                "value": name,
                "id": "txtName"
            });
            var input_displayName = $("<input>").attr({
                "type": "text",
                "value": displayName,
                "id": "txtDisplayName"
            });
            var select = $("<select>");
            select.attr("id", "selParrent");
            $.each(roles, function (key, name) {
                var tmp = $("<option>").attr("value", key).html(name);
                if (parrent === name) {
                    tmp.attr("selected", "selected");
                }
                select.append(tmp);
            });
            $("#name_" + id).append(input_name);
            $("#displayName_" + id).append(input_displayName);
            $("#parrent_" + id).append(select);
            $("#editFields_" + id).hide();
            $("#saveFields_" + id).show();
            return false;
        });

        $(".lnkCancel").on("click", function (event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            cancel(id);
        });

        $(".lnkSave").on("click", function (event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var data = {
                'action': 'club_save_role',
                name: $("#txtName").val(),
                displayName: $("#txtDisplayName").val(),
                parrent: $("#selParrent").val(),
                id: id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(wpAjaxUrl, data, function (response) {
                if (response !== "error") {
                    data = JSON.parse(response);
                    origData.id = data.rid;
                    origData.name = data.name;
                    origData.displayName = data.displayName;
                    origData.parrent = roles[data.parrent];
                    cancel(origData.id);
                }
            });
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    });
</script>