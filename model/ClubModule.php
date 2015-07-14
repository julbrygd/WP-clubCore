<?php

class ClubModule {
    protected $name;
    protected $textdomain;
    protected $cap;
    protected $slug;
    protected $class;
    protected $method;
    
    function __construct($name, $textdomain, $cap, $slug, $class, $method) {
        $this->name = $name;
        $this->textdomain = $textdomain;
        $this->cap = $cap;
        $this->slug = $slug;
        $this->class = $class;
        $this->method = $method;
    }

    function getName() {
        return $this->name;
    }

    function getTextdomain() {
        return $this->textdomain;
    }

    function getCap() {
        return $this->cap;
    }

    function getSlug() {
        return $this->slug;
    }

    function getClass() {
        return $this->class;
    }

    function getMethod() {
        return $this->method;
    }


}
