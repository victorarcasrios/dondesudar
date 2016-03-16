<?php
/**
 * Gestiona las consultas a la tabla comunidades_autonomas
 */
class comunidadesAutonomas{
	const TABLE = "comunidades_autonomas";

        /**
         * Devuelve todos los registros de la tabla
         * @return array
         */
	public static function getAll(){
		return db::singleton()->query( "SELECT * FROM " . self::TABLE);
	}
}