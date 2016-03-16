<?php

class scores {

    const TABLE = "valoraciones";

    /**
     * Devuelve la puntuación media del gimnasio con id $gymId
     * @param Integer $gymId
     * @return Float $average
     */
    public static function getScoreAverageWhereGymIdIs($gymId) {
        $select = "SELECT AVG(puntuacion) FROM " . self::TABLE . " WHERE id_gimnasio = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId));
        $average = $query->fetchColumn();
        return $average;
    }

    /**
     * Devuelve la puntuación del gimnasio con id $gym_Id dada por el usuario con id $userId
     * @param Integer $gymId
     * @param Integer $userId
     * @return Integer $score
     */
    public static function getScoreWhereGymIdIs_AndUserIdIs($gymId, $userId) {
        $select = "SELECT puntuacion FROM " . self::TABLE . " WHERE id_gimnasio = ? AND id_autor = ?";
        $query = db::singleton()->prepare($select);
        $query->execute(array($gymId, $userId));
        $score = $query->fetchColumn();
        return $score;
    }
    
    /**
     * Inserta un nuevo registro con los valores $gymId, $userId y $vote
     * @param Integer $gymId
     * @param Integer $userId
     * @param Integer $vote
     */
    public static function insertRecord( $gymId, $userId, $vote ){
        $insert = "INSERT INTO " .self::TABLE. " VALUES(?, ?, ?)";
        $query = db::singleton()->prepare($insert);
        $query->execute(array($gymId, $userId, $vote));
    }

    /**
     * Actualiza el registro con id_gimnasio $gymId e id_autor $userId estableciendo la puntuacion en $vote
     * @param Integer $gymId
     * @param Integer $userId
     * @param Integer $vote
     */
    public static function updateScoreWhereGymIdIs_AndUserIdIs($gymId, $userId, $vote) {
        $update = "UPDATE " . self::TABLE . " SET puntuacion = ? WHERE id_gimnasio = ? AND id_autor = ?";
        $query = db::singleton()->prepare($update);
        $query->execute(array($vote, $gymId, $userId));
    }

}
