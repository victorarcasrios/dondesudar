<?php

/**
 * Gestiona las consultas a la tabla imagen_principal_gimnasio
 *
 * @author Víctor
 */
class primaryGymImage {
    
    const TABLE = "imagen_principal_gimnasio";
    
    /**
     * Devuelve el registro con id $gymId
     * @param integer $gymId
     * @return array
     */
    public static function getOneWhereGymIdIs($gymId){
        $select = "SELECT id_imagen_principal FROM " .self::TABLE. " WHERE id_gimnasio = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        return ($query->rowCount()) ? $query->fetchColumn() : false;
    }
}
