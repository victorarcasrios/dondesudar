<?php

require "models/users.php";

/**
 * Controlador de la página de inicio
 *
 * @author Víctor
 */
class siteController {

    /**
     * Renderiza la vista index
     */
    public function actionIndex() {
        vista("site/index", "");
    }

    /**
     * Renderiza el formulario de identifición si no hay ningún usuario
     * identificado, en cuyo caso muestra un mensaje de advertencia
     */
    public function actionLoginForm() {
        if ($_SESSION["user"]->status === "guest")
            vista("site/loginForm", "");
        else
            vista("site/message", array(
                "message" => "alreadyLoged",
                "aux" => false
            ));
    }

    /**
     * Gestiona el login
     */
    public function actionLogin() {

        if (isset($_POST["nickOrEmail"])) {
            if ($dbPassword = (users::getPasswordByNickOrEmail($_POST["nickOrEmail"]))) {// Recoge el password si existe el usuario
                $salt = users::getSaltByNickOrEmail($_POST["nickOrEmail"]);
                if (passwordEqualsHashedPassword($dbPassword, $salt, $_POST["password"])) {// Si es el mismo que el introducido
                    $_SESSION["user"] = new activeUser(users::getIdByNickOrEmail($_POST["nickOrEmail"]));
                    if ($_SESSION["user"]->status === "notYetAccepted") {
                        session_destroy();
                        vista("site/message", array(
                            "message" => "notYetAccepted",
                            "aux" => false
                        ));
                    } else if ($_SESSION["user"]->status === "banned") {
                        session_destroy();
                        vista("site/message", array(
                            "message" => "banned",
                            "aux" => false
                        ));
                    } else
                        vista("site/message", array(
                            "message" => "succesfulLogin",
                            "aux" => false
                        ));
                } else {// Si el password no es el correcto
                    vista("site/loginForm", "loginError");
                }
            } else {// Si ni el nick ni el email estan registrados
                vista("site/loginForm", "loginError");
            }
        } else
            vista("site/index", "");
    }

    /**
     * Gestiona el logout
     */
    public function actionLogout() {

        if ($_SESSION["user"]->status !== "guest") {
            $_SESSION["user"] = new activeUser(0);
            session_destroy();
            vista("site/loginForm", "succesfulLogout");
        } else
            vista("site/message", array(
                "message" => "alreadyGuest",
                "aux" => false
            ));
    }

    public function actionShowUserControlPanel() {
        require "models/gyms.php";

        vista("site/userControlPanel", array(
            "gyms" => gyms::getAllWhereUserIdIs($_SESSION["user"]->id),
            "aux" => true
        ));
    }

    /**
     * Renderiza el formulario de registro si el usuario es guest,
     * en caso contrario, muestra un mensaje de advertencia
     */
    public function actionSigninForm() {

        if ($_SESSION["user"]->status === "guest")
            vista("site/signinForm", "");
        else
            vista("site/message", array(
                "message" => "alreadyLoged",
                "aux" => false
            ));
    }

