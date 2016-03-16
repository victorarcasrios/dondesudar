<?php
// Formulario de registro
$header = $t["signin"];
$jsAdditionalScripts = array(
    "signinForm"
);
?>

<form action="?r=site/signin" method="POST" class="form-horizontal" role="form" id="signinForm">

    <br />
    <br />

    <!-- IZQUIERDA -->
    <div class="col-lg-6">
        <div class="form-group col-lg-9">
            <label for="email" class="sr-only"><?= $t["obligatoryFieldMessage"] ?></label>
            <input type="email" name="email" placeholder="Email" required title='<?= $t["notAnEmail"] ?>' autofocus
                   class="form-control" data-container="body" data-toggle="popover" data-placement="right" 
                   data-content="<?= $t['notAvailableEmail'] ?>" data-trigger="manual">
        </div>
        <div class="form-group col-lg-7">
            <label for="password" class="sr-only"><?= $t["password"] ?></label>
            <div class='input-group'>
            <input type="password" name="password" placeholder="<?= $t["password"] ?>" required 
                   title='<?= $t["obligatoryFieldMessage"] ?>' class="form-control">
                   <span class="input-group-addon passwordSwitch">
                    <span class="glyphicon glyphicon-eye-open"></span>
                    <span class="glyphicon glyphicon-eye-close oculto"></span>
                </span>
            </div>
        </div>        
        <div class="form-group col-lg-7">
            <label for="repeatPassword" class="sr-only"><?= $t["repeatPassword"] ?></label>
            <div class="input-group">
                <input type="password" name="repeatPassword" placeholder="<?= $t["repeatPassword"] ?>" required 
                    title='<?= $t["obligatoryFieldMessage"] ?>' class="form-control">
                <span class="input-group-addon passwordSwitch">
                    <span class="glyphicon glyphicon-eye-open"></span>
                    <span class="glyphicon glyphicon-eye-close oculto"></span>
                </span>
            </div>            
        </div>
        <div class="col-lg-8">
            <div id="passwordsMatch" class="alert alert-success hide"><?= $t["passwordsMatch"] ?></div>
            <div id="passwordsDoesNotMatch" class="alert alert-danger hide"><?= $t["passwordDoesNotMatch"] ?></div>
        </div>
        <legend><?= $t["optionalFieldsLegend"] ?></legend>
        <div id="advancedSigninForm" >
            <div class="help-block"><?= $t["optionalFieldsMessage"] ?></div>
            <div class="form-group">
                <div class="col-lg-7">
                    <label for="nick" class="sr-only"><?= $t["nick"] ?></label>
                    <input type="text" name="nick" placeholder="<?= $t["nick"] ?>" class="form-control" 
                    data-container="body" data-toggle="popover" data-placement="right" 
                   data-content="<?= $t['notAvailableNick'] ?>" data-trigger="manual">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <label for="name" class="sr-only"><?= $t["name"] ?></label>
                    <input type="text" name="name" placeholder="<?= $t["name"] ?>" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label for="surname" class="sr-only"><?= $t["surname"] ?></label>
                    <input type="text" name="surname" placeholder="<?= $t["surname"] ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- END DERECHA -->

    <!-- SUBMIT -->
        <br />
        <br />
        <div class="col-lg-2" ></div>
        <input type="submit" value='<?= $t["signin"] ?>' class="btn btn-primary btn-lg col-lg-2">
    
    <div class="col-lg-6">
         <div id="formNotFilledCorrectly" class="alert alert-danger oculto"><?= $t["formNotFilledCorrectly"] ?></div>
    </div>
</form>
</div>
</div>