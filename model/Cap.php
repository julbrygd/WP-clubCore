<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "WP-clubCore.php";

class Cap {

    protected $_id;
    protected $_name;

    function __construct($name, $id = -1) {
        $this->_id = $id;
        $this->_name = $name;
    }

    public function __set($name, $value) {
        switch ($name) {
            case "name": $this->_name = $value;
                break;
        }
    }

    public function __get($name) {
        switch ($name) {
            case "name": return $this->_name;
            case "cid": return $this->_id;
        }
    }

    public function toJson() {
        $obj = new stdClass();
        $obj->name = $this->_name;
        $obj->cid = $this->_id;
        return json_encode($obj);
    }

    public static function fromStdObj($obj) {
        return new Cap(
                $obj->name, $obj->cid
        );
    }

    public static function findAll() {
        global $wpdb;
        $var = $wpdb->get_results(
                "SELECT * FROM " . ClubCore::getInstance()->table_name_caps
        );
        $ret = array();
        foreach ($var as $key => $obj) {
            $ret[] = Cap::fromStdObj($obj);
        }
        return $ret;
    }

    public static function findById($id) {
        global $wpdb;
        $sql = "SELECT * FROM " . ClubCore::getInstance()->table_name_caps . " WHERE cid = " . $id;
        $var = $wpdb->get_row(
                $sql, OBJECT
        );
        if ($var == null) {
            return new Cap();
        }
        return self::fromStdObj($var);
    }

    public function save($cap = null) {
        global $wpdb;
        if ($cap == null) {
            $cap = $this;
        }
        if ($cap->cid == -1) {
            $adm = get_role('administrator');
            $adm->add_cap($cap->name);
            
            return $wpdb->insert(
                            ClubCore::getInstance()->table_name_caps, array(
                        "name" => $cap->name,
                            ), array("%s"));
        } else {
            return $wpdb->update(
                            ClubCore::getInstance()->table_name_caps, array(
                        "name" => $cap->name,
                            ), array("cid" => $cap->cid), array("%s"), array("%d")
            );
        }
    }

    public function delete() {
        global $wpdb;
        return $wpdb->delete(ClubCore::getInstance()->table_name_caps, array('cid' => $this->_id), array('%d'));
    }

    public static function saveChanges() {

        if (isset($_POST["action"]) && $_POST["action"] == "club_save_cap_changes") {
            if (isset($_POST["changes"])) {
                if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'club_save_cap_changes')) {                 
                    foreach ($_POST['changes'] as $cap => $data) {
                        foreach ($data as $role => $granted) {
                            $wpRole = get_role($role);
                            $test = false;
                            if ($granted == "false") {
                                $test = FALSE;
                            } else {
                                $test = TRUE;
                            }
                            if ($test) {
                                $wpRole->add_cap($cap, TRUE);
                            } else {
                                $wpRole->remove_cap($cap);
                            }
                        }
                    }
                    echo json_encode(array('nonce' => wp_create_nonce('club_save_cap_changes')));
                } else {
                    echo json_encode(array('error' => 'nonce was fasle'));
                }
            }
        }
        wp_die();
    }

}
