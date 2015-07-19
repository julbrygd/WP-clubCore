<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ClubModule {
    private $_name;
    private $_active;
    private $_class;
    private $_file;
    
    function __construct($_name, $_active, $_class, $_file) {
        $this->_name = $_name;
        $this->_active = $_active;
        $this->_class = $_class;
        $this->_file = $_file;
    }

     public function __set($name, $value) {
         switch ($name) {
             case "name": $this->_name = $value; break;
             case "active": $this->_active = $value; break;
             case "class": $this->_class = $value; break;
             case "file": $this->_file = $value; break;
         }
     } 
     
     public function __get($name) {
         switch ($name) {
             case "name": return $this->_name; break;
             case "active": return $this->_active; break;
             case "class": return $this->_class; break;
             case "file": return $this->_file; break;
         }
     }
}