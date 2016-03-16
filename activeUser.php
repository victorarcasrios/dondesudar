<?php

/**
 * Description of activeUser
 *
 * @author Víctor
 */
class activeUser {

    private $data = array();
    private $textStatus = array(
        "s" => "admin",
        "a" => "user",
        "p" => "notYetAccepted",
        "b" => "banned",
        "g" => "guest"
    );

    function __construct($id) {
        $this->data = db::singleton()->query("SELECT * FROM usuarios WHERE id=$id")->fetch();        
        $this->data["status"] = $this->textStatus[$this->data["status"]];
    }

    public function __get($name) {
        return $this->data[$name];
    }
}