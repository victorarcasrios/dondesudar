<?php
/**
 * Gestiona las consultas a la tablas ind_caracteristicas_gimnasios
 */
class ind_caracteristicasGimnasios {

    const TABLE = "ind_caracteristicas_gimnasios";

    /**
     * Devuelve todos los registro con id_gimnasio $gymId
     * @param integer $gymId
     * @return array
     */
    public static function getAllFeaturesWhereGymIdIs($gymId) {
        $select = "SELECT id, nombre FROM caracteristicas c, " . self::TABLE . " i WHERE id=id_caracteristica ";
        $select .= "AND id_padre IS NOT NULL and id_gimnasio=?";

        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        return $query->fetchAll();
    }

    /**
     * Inserta un registro
     * @param integer $gymId
     * @param integer $featureId
     */
    public static function insertRecord($gymId, $featureId) {
        $insert = "INSERT INTO " . self::TABLE . " VALUES( ?, ? )";
        $query = db::singleton()->prepare($insert);
        $query->execute(array($gymId, $featureId));
    }

}
