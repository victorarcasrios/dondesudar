<?php
/**
 * Gestiona las consultas a la tabla gimnasios
 */
class gyms {

    const TABLE = "gimnasios";

    /**
     * Devuelve todos los registros que contengan los $searchValues en los
     * campos name o domicilio
     * @param array $searchValues
     * @return array
     */
    public static function getAllWhereNameOrLocationLike($searchValues){
        $text = self::prepareSearchs($searchValues);
        $select = "SELECT * FROM " .self::TABLE. " WHERE status = 1 AND $text";
        
        $query = db::singleton()->prepare($select); 
        $query->execute();
        return $query->fetchAll();
    }
    
    /**
     * Prepare los $searhValues, y con ellos, parte de la consulta
     * @param array $searchValues
     * @return array
     */
    private static function prepareSearchs($searchValues){
        $text = "";
        
        foreach($searchValues as $val){
            $text .= "OR name LIKE '%$val%' OR domicilio LIKE '%$val%' ";
        }
        
        return substr($text, 2);
    }
    
    /**
     * Devuelve todos los registros que tengan relacion administrativa con el
     * usuario con id $id
     * @param integer $userId
     * @return array
     */
    public static function getAllWhereUserIdIs($userId) {
        $select = "SELECT g.id, g.name, cif, domicilio, status, p.nombre provincia, relacion FROM provincias p, ind_gimnasios_usuarios i, "
                . self::TABLE . " g WHERE g.provincia=p.id AND g.id=i.id_gimnasio AND i.id_usuario=?";

        $query = db::singleton()->prepare($select);
        $query->execute(array($userId));
        return $query->fetchAll();
    }

    /**
     * Devuelve el registro con id $id
     * @param integer $id
     * @return array
     */
    public static function getRecordWhereIdIs($id) {
        $select = "SELECT g.id, name, domicilio, email, telefono, otro_telefono, p.nombre provincia, c.nombre ca FROM " . self::TABLE;
        $select .= " g, provincias p, comunidades_autonomas c WHERE g.provincia=p.id ";
        $select .= " AND p.id_comunidadautonoma=c.id AND g.id=?";

        $query = db::singleton()->prepare($select);
        $query->execute(array($id));
        return $query->fetchAll();
    }

    /**
     * Devuelve el id del registro con cif $cif
     * @param string $cif
     * @return integer
     */
    public static function getIdWhereCifIs($cif) {
        $select = "SELECT id FROM " . self::TABLE . " WHERE cif=?";
        
        $query = db::singleton()->prepare($select);
        $query->execute(array($cif));
        return $query->fetchColumn();
    }

    /**
     * Devuelve todos los registros con status 1 (activo)
     * @return array
     */
    public static function getAllActive() {
        $select = "SELECT g.id, name, domicilio, nombre provincia FROM provincias p, " . self::TABLE .
                " g WHERE g.provincia=p.id and status=1";
        
        return db::singleton()->query($select)->fetchAll();
    }

    /**
     * Devuelve el numero de registros donde el cif es $cif
     * @param string $cif
     * @return integer
     */
    public static function returnHowMuchRecordsWhereCifIs($cif) {
        $select = "SELECT id FROM " . self::TABLE . " WHERE cif = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($cif));

        return $query->rowCount();
    }

    /**
     * Inserta un nuevo registro
     * @param string $gymName
     * @param string $cif
     * @param integer $provincia
     * @param string $address
     * @param integer $postcode
     * @param string $email
     * @param string $phoneNumber
     * @param string $otherPhoneNumber
     */
    public static function insertRecord($gymName, $cif, $provincia, $address, $postcode, $email, $phoneNumber, $otherPhoneNumber) {
        $otherPhoneNumber = ( $otherPhoneNumber ) ? "'" . $otherPhoneNumber . "'" : "NULL";

        $insert = "INSERT INTO " . self::TABLE . " VALUES( NULL, ?, ?, ?, ?, ?, 0, ?, ?, ?)";
        $query = db::singleton()->prepare($insert);
        $query->execute(array($gymName, $cif, $provincia, $address, $postcode, $email, $phoneNumber, $otherPhoneNumber));
    }

}
