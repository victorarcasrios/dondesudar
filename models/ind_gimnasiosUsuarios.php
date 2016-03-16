<?php

class ind_gimnasiosUsuarios {

    const TABLE = "ind_gimnasios_usuarios";

    /**
     * Inserta un registro
     * @param integer $gymId
     * @param integer $userId
     * @param string $relation
     */
    public static function insertRecord($gymId, $userId, $relation) {
        $insert = "INSERT INTO " . self::TABLE . " VALUES(?, ?, ?)";
        $query = db::singleton()->prepare($insert);
        $query->execute(array($gymId, $userId, $relation));
    }

    /**
     * Devuelve true si existe el registro con id_gimnasio $gymId e id_usuario $userId
     * @param integer $gymId
     * @param integer $userId
     * @return boolean
     */
    public static function existsRecord($gymId, $userId) {
        $select = "SELECT * FROM " . self::TABLE . " WHERE id_gimnasio = ? and id_usuario = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId, $userId));
        return ($query->rowCount()) ? true : false;
    }

}
