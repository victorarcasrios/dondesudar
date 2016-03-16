<?php

require "../../db.php";

switch ($_POST['action']) {
    case "addCommentTo":
        actionAddCommentTo($_POST["comment"], $_POST["authorId"], $_POST["gymId"]);
        break;

    case "createOrUpdateVote":
        actionCreateOrUpdateVote($_POST["gymId"], $_POST["userId"], $_POST["vote"]);
        break;

    case "deleteImages":
        actionDeleteImages($_POST["imagesIds"]);
        break;

    case "getDaughtersFeaturesFor":
        actionGetDaughtersFeaturesFor($_POST["fatherId"]);
        break;

    case "getCommentsWhereGymIdIs":
        actionGetCommentsWhereGymIdIs($_POST["gymId"]);
        break;

    case "getGymScoreFor":
        actionGetGymScoreFor($_POST["gymId"]);
        break;

    case "getProvinciasForCA":
        actionGetProvinciasForCA($_POST['caId']);
        break;

    case "registerNewGym":
        actionInsertRecordIntoGyms($_POST["gymData"]);
        break;

    case "returnOneIfCifIsAvailable":
        actionReturnOneIfCifIsAvailable($_POST["cif"]);
        break;

    case "search":
        actionSearch($_POST['search'], $_POST["ca"], $_POST["provincia"], $_POST["postcode"], $_POST["features"]);
        break;

    case "uploadImages":
        actionUploadImagesForGym($_POST['gymId']);
        break;

    default:
        echo "Opción no contemplada en SWITCH gymsAjaxController.php";
        break;
}

/**
 * BUSQUEDAS AVANZADAS (PROGRAMADA CON PRISA, LO SIENTO)
 * @param string $search
 * @param integer $ca
 * @param integer $provincia
 * @param integer $postcode
 * @param array $features
 */
function actionSearch($search, $ca, $provincia, $postcode, $features) {
    require "../../config.php";
    require "../../models/gyms.php";
    require "../../models/provincias.php";
    require "../../models/ind_caracteristicasGimnasios.php";
    require "../../models/gymsImages.php";

    // Para evitar mostrar todos los gimnasios
    if ($search === "false" && $ca === "false" && $provincia === "false" && $postcode === "false" && $features === "false") {
        echo "false";
        die;
    }
    // TEXTO
    $select = "";
    $searchValues = explode(' ', $search);
    if ($search !== "false") {
        foreach ($searchValues as $val) {
            $select .= " OR (name LIKE '%$val%' OR domicilio LIKE '%$val%') ";
        }
        $select = substr($select, 3);
    }
    // LOCALIZACION
    if ($ca !== "false") {
        $select .= " AND provincia = ";
        $select .= ($provincia !== "false") ? $provincia : provincias::getFirstProvinciaWhereIdCaIs($ca);
    }
    $select .= ($postcode !== "false") ? " AND codigo_postal = $postcode " : '';
    // CARACTERISTICAS
    if ($features !== "false") {
        foreach ($features as $f) {
            $select .= " AND id_caracteristica = $f ";
        }
    }
       $and = (strpos($select, "AND"));
    $select = ($and <= 1) ? $select : "AND $select";
    $query = "SELECT DISTINCT g.id, g.name, domicilio, p.nombre provincia FROM gimnasios g, provincias p, ind_caracteristicas_gimnasios icg, caracteristicas c ";
    $query .= " WHERE g.id = icg.id_gimnasio AND icg.id_caracteristica = c.id AND g.provincia = p.id $select";
    $results = db::singleton()->query($query)->fetchAll();
    foreach ($results as $key => $r) {
        $results[$key]["features"] = ind_caracteristicasGimnasios::getAllFeaturesWhereGymIdIs($r["id"]);
        $results[$key]["primaryImage"] = IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH . "/";
        $results[$key]["primaryImage"] .= ($aux = gymsImages::getFirstImageOf($r["id"])) ? $aux : GYM_DEFAULT_IMAGE;
    }
    echo json_encode($results);
}

/**
 * Añade un commentario a la tabla
 * @param String $comment
 * @param Integer $authorId
 * @param Integer $gymId
 */
function actionAddCommentTo($comment, $authorId, $gymId) {
    require "../../models/comments.php";

    comments::insertRecord($comment, $authorId, $gymId);
}

/**
 * Inserta o actualiza el registro con la combinacion $gymId, $userId, $vote
 * @param Integer $gymId
 * @param Integer $userId
 * @param Integer $vote
 */
function actionCreateOrUpdateVote($gymId, $userId, $vote) {
    require "../../models/scores.php";

    if (scores::getScoreWhereGymIdIs_AndUserIdIs($gymId, $userId))
        scores::updateScoreWhereGymIdIs_AndUserIdIs($gymId, $userId, $vote);
    else
        scores::insertRecord($gymId, $userId, $vote);
}

/**
 * Gestiona la eliminacion de las imagenes con ids comprendidos en $ids tanto 
 * de la db como del servidor mediante tres funciones menores
 * @param array $ids
 */
function actionDeleteImages($ids) {
    require "../../models/gymsImages.php";

    $names = gymsImages::getNamesWhereIdsAre($ids);
    removeImagesFromServer($names);
    gymsImages::deleteRecords($ids);
}

/**
 * Elimina del servidor las imagenes con los nombres comprendidos en $names
 * @param array $names
 */
function removeImagesFromServer($names) {
    require_once "../../config.php";
    $path = "../../" . IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH;

    foreach ($names as $name) {
        $img = "{$path}/{$name}";
        if (file_exists($img)) {
            unlink($img);
        }
    }
}

/**
 * Devuelve las categorias con el id_padre $fatherId
 * @param Integer $fatherId
 */
function actionGetDaughtersFeaturesFor($fatherId) {
    require "../../models/caracteristicas.php";

    $caracteristicas = caracteristicas::getAllWhereFatherIdIs($fatherId);

    echo json_encode($caracteristicas);
}

/**
 * Devuelve los comentarios correspondientes al gimnasio con id $gymId
 * @param Integer $gymId
 */
function actionGetCommentsWhereGymIdIs($gymId) {
    require "../../config.php";
    require "../../models/comments.php";

    $comments = comments::getAllWhereGymIdIs($gymId);
    prepareAvatarImages($comments);

    echo json_encode($comments);
}

/**
 * Devuelve la puntuacion media para el gimnasio con id $gymId
 * @param Integer $gymId
 */
function actionGetGymScoreFor($gymId) {
    require "../../models/scores.php";

    $averageScore = scores::getScoreAverageWhereGymIdIs($gymId);

    echo json_encode(round($averageScore, 2));
}

/**
 * Devuelve las provincias con el id_comunidadautonoma $caId
 * @param Integer $caId
 */
function actionGetProvinciasForCA($caId) {
    require "../../models/provincias.php";

    $provincias = provincias::getAllWhereCa($caId);

    echo json_encode($provincias);
}

/**
 * Inserta un registro en gyms con los datos del nuevo gimnasio, llama a setFeaturesToGym() para que inserte
 * un registro en ind_características para asignar al gimnasio las características seleccionadas, a 
 * setManagerToGym() para que inserte un registro en ind_gimnasiosUsuarios para vincular el gimnasio con el
 * usuario que lo ha creado creado, y por último establece el nombre especificado via POST como el del usuario
 * @param Integer $gymData
 */
function actionInsertRecordIntoGyms($gymData) {
    require "../../config.php";
    require "../../models/gyms.php";

    $otherPhoneNumber = ( isset($gymData["otherPhoneNumber"]) ) ? $gymData["otherPhoneNumber"] : false;

    gyms::insertRecord($gymData["gymName"], $gymData["cif"], $gymData["provincia"], $gymData["address"], $gymData["postcode"], $gymData["email"], $gymData["phoneNumber"], $otherPhoneNumber);

    $gymId = gyms::getIdWhereCifIs($gymData["cif"]);
    var_dump($gymId);

    if (isset($gymData["gymFeatures"]))
        setFeaturesToGym($gymId, $gymData["gymFeatures"]);

    setManagerToGym($gymId, $_POST["userId"], $gymData["occupation"]);
    setNameToUserWhereIdNameIs($_POST["userId"], $gymData["contactName"]);
}

/**
 * Por cada $features inserta un registro con el id_caracteristica $feature y el id_gimnasio $gymId
 * @param Integer $gymId
 * @param Array of Integers $features
 */
function setFeaturesToGym($gymId, $features) {
    require "../../models/ind_caracteristicasGimnasios.php";

    foreach ($features as $f)
        ind_caracteristicasGimnasios::insertRecord($gymId, $f);
}

/**
 * Inserta un registro en la tabla ind_gimnasios_usuarios para relacionar un gimnasio con un usuario
 * @param Integer $gymId
 * @param Integer $userId
 * @param String $relation
 */
function setManagerToGym($gymId, $userId, $relation) {
    require "../../models/ind_gimnasiosUsuarios.php";

    ind_gimnasiosUsuarios::insertRecord($gymId, $userId, $relation);
}

/**
 * Actualiza el nombre $name del registro de la tabla usuarios con id $userId
 * @param Integer $userId
 * @param String $name
 */
function setNameToUserWhereIdNameIs($userId, $name) {
    require "../../models/users.php";

    users::updateRecordWhereIdIs_setName($userId, $name);
}

/**
 * Devuelve 1 si el cif $cif no consta en ningun registro de la base de datos
 * De lo contrario devuelve 0
 * @param String $cif
 */
function actionReturnOneIfCifIsAvailable($cif) {
    require "../../models/gyms.php";

    if (gyms::returnHowMuchRecordsWhereCifIs($cif))
        echo 0;
    else
        echo 1;
}

function actionUploadImagesForGym($gymId) {
    require "../../config.php";
    require "../../models/gymsImages.php";

    $i = 0;
    $images = count($_FILES);

    while ($i < $images && $moreImages = gymHasSpaceForImages($gymId)) {
        $img = $_FILES[$i++];
        if ($img['error'] == UPLOAD_ERR_OK) {
            $name = $img['name'];
            $extension = strtolower(pathinfo($name)['extension']);
            $tmpName = $img['tmp_name'];
            $size = ($img['size']);

            if (!isAllowedImageExtension($extension) || !isAllowedImageSize($size, 'gym')) {
                $response[] = "NOT_ALLOWED_FORMAT_ERROR";
            } else {
                $finalName = recordImagePathOnDatabaseAndReturnName($gymId, $extension);
                $response[] = array(
                    "id" => gymsImages::getIdWhereNameIs($finalName),
                    "source" => saveImageOnServerAndReturnDestination($tmpName, $finalName)
                );
            }
        } else {
            $response[] = "FATAL_ERROR_UPLOAD_FAIL";
        }
    }
    echo (!isset($response)) ? "FATAL_ERROR_ZERO_UPLOADED" : json_encode($response);
}

/**
 * Devuelve true si el gimnasio aun puede tener más imágenes. De lo contrario devuelve false
 * @param integer $gymId
 * @return boolean
 */
function gymHasSpaceForImages($gymId) {
    $numberOfImages = gymsImages::howMuchRecordsWhereGymIdIs($gymId);
    return ($numberOfImages < MAX_IMAGES_FOR_GYM ) ? true : false;
}

/**
 * Registra la imagen el la base de datos
 * @param integer $gymId
 * @param string $extension
 * @return string
 */
function recordImagePathOnDatabaseAndReturnName($gymId, $extension) {
    $lastImage = gymsImages::getLastImageNameWhereGymIdIs($gymId);
    $number = 1 + $lastNumber = getImageNumberFromName($lastImage, $gymId);
    if ($lastImage) {
        $newImage = "{$gymId}{$number}.{$extension}";
    } else {
        $newImage = "{$gymId}0.{$extension}";
    }
    gymsImages::insertRecord($gymId, $newImage);
    return $newImage;
}

function getImageNumberFromName($imageName, $gymId) {
    $imageName = explode('.', $imageName)[0];
    $prefixLength = count($gymId);
    $number = substr($imageName, $prefixLength);
    return $number;
}

/**
 * Guarda la imagen del fichero temporal del servidor en el fichero final
 * @param string $tmpName
 * @param string $finalName
 * @return string
 */
function saveImageOnServerAndReturnDestination($tmpName, $finalName) {
    $path = "../../" . IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH;
    $destination = "{$path}/{$finalName}";
    move_uploaded_file($tmpName, $destination);
    return $destination;
}
