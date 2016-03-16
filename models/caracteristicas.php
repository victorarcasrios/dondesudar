<?php

/**
 * Gestiona las consultas a la tabla caracteristicas
 */
class caracteristicas {

    const TABLE = "caracteristicas";
    
    /**
     * Devuelve todos los registros con id_padre $fatherId
     * @param integer $fatherId
     * @return array
     */
    public static function getAllWhereFatherIdIs($fatherId = false) {
        $select = "SELECT * FROM " .self::TABLE. " ";
        if ($fatherId) {
            $select .= "WHERE id_padre=?";
        } else {
            $select .= "WHERE id_padre IS NULL";
        }
        $select .= " ORDER BY nombre";
        $query = db::singleton()->prepare($select);
        $query->execute(array($fatherId));
        return $query->fetchAll();
    }

}
