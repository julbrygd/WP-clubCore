<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'WP-clubCore.php';
require_once plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'ClubClass.php';

class ClubList implements ClubClass {
    private $club;
    
    public function __construct() {
        $this->club = ClubCore::getInstance();
    }


    public function getMenu() {
        return array(
            'club_menu_list' => array(
                'cap' => 'club_show_list',
                'title' => __('Club Listen', ClubCore::$TEXT_DOMAIN),
                'functionName' => array(&$this, 'show_lists')
            )
        );
    }

}