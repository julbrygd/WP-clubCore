<?php

/**
 * Plugin Name: clubCore
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the plugin.
 * Version: 0.0.1
 * Author: julbrygd
 * Author URI: http://URI_Of_The_Plugin_Author
 * Text Domain: clubCore
 * Domain Path: /locale/
 * License: GPL2
 */
/*  Copyright 2015  Stephan Conrad  (email : stephan.conrad@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

defined('ABSPATH') or die("No script kiddies please!");
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "model" .DIRECTORY_SEPARATOR . "Role.php";

class ClubCore {

    protected $PLUGIN_DIR;
    protected $INSTALL_FILE;
    public static $TEXT_DOMAIN = "clubCore";
    protected static $INSTANCE = NULL;
    protected $_menuSlug = "clubCore_menu";
    protected $vars = array();

    public static function getInstance() {
        if (self::$INSTANCE == NULL) {
            self::$INSTANCE = new ClubCore();
        }
        return self::$INSTANCE;
    }

    private function __construct() {
        $this->PLUGIN_DIR = dirname(__FILE__);
        $this->INSTALL_FILE = $this->PLUGIN_DIR . DIRECTORY_SEPARATOR . "install.php";

        $this->init();
    }

    public function init() {
        global $wpdb;
        load_plugin_textdomain(self::$TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/locale');
        $this->table_name_roles = $wpdb->prefix . "clubCore_roles";
        $this->table_name_caps = $wpdb->prefix . "clubCore_caps";
        $this->table_name_caps_roles = $wpdb->prefix . "clubCore_caps_roles";
        register_activation_hook(__FILE__, array(&$this, 'clubCore_install'));
        add_action('admin_menu', array(&$this, "add_menu"));
        add_action( 'wp_ajax_club_save_role', array("Role", "saveAjaxObj") );
        add_action("wp_ajax_club_delete_role", array("Role", "deleteAjaxObj"));
        $this->adminInit();
    }

    public function adminInit() {
        add_action('admin_enqueue_scripts', array(&$this, "loadStylesScripts"));
    }

    public function add_menu() {
        add_menu_page(
                __("Club", self::$TEXT_DOMAIN), __("Club", self::$TEXT_DOMAIN), 'edit_others_posts', $this->menuSlug, array(&$this, "menu_index")
        );
        add_submenu_page(
                $this->menuSlug, __("Club Berechigungen", self::$TEXT_DOMAIN), __("Club Berechigungen", self::$TEXT_DOMAIN), 'edit_others_posts', "club_menu_rights", array(&$this, "menu_rights"));
    }

    public function menu_index() {
        include $this->PLUGIN_DIR . DIRECTORY_SEPARATOR . "pages/index.php";
    }

    public function menu_rights() {
        
        include $this->PLUGIN_DIR . DIRECTORY_SEPARATOR . "pages/rights.php";
    }

    public function addRole($name, $displayName, $parrent) {
        global $wpdb;
        $parrentRole = get_role($parrent);
        $role = add_role($name, $displayName, $parrentRole->capabilities);
        if ($role == null) {
            $role = get_role($name);
        } else {
            $wpdb->insert(
                    $this->table_name_roles, array(
                "name" => $name,
                "displayName" => $displayName,
                "parrent" => $parrent
                    )
            );
            $rid = $wpdb->insert_id;
        }
    }

    public function __get($name) {
        if ($name == "menuSlug") {
            return $this->_menuSlug;
        } else {
            return $this->vars[$name];
        }
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function clubCore_install() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = array();
        $sql[] = "CREATE TABLE $this->table_name_roles (
            rid int NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            displayName varchar(100) NOT NULL,
            parrent varchar(100) NOT NULL,
            PRIMARY KEY  (rid)
          ) $charset_collate;";

        $sql[] = "CREATE TABLE $this->table_name_caps (
            cid int NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            PRIMARY KEY  (cid)
          ) $charset_collate;";

        $sql[] = "CREATE TABLE $this->table_name_caps_roles (
            rid INT NOT NULL,
            cid INT NOT NULL,
            INDEX role_index (rid),
            INDEX cap_index (cid),
            FOREIGN KEY (rid) REFERENCES $this->table_name_roles(rid),
            FOREIGN KEY (cid) REFERENCES $this->table_name_caps(cid),
            PRIMARY KEY  (rid, cid)
          ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        //$this->addRole("clubAdmin", "Club Admin", "administrator");
    }

    public function loadStylesScripts() {
        $static = plugin_dir_url(__FILE__) . "static";
        
        //wp_enqueue_style("wp_admin_bootstrap", $plugin_css_url . DIRECTORY_SEPARATOR . "wordpress.css");
        //wp_enqueue_script("wp_admin_bootstrap_script", $plugin_js_url . DIRECTORY_SEPARATOR . "bootstrap.min.js", array("jquery"));
        wp_register_script("club_jquery", $static . "/jquery.js");
        wp_register_script("club_jquery_ui", $static . "/jquery-ui.min.js", array("club_jquery"));
        wp_register_script("club_bootstrap_js", $static . "/js/bootstrap.min.js", array("club_jquery"));
        wp_register_style("club_jquery_ui_css", $static . "/jquery-ui.css");
        wp_register_script("club_local_script", $static . "/js/localized.js", array("club_jquery"));
        wp_register_style("club_admin_css", $static . "/style.css");
        wp_register_style("club_admin_bootstrap_css", $static . "/css/wordpress.css");
        wp_enqueue_script("club_jquery_ui");
        wp_enqueue_script("club_bootstrap_js");
        $l10n = array(
            "role_delete" => __("Sind sie sicher, dass sie die Rolle %%s löschen wollen?", self::$TEXT_DOMAIN)
        );
        wp_enqueue_script("club_bootstrap_js");
        wp_localize_script("club_local_script", "club_local", $l10n);
        wp_enqueue_script("club_local_script");
        wp_enqueue_style("club_jquery_ui_css");
        wp_enqueue_style("club_admin_css", array("club_jquery_ui_css"));
        wp_enqueue_style("club_admin_bootstrap_css");
    }

}

ClubCore::getInstance();
