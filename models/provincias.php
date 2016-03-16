<?php

class provincias {

    const TABLE = "provincias";

    /**
     * Devuelve todos los registros con id_comunidadautonoma $id_comunidadautonoma
     * @param integer $id_comunidadautonoma
     * @return type
     */
    public static function getAllWhereCA($id_comunidadautonoma) {
        $select = "SELECT * FROM " . self::TABLE . " WHERE id_comunidadautonoma=?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($id_comunidadautonoma));
        return $query->fetchAll();
    }

    /**
     * Devuelve el primer registro con id_comunidadautonoma $ca
     * @param integer $ca
     * @return integer
     */
    public static function getFirstProvinciaWhereIdCaIs($ca) {
        $select = "SELECT id FROM " .self::TABLE . " WHERE id_comunidadautonoma=?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($ca));
        return $query->fetchArray()[0];
    }

}
