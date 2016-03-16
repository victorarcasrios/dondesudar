<?php
// Formulario de identificacion
$header = $t["login"];
$cssAdditionalStyles = array(
    "font-awesome"
    );
?>

<form action="?r=site/login" method="POST" role="form" class="form-horizontal">
    <br />
    <br />
    <br />
    <br />

    <div class="form-group">
        <div class="col-lg-4"></div>

        <label class="sr-only" for="nickOrEmail"><?= $t["nickOrEmail"] ?></label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-user"></span>
                </span>
                <input type="text" name="nickOrEmail" placeholder='<?= $t["nickOrEmail"] ?>' required title='<?= $t["obligatoryFieldMessage"] ?>'
                       class="form-control texto-centrado">
                <span class="input-group-addon">
                    <span class="fa fa-envelope fa-md"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4"></div>
        <label class="sr-only" for="password"><?= $t["password"] ?></label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fa fa-lock fa-lg"></span>
                </span>
                <input type="password" name="password" placeholder=<?= $t["password"] ?> required title='<?= $t["obligatoryFieldMessage"] ?>'
                   class="form-control">
                <span class="input-group-addon passwordSwitch">
                    <span class="glyphicon glyphicon-eye-open"></span>
                    <span class="glyphicon glyphicon-eye-close oculto"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-7"></div>
    <input type="submit" value="<?= $t["login"] ?>" class="btn btn-primary">
    
    <br />
    <br />
</form>

<?php if (isset($t[$datos])) { ?> <div class="alert alert-info"><?= $t[$datos] ?></div> <?php } ?>