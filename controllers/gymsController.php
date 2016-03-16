<?php

class gymsController {

    /**
     * Renderiza la vista gyms/index con los datos de los últimos gimnasios registrados
     */
    public function actionIndex() {
        require "models/gyms.php";
        require "models/ind_caracteristicasGimnasios.php";

        $gyms = gyms::getAllActive();
        foreach ($gyms as $key => $val) {
            $gyms[$key]["features"] = ind_caracteristicasGimnasios::getAllFeaturesWhereGymIdIs($val["id"]);
            $gyms[$key]["primaryImage"] = $this->getPrimaryImageFor($val["id"]);
        }

        vista("gyms/index", array(
            "gyms" => $gyms,
            "aux" => true
                )
        );
    }

    private function getPrimaryImageFor($gymId) {
        require_once "models/primaryGymImage.php";
        require_once "models/gymsImages.php";

        $image = false;
        $imageId = primaryGymImage::getOneWhereGymIdIs($gymId);
        if ($imageId) {
            $image = gymsImages::getImage($imageId);
        } else {
            $image = gymsImages::getFirstImageOf($gymId);
            if (!$image) {
                $image = GYM_DEFAULT_IMAGE;
            }
        }
        return IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH . "/{$image}";
    }

    /**
     * Renderiza la vista gyms/searchPage 
     */
    public function actionSearch() {
        require "models/comunidadesAutonomas.php";
        require "models/caracteristicas.php";
        require "models/gyms.php";
        require "models/ind_caracteristicasGimnasios.php";

        $gyms = (isset($_GET["searchInput"])) ? $this->search($_GET["searchInput"]) : false;

        vista("gyms/searchPage", array(
            "comunidadesAutonomas" => comunidadesAutonomas::getAll(),
            "caracteristicas" => caracteristicas::getAllWhereFatherIdIs(false),
            "gyms" => $gyms
                )
        );
    }

    public function search($text) {
        $searchValues = explode(' ', $text);
        $gyms = gyms::getAllWhereNameOrLocationLike($searchValues);

        foreach ($gyms as $key => $val) {
            $gyms[$key]["features"] = ind_caracteristicasGimnasios::getAllFeaturesWhereGymIdIs($val["id"]);
            $gyms[$key]["primaryImage"] = $this->getPrimaryImageFor($val["id"]);
        }

        return $gyms;
    }

    /**
     * Renderiza la vista site/loginForm con un mensaje de como registrar un gimnasio para los usuarios no registrados
     * Si el usuario ya ha sido identificado como miembro, llama a la metodo signinForm()
     */
    public function actionSignNewGymIn() {
        if ($_SESSION["user"]->status === "guest")
            vista("site/loginForm", "howToSigninAGim");
        else
            $this->signinForm();
    }

    /**
     * Renderiza la vista gyms/index con el formulario de registro de gimnasios
     */
    public function signinForm() {
        require "models/comunidadesAutonomas.php";
        require "models/caracteristicas.php";
        require "models/users.php";

        vista("gyms/signinForm", array(
            "comunidadesAutonomas" => comunidadesAutonomas::getAll(),
            "caracteristicas" => caracteristicas::getAllWhereFatherIdIs(false),
            "actualName" => users::getNameWhereIdIs($_SESSION["user"]->id)
        ));
    }

    /**
     * Renderiza la vista gyms/gymDetail con la información detallada del gimnasio recibido por $_GET[]
     */
    public function actionViewGym() {
        require "models/gyms.php";
        require "models/ind_caracteristicasGimnasios.php";
        require "models/comments.php";
        require "models/scores.php";

        $gymId = $_GET["gym"];
        $userId = $_SESSION["user"]->id;
        $gym = gyms::getRecordWhereIdIs($gymId)[0];
        $features = ind_caracteristicasGimnasios::getAllFeaturesWhereGymIdIs($gymId);
        $comments = comments::getAllWhereGymIdIs($gymId);
        prepareAvatarImages($comments);
        $gymScore = round(scores::getScoreAverageWhereGymIdIs($gymId), 1);
        $userVote = scores::getScoreWhereGymIdIs_AndUserIdIs($gymId, $userId);
        $images = $this->getImagesFor($gymId);

        vista("gyms/gymDetail", array(
            "gym" => $gym,
            "features" => $features,
            "comments" => $comments,
            "gymScore" => $gymScore,
            "userVote" => $userVote,
            "images" => $images
                )
        );
    }

    public function getImagesFor($gymId) {
        require "models/gymsImages.php";
        require "models/primaryGymImage.php";

        $images = gymsImages::getAllWhereGymIdIs($gymId);
        if (!$images) {
            $src = IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH . "/" . GYM_DEFAULT_IMAGE;
            return "<img src='$src' class='img-responsive'>";
        } else {
            $primaryImage = primaryGymImage::getOneWhereGymIdIs($gymId);

            $items = $this->getCarouselItems($images, $primaryImage);
            return $this->getCarouselWith($items);
        }
    }

    private function getCarouselItems($images, $primaryImage = false) {
        $path = IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH;
        $items = "";
        foreach ($images as $key => $img) {
            $aux = (!$key) ? "active" : "";
            $items .= "<div class='item $aux'><img src='{$path}/{$img["imagen"]}'></div>";
        }
        return $items;
    }

    private function getCarouselWith($items) {
        $slider = "<div id='slider' class='carousel slide' data-ride='carousel'>";
        $slider .= "<div class='carousel-inner'>$items</div>";
        $slider .= "<a class='left carousel-control' href='#slider' data-slide='prev'>";
        $slider .= "<span class='glyphicon glyphicon-chevron-left'></span></a>";
        $slider .= "<a class='right carousel-control' href='#slider' data-slide='next'>";
        $slider .= "<span class='glyphicon glyphicon-chevron-right'></span></a></div>";
        return $slider;
    }

    public function actionEditGym() {
        require "models/gyms.php";
        require "models/gymsImages.php";
        require "models/ind_gimnasiosUsuarios.php";

        $gymId = $_GET['gym'];

        if (ind_gimnasiosUsuarios::existsRecord($gymId, $_SESSION["user"]->id)) { // El usuario tiene privilegios
            $images = $this->getImagesForEditView($gymId);

            vista("gyms/editGym", array(
                "gymId" => $gymId,
                "gymName" => gyms::getRecordWhereIdIs($gymId)[0]["name"],
                "images" => $images,
                "principal" => false,
                "aux" => false
            ));
        } else {
            vista("site/message", array(
                "message" => "youCanNotEditThisGym",
                "aux" => false
            ));
        }
    }

    private function getImagesForEditView($gymId) {
        $images = gymsImages::getAllWhereGymIdIs($gymId);
        //$this->putPrimaryImageFirst($images, $gymId);
        $this->prepareGymsImages($images);

        return $images;
    }

    private function prepareGymsImages(&$images) {
        foreach ($images as $key => $img) {
            $images[$key]['ruta'] = IMAGES_FOLDER_PATH . "/" . GYMS_FOLDER_PATH . "/" . $img['imagen'];
            unset($images[$key]['imagen']);
        }
    }

    private function putPrimaryImageFirst(&$images, $gymId) {
        require "models/primaryGymImage.php";

        $primaryImageId = primaryGymImage::getOneWhereGymIdIs($gymId);
        $primaryImage = gymsImages::getImage($primaryImageId);
        foreach ($images as $key => $img) {
            if ($img["id_imagen"] == $primaryImage) {
                unset($images[$key]);
            }
        }
        array_unshift($images, $primaryImage);
    }

}
