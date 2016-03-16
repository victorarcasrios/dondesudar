<?php
$header = $t["gyms"];
$jsAdditionalScripts = array(
    "gyms/index"
);
?>

<div class="row well">
    <form method="GET" action="?r=gyms/search" class="navbar-form navbar-left" role="search" id="searchForm">
        <div class="form-group">
            <div class="col-lg-4">
                <?php require "views/gyms/searchTab.php" ?>
            </div>
            <div class="pull-right">
                <a href="?r=gyms/search" class="btn btn-primary btn-lg">Busqueda avanzada</a>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="container">
        <?php
        if ($numberOfGyms = count($gyms)) {
            if ($numberOfGyms > 6)
                $dimension = 4;
            else if ($numberOfGyms < 4)
                $dimension = 6;
            else
                $dimension = 4;

            foreach ($gyms as $g) {
                ?>
                <div class="col-lg-<?= $dimension ?> gymDiv">
                    <div class="thumbnail">
                        <img data-src="#" alt="">
                        <div class="caption">
                            <h3><?= $g["name"] ?></h3>
                            <p><?= $g["domicilio"] ?> <span class="label label-warning pull-right"><?= $g["provincia"] ?></span></p>
                            <p><img src="<?= $g['primaryImage'] ?>" class="img-responsive"></p>
                            <p>
                                <?php
                                if ($g["features"]) {
                                    foreach ($g["features"] as $f)
                                        echo " <span class='label label-success'>" . $f["nombre"] . "</span>";
                                }else{
                                    echo "<span class='label label-info'>{$t['withoutInformation']}<hr></label>";
                                }                                    
                                ?>
                            </p>
                            <p><a href="?r=gyms/viewGym&gym=<?= $g['id'] ?>" class="btn btn-primary" role="button">Ver más</a></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<div class="row">
    <?php require "views/site/doYouHaveAGym.php"; ?>
</div>