<?php

/**
 * Gestiona la tabla usuarios
 *
 * @author Víctor
 */
class users {

    const TABLE = "usuarios";

    /**
     * Inserta un nuevo registro
     * @param string $email
     * @param string $password
     * @param string $salt
     * @param string $nick
     * @param string $name
     * @param string $surname
     * @param string $profile_img
     */
    public static function addUser($email, $password, $salt, $nick, $name = NULL, $surname = NULL, $profile_img = NULL) {
        while (!$nick) {
            $aux = substr($email, 0, 1) . rand();
            if (!self::getIdWhereNickIs($aux))
                $nick = $aux;
        }
        $name = ($name) ? "'" . $name . "'" : "NULL";
        $surname = ($surname) ? "'" . $surname . "'" : "NULL";
        $profile_img = ($profile_img) ? "'" . $profile_img . "'" : "NULL";
        
        $select = "INSERT INTO " . self::TABLE . " values(NULL, ?, ?, ?, ?, ?, ?, ?, NULL, 'p')";
        $query = db::singleton()->prepare($select);
        $query->execute(array($nick, $name, $surname, $email, $password, $salt, $profile_img));
    }

    /**
     * Devuelve true si el email parámetro no se encuentra registrado en la base de datos, y false si ya lo esta
     * @param String $email
     * @return boolean
     */
    public static function getIdWhereEmailIs($email) {
        $select = "SELECT id FROM " . self::TABLE . " WHERE email=?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($email));
        return $query->fetchColumn();
    }


    /**
     * Devuelve true si el nick parámetro no se encuentra registrado en la base de datos, y false si ya lo esta
     * @param String $nick
     * @return boolean
     */
    public static function getIdWhereNickIs($nick) {
        $select = "SELECT id FROM " . self::TABLE . " WHERE nick=?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($nick));
        return $query->fetchColumn();
    }

    /**
     * Devuelve el id correpondiente a la contraseña introducida
     * @param string $nickOrEmail
     * @return string
     */
    public static function getIdByNickOrEmail($nickOrEmail) {
        $select = "SELECT id FROM " . self::TABLE . " WHERE nick = ? OR email = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($nickOrEmail, $nickOrEmail));
        return $query->fetchColumn();
    }

    /**
     * Devuelve la imagen del usuario o false si no la hay
     * @param integer $userId
     * @return mixed (string/boolean)
     */
    public static function getImageWhereIdIs($userId) {
        $select = "SELECT profile_img FROM " . self::TABLE . " WHERE id = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($userId));
        return ($query->rowCount()) ? $query->fetchColumn() : false;
    }

    /**
     * Devuelve el nombre de la fila con el id $id
     * @param integer $id
     * @return string
     */
    public static function getNameWhereIdIs($id) {
        $select = "SELECT name FROM " . self::TABLE . " WHERE id = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($id));
        return $query->fetchColumn();
    }

    /**
     * Si existe el nick o el email devuelve la password, si no devuelve false 
     * @param string $nickOrEmail
     * @return boolean
     */
    public static function getPasswordByNickOrEmail($nickOrEmail) {
        $select = "SELECT password FROM " . self::TABLE . " WHERE nick = ? OR email = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($nickOrEmail, $nickOrEmail));
        return ($query->rowCount()) ? $query->fetchColumn() : false;
    }

    /**
     * Devuelve el salt del registro con el nick o el email $nickOrEmail
     * @param string $nickOrEmail
     * @return string
     */
    public static function getSaltByNickOrEmail($nickOrEmail) {
        $query = "SELECT salt FROM " . self::TABLE . " WHERE nick=? OR email=?";
        $response = db::singleton()->prepare($query);
        $response->execute(array($nickOrEmail, $nickOrEmail));

        return $response->fetchColumn();
    }

    /**
     * Actualiza los datos de perfil de un usuario
     * @param integer $id
     * @param string $email
     * @param string $password
     * @param string $nick
     * @param string $name
     * @param string $surname
     * @param string $profile_img
     */
    public static function updateUser($id, $email, $password = false, $salt = false, $nick, $name = NULL, $surname = NULL, $profile_img = NULL) {
                
        if( $password )
            self::updatePassword($id, $password, $salt);

        $update = "UPDATE " . self::TABLE . " SET email = ?, nick = ?, name = ?, surname = ?, profile_img = ? WHERE id = ?";
        $query = db::singleton()->prepare($update);
        $query->execute(array($email, $nick, $name, $surname, $profile_img, $id));
    }

    /**
     * Actualiza los campos password y salt del registro con id $id 
     * @param integer $id
     * @param string $password
     * @param string $salt
     */
    public static function updatePassword($id, $password, $salt) {
        $update = "UPDATE " .self::TABLE. " SET password = ?, salt = ? WHERE id = ?";
        $query = db::singleton()->prepare($update);
        $query->execute(array($password, $salt, $id));
    }

    /**
     * Actualiza el nombre del registro con id $userId
     * @param integer $userId
     * @param string $name
     */
    public static function updateRecordWhereIdIs_setName($userId, $name) {
        $update = "UPDATE " . self::TABLE . " SET name = ? WHERE id = ?";
        $query = db::singleton()->prepare($update);
        $query->execute(array($name, $userId));
    }

}
