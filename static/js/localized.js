

function setRoleDeleteQuestion(role, elem){
    var text = club_local.role_delete;
    $(elem).html(text.replace("%%s", role));
}

