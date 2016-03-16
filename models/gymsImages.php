<?php
/**
 * Gestiona la tabla imagenes_gimnasios
 *
 * @author Víctor
 */
class gymsImages {
    
    const TABLE = "imagenes_gimnasios";
      
    /**
     * Elimina los registros con id_imagen comprendidos en $ids
     * @param array $ids
     */
    public static function deleteRecords($ids){
        $delete = "DELETE FROM " .self::TABLE. " WHERE id_imagen = ?";
        $query = db::singleton()->prepare($delete);
        foreach( $ids as $id ){
            $query->execute(array($id));
        }
    }
    
    /**
     * Devuelve todos los registros con id_gimnasio $gymId
     * @param integer $gymId
     * @return array
     */
    public static function getAllWhereGymIdIs($gymId){
        $select  = "SELECT id_imagen, imagen FROM " .self::TABLE. " WHERE id_gimnasio = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        return $query->fetchAll();
    }
    
    /**
     * Devuelve el campo imagen del registro con el id_imagen más bajo de las que
     * comparten el id_gimnasio $gymId
     * @param integer $gymId
     * @return string
     */
    public static function getFirstImageOf($gymId){
        $minImageIdForThisGym = "SELECT MIN(id_imagen) FROM " .self::TABLE. " WHERE id_gimnasio = ?";
        $select = "SELECT imagen FROM " .self::TABLE. " WHERE id_imagen = ($minImageIdForThisGym)";
        
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        return ($query->rowCount()) ? $query->fetchColumn() : false;
    }
    
    /**
     * Devuelve el id del registro con nombre $name
     * @param string $name
     * @return integer
     */
    public static function getIdWhereNameIs($name){
        $select = "SELECT id_imagen FROM " .self::TABLE. " WHERE imagen = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($name));
        return $query->fetchColumn();
    }
    
    /**
     * Devuelve el nombre del registro con id $imageId
     * @param integer $imageId
     * @return string
     */
    public static function getImage($imageId){
        $select = "SELECT imagen FROM " .self::TABLE. " WHERE id_imagen = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($imageId));
        return $query->fetchColumn();
    }   
    
    /**
     * Devuelve la columna imagen del ultimo registro hecho con el id_gimnasio $gymId
     * @param type $gymId
     * @return type
     */
    public static function getLastImageNameWhereGymIdIs($gymId){
        $maxImageIdForThisGym = "SELECT MAX(id_imagen) FROM " .self::TABLE. " WHERE id_gimnasio = ?";
        $select = "SELECT imagen FROM " .self::TABLE. " WHERE id_imagen = ($maxImageIdForThisGym)";
        
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        return $query->fetchColumn();
    }
      
    /**
     * Devuelve un array con los nombres de los registros con ids comprendidos en $ids
     * @param array $ids
     * @return array
     */
    public static function getNamesWhereIdsAre($ids){
        $names = array();
        $select = "SELECT imagen FROM " .self::TABLE. " WHERE id_imagen = ?";
        $query = db::singleton()->prepare($select);
        foreach ($ids as $id) {
            $query->execute(array($id));
            $names[] = $query->fetchColumn();
        }
        return $names;
    }
    
    /**
     * Devuelve el numero de registros con el id_gimnasio $gymId
     * @param integer $gymId
     * @return integer
     */
    public static function howMuchRecordsWhereGymIdIs($gymId){
        $select = "SELECT COUNT(id_imagen) FROM " .self::TABLE. " WHERE id_gimnasio = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));

        return $query->fetchColumn();
    }
    
    /**
     * Inserta un registro el la tabla imagenes_gimnasios
     * @param integer $gymId
     * @param string $newImage
     */
    public static function insertRecord($gymId, $newImage){
        $insert = "INSERT INTO " .self::TABLE. " VALUES(NULL, ?, ?)";
        
        $query = db::singleton()->prepare($insert);
        $query->execute(array($gymId, $newImage));
    }
}
