<?php

/**
 * Clase que gestiona la conexión a la base de datos mediante PDO
 */
class db extends PDO {

    private static $instance = null;
 
    /**
     * Constructor para inicializar los parametros esenciales heredados de PDO
     */
    public function __construct() {
        $db=array(
        	'dbhost'=>'localhost',
        	'dbname'=>'dondesudar',
        	'dbuser'=>'alumno',
        	'dbpass'=>'alumno',
            array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        
        parent::__construct('mysql:host=' . $db['dbhost'] . ';dbname=' . $db['dbname'], $db['dbuser'], $db['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }

    /**
     * Singleton convencional:
     * Si no existe ya una instancia de la clase db, la crea
     * @return instancia de la clase db
     */
    public static function singleton() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}