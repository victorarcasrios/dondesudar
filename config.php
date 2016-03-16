<?php
// Cifrado
define("FIRST_PEPPER", "$2a$05$");
define("LAST_PEPPER", "$");
define("CHARS_LENGTH", "63");
define("SALT_LENGTH", "21");
//Imagenes
define("MAX_AVATAR_IMAGE_SIZE", 200000); // 200.000
define("MAX_GYM_IMAGE_SIZE", 2000000); //2.000.000
define("IMAGES_FOLDER_PATH", "uploads/img");
define("AVATAR_FOLDER_PATH", "avatar");
define("GYMS_FOLDER_PATH", "bussiness");
define("MAX_IMAGES_FOR_GYM", 8);
define("AVATAR_DEFAULT_IMAGE", "default-avatar.gif");
define("GYM_DEFAULT_IMAGE", "default-image.jpg");
//Idiomas
define("DEFAULT_LANGUAGE", "es");

/**
 * Recoge una vista, datos y un layout y lo renderiza todo adecuadamente
 * Además, llama setLanguage para que establezca el idioma en que se 
 * presentará la vista renderizada
 * @param String $vista
 * @param String $datos
 */
function vista($vista, $datos) {
    // Si $datos es un array, lo extrae en variables
    if (count($datos) >= 2)
        extract($datos);

    ob_start();
    require setLanguage();
    require "views/" . $vista . ".php";
    $content = ob_get_clean();
    require "layouts/" . getLayout() . "Layout.php";
}

/**
 * Devuelve el layout pertinente basandose en el tipo de usuario conectado
 * @return string
 */
function getLayout() {
    $status = $_SESSION["user"]->status;

    if ($status === "admin" or $status === "user")
        return $status;
    else
        return "default";
}

/**
 * Establece el idioma de la aplicación
 */
function setLanguage() {
    if (isset($_GET["lang"]) && $_GET["lang"]) {
        $_SESSION["lang"] = $_GET["lang"];
        $lang = $_SESSION["lang"];
    } else if (isset($_SESSION["lang"]))
        $lang = $_SESSION["lang"];
    else
        $lang = DEFAULT_LANGUAGE;

    return "lang/$lang.php";
}

// Recoge una contraseña por referencia, la cifra y devuelve el salt generado aleatoriamente y empleado en el cifrado
function cryptPassword(&$password) {

    $allowedSaltChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
    $salt = "";
    for ($i = 0; $i < SALT_LENGTH; $i++)
        $salt .= $allowedSaltChars[mt_rand(0, CHARS_LENGTH)];

    $composedSalt = FIRST_PEPPER . $salt . LAST_PEPPER;

    $password = crypt($password, $composedSalt);
    return $salt;
}

// Devuelve true si el primer password es igual al segundo password cifrado con el salt. De lo contrario devuelve false
function passwordEqualsHashedPassword($hashedPassword, $salt, $password) {
    $secondPassword_hashed = crypt($password, FIRST_PEPPER . $salt . LAST_PEPPER);

    return ( $secondPassword_hashed === $hashedPassword ) ? true : false;
}

function isAllowedImageExtension($extension) {
    $allowedImages = array("gif", "jpeg", "jpg", "png");
    foreach ($allowedImages as $ext) {
        if ($extension === $ext) {
            return true;
        }
    }
    return false;
}

function isAllowedImageSize($size, $type) {
    switch ($type) {
        case 'avatar':
            return ( $size <= MAX_AVATAR_IMAGE_SIZE ) ? true : false;
            break;
        case 'gym':
            return ( $size <= MAX_GYM_IMAGE_SIZE ) ? true : false;
            break;
    }
}

function prepareAvatarImages(&$comments) {
    $folder = IMAGES_FOLDER_PATH . "/" . AVATAR_FOLDER_PATH;
    foreach ($comments as $key => $value) {
        if ($value['avatar']) {
            $comments[$key]['avatar'] = "{$folder}/{$value['avatar']}";
        } else {
            $comments[$key]['avatar'] = $folder . "/" . AVATAR_DEFAULT_IMAGE;
        }
    }
}
