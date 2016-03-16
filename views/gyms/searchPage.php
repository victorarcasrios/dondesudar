<?php
$header = $t["gyms"];
$jsAdditionalScripts = array(
    "gyms/searchPage",
    "gyms/locationSelects",
    "gyms/featuresAccordion",
    "gyms/index"
);
?>

<div class="col-lg-3 well">
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-lg-8"><h4><?= $t["whereToSearch"] ?></h4></div>
            <div class="col-lg-3"><input type="button" name="filter" value="<?= $t["filter"] ?>" class="btn btn-default"></div>
        </div>
        <div class="form-group">
            <label for="comunidadAutonoma"><?= $t["comunidadAutonoma"] ?></label>
            <select name="comunidadAutonoma" class="form-contol col-lg-12">
                <option class="vacio" value="false"></option>
                <?php
                foreach ($comunidadesAutonomas as $ca) {
                    ?>
                    <option value="<?= $ca['id'] ?>"><?= $ca["nombre"] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="provincia"><?= $t["provincia"] ?></label>
            <select name="provincia" class="form-control" disabled>
                <option value="false"><?= $t["selectCAFirst"] ?></option>		
            </select>
        </div>
        <div class="form-group">
            <div class="col-lg-6">
                <input type="text" name="postcode" class="form-control" placeholder="<?= $t["postcode"] ?>">
            </div>
        </div>
        <div class="form-group">
            <h4><?= $t["whatToSearch"] ?></h4>
        </div>

        <div class="form-group">
            <div class="panel-group" id="accordion">

                <?php
                foreach ($caracteristicas as $c) {
                    ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" id="ff_<?= $c['id'] ?>" href="#">
                                        <?= $c['nombre'] ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?= $c['id'] ?>" class="panel-collapse collapse in">
                                <div class="panel-body">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>
<div class="col-lg-9">
    <div class="col-lg-12">
        <?php require "views/gyms/searchTab.php"; ?>
        <br>
    </div>
    <div class="col-lg-12" id="searchResults">
        <?php
        if ($gyms) {
            foreach ($gyms as $g) {
                ?>
                <div class="col-lg-6">
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
                                    echo "<span class='label label-info'>{$t["withoutInformation"]}</span><hr>";
                                }
                                ?>
                            </p>
                            <p><a href="?r=gyms/viewGym&gym=<?= $g['id'] ?>" class="btn btn-primary" role="button">Ver más</a></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='alert alert-info col-lg-12 texto-centrado'><h4>{$t['noResultsSearch']}</h4></div>";
        }
        ?>
    </div>
</div>

<input type="hidden" name="noInfo" value="<?= $t["withoutInformation"] ?>">