    /**
     * Gestiona el registro
     */
    public function actionSignin() {

        if ($_SESSION["user"]->status === "guest") {
            if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["repeatPassword"])) {
                if ($_POST["password"] === $_POST["repeatPassword"] && !users::getIdWhereEmailIs($_POST["email"])) {
                    if ((isset($_POST["nick"]))) {
                        if (!users::getIdWhereNickIs($_POST["nick"]))
                            $nick = $_POST["nick"];
                        else {
                            vista("site/message", array(
                                "message" => "navegationError",
                                "aux" => false));
                        }
                    } else
                        $nick = false;
                    $password = $_POST["password"];
                    $salt = cryptPassword($password);
                    $name = (isset($_POST["name"])) ? $_POST["name"] : false;
                    $surname = (isset($_POST["surname"])) ? $_POST["surname"] : false;
                    $profile_img = (isset($_POST["profile_img"])) ? $_POST["profile_img"] : false;
                    users::addUser($_POST["email"], $password, $salt, $nick, $name, $surname, $profile_img);
                    vista("site/message", array(
                        "message" => "succesfulSignin",
                        "aux" => false));
                } else {
                    vista("site/message", array(
                        "message" => "navegationError",
                        "aux" => false));
                }
            } else {
                vista("site/message", array(
                    "message" => "navegationError",
                    "aux" => false));
            }
        } else {
            vista("site/message", array(
                "message" => "alreadyLoged",
                "aux" => false));
        }
    }

    // Muestra el perfil del usuario
    public function actionProfile() {
        if ($_SESSION["user"]->status === "admin" || $_SESSION["user"]->status === "user") {
            vista("site/profile", array(
                "profileImage" => self::getProfileImageFor($_SESSION["user"]->id),
                "aux" => false
            ));
        } else {
            vista("site/message", array(
                "message" => "actionNotAllowed",
                "aux" => false
            ));
        }
    }

    private function getProfileImageFor($userId) {
        $path = IMAGES_FOLDER_PATH . "/" . AVATAR_FOLDER_PATH;

        if (!$img = users::getImageWhereIdIs($userId)) {
            return $path . "/" . AVATAR_DEFAULT_IMAGE;
        } else if (file_exists($response = "{$path}/{$img}")) {
            return $response;
        } else {
            return $path . "/" . AVATAR_DEFAULT_IMAGE;
        }
    }

    // Gestiona la modificacion del perfil por el usuario
    public function actionSaveProfile() {
        require_once "models/users.php";
        
        $password = false;
        $salt = false;
        if (isset($_POST["currentPassword"]) && passwordEqualsHashedPassword($_SESSION["user"]->password, $_SESSION["user"]->salt, $_POST["currentPassword"])) {
            $name = (isset($_POST["name"])) ? $_POST["name"] : false;
            $surname = (isset($_POST["surname"])) ? $_POST["surname"] : false;
            // IMAGEN DE PERFIL
            if ($_FILES['img']["size"]) {
                $profile_img = self::saveAvatarImgAndReturnPath();
            } else {
                $profile_img = users::getImageWhereIdIs($_SESSION["user"]->id);
            }
            $nick = (isset($_POST["nick"])) ? $_POST["nick"] : $_SESSION["user"]->nick;
            $email = (isset($_POST["email"])) ? $_POST["email"] : $_SESSION["user"]->email;
            if ($_POST["newPassword"] && $_POST["repeatPassword"] && $_POST["newPassword"] === $_POST["repeatPassword"]) {
                $password = $_POST["newPassword"];
                $salt = cryptPassword($password);
            }
            users::updateUser($_SESSION["user"]->id, $email, $password, $salt, $nick, $name, $surname, $profile_img);
            $_SESSION["user"] = new activeUser($_SESSION["user"]->id);
            vista("site/profile", array(
                "profileImage" => self::getProfileImageFor($_SESSION["user"]->id),
                "message" => "changesSaved"
            ));
        } else {
            vista("site/profile", array(
                "profileImage" => self::getProfileImageFor($_SESSION["user"]->id),
                "message" => "wrongPassword",
            ));
        }
    }

    private function saveAvatarImgAndReturnPath() {
        $img = $_FILES['img'];
        $folder = IMAGES_FOLDER_PATH . "/" . AVATAR_FOLDER_PATH;

        $tmpName = $img['tmp_name'];
        $size = $img['size'];
        $extension = strtolower(pathInfo($img['name'])['extension']);

        if (!isAllowedImageSize($size, 'avatar') || !isAllowedImageExtension($extension)) {
            return false;
        } else {
            $finalName = "{$_SESSION['user']->id}.{$extension}";
            $destination = "$folder/$finalName";
            if (file_exists($destination)) {
                unlink($destination);
            }
            move_uploaded_file($tmpName, $destination);
            return $finalName;
        }
    }

}
