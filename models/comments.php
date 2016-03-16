<?php

/**
 * Gestiona las consultas de la tabla comentarios
 */
class comments {

    const TABLE = "comentarios";

    /**
     * Inserta un nuevo registro con los datos parametro para un nuevo comentario activo
     * @param String $comment
     * @param Integer $authorId
     * @param Integer $gymId
     */
    public static function insertRecord($comment, $authorId, $gymId) {
        $insert = "INSERT INTO " . self::TABLE . " VALUES( NULL, ?, ?, NULL, ?, 1 )";

        $db = db::singleton()->prepare($insert);
        $db->execute(array($authorId, $gymId, $comment));
    }

    /**
     * Devuelve todos los comentarios donde el id_gimnasio es $gymId
     * @param Integer $gymId
     * @return Array
     */
    public static function getAllWhereGymIdIs($gymId) {
        $select = "SELECT nick, post_date, profile_img avatar, text FROM " . self::TABLE;
        $select .= " c, usuarios u WHERE id_autor=u.id AND id_gimnasio = ? AND c.status=1 ORDER BY c.id DESC";

        $db = db::singleton()->prepare($select);
        $db->execute(array($gymId));
        return $db->fetchAll();
    }

}
