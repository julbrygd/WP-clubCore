<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "WP-clubCore.php";

class Role {

    protected $_id = -1;
    protected $_name;
    protected $_displayName;
    protected $_parrent;

    function __construct($_name, $_displayName, $_parrent, $id = -1) {
        $this->_id = $id;
        $this->_name = $_name;
        $this->_displayName = $_displayName;
        $this->_parrent = $_parrent;
    }

    public function __set($name, $value) {
        switch ($name) {
            case "name": $this->_name = $value;
                break;
            case "displayName": $this->_displayName = $value;
                break;
            case "parrent": $this->_parrent = $value;
                break;
        }
    }

    public function __get($name) {
        switch ($name) {
            case "name": return $this->_name;
                break;
            case "displayName": return $this->_displayName;
                break;
            case "parrent": return $this->_parrent;
                break;
            case "rid": return $this->_id;
                break;
        }
    }

    public function toJson() {
        $obj = new stdClass();
        $obj->name = $this->_name;
        $obj->displayName = $this->_displayName;
        $obj->parrent = $this->_parrent;
        $obj->rid = $this->_id;
        return json_encode($obj);
    }

    public static function fromStdObj($obj) {
        return new Role(
                $obj->name, 
                $obj->displayName, 
                $obj->parrent, 
                $obj->rid
                );
    }

    public static function findAll() {
        global $wpdb;
        $var = $wpdb->get_results(
                "SELECT * FROM " . ClubCore::getInstance()->table_name_roles
        );
        $ret = array();
        foreach ($var as $key => $obj) {
            $ret[] = Role::fromStdObj($obj);
        }
        return $ret;
    }

    public static function findById($id) {
        global $wpdb;
        $sql = "SELECT * FROM " . ClubCore::getInstance()->table_name_roles . " WHERE rid = " . $id;
        $var = $wpdb->get_row(
                $sql, OBJECT
        );
        if($var == null){
            return new Role();
        }
        return self::fromStdObj($var);
    }

    public function save($role = null) {
        global $wpdb;
        if ($role == null) {
            $role = $this;
        }
        if ($role->rid == -1) {
            return $wpdb->insert(
                            ClubCore::getInstance()->table_name_roles, array(
                        "name" => $role->name,
                        "displayName" => $role->displayName,
                        "parrent" => $role->parrent
                            ), array("%s", "%s", "%s"));
        } else {
            return $wpdb->update(
                            ClubCore::getInstance()->table_name_roles, array(
                        "name" => $role->name,
                        "displayName" => $role->displayName,
                        "parrent" => $role->parrent
                            ), array("rid" => $role->rid), array("%s", "%s", "%s"), array("%d")
            );
        }
    }
    
    public function delete(){
        global $wpdb;
        return $wpdb->delete( ClubCore::getInstance()->table_name_roles, array( 'rid' => $this->_id ), array( '%d' ) );
    }

    public static function saveAjaxObj() {
        if (isset($_POST["action"]) && $_POST["action"] == "club_save_role") {
            $role = self::findById($_POST["id"]);
            $role->name = $_POST["name"];
            $role->displayName = $_POST["displayName"];
            $role->parrent = $_POST["parrent"];
            $ret = $role->save();
            $wprole = get_role($role->name);
            if($wprole != null){
                remove_role($role->name);
            }
            $parrent = get_role($role->parrent);
            add_role($role->name, $role->displayName, $parrent->capabilities);
            $wprole = get_role($role->name);
            if ((is_bool($ret) && $ret == false) && $wprole == NULL){
                echo "error";
            } else {
                echo $role->toJson();
            }
        } else {
            echo "error";
        }
        wp_die();
    }
    
    public static function deleteAjaxObj() {
        if (isset($_POST["action"]) && $_POST["action"] == "club_delete_role") {
            $role = self::findById($_POST["id"]);
            $ret = $role->delete();
            if (is_bool($ret) && $ret == false) {
                echo "error";
            } else {
                echo $role->toJson();
            }
        } else {
            echo "error";
        }
        wp_die();
    }

}
