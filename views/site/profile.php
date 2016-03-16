<?php
$header = $t["userProfile"];
/*
  $cssAdditionalStyles = array(
  "bootstrap-editable"
  );
 */
$jsAdditionalScripts = array(
    "profile"
        //"bootstrap-editable"
);
?>
<form method="POST" action="?r=site/saveProfile" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="col-lg-6">
        <div class="form-group">
            <div class="col-lg-6">
                <label for="nick>" class="control-label"><?= $t["nick"] ?></label>
                <input type="text" name="nick" value="<?= $_SESSION["user"]->nick ?>" class="form-control"
                       data-container="body" data-toggle="popover" data-placement="right" 
                       data-content="<?= $t['notAvailableNick'] ?>" data-trigger="manual" required>
            </div></div>
        <div class="form-group">
            <div class="col-lg-6"><label for="name" class="control-label"><?= $t["name"] ?></label>
                <input typp="text" name="name" value="<?= $_SESSION["user"]->name ?>" class="form-control"></div>
            <div class="col-lg-6"><label for="surname" class="control-label"><?= $t["surname"] ?></label>
                <input type="text" name="surname" value="<?= $_SESSION["user"]->surname ?>" class="form-control"></div>

        </div>    
        <div class="form-group">
            <div class="col-lg-6">
                <label for="email" class="control-label">Email</label>
                <input type="text" name="email" value="<?= $_SESSION["user"]->email ?>" class="form-control"
                       title='<?= $t["notAnEmail"] ?>' data-container="body" data-toggle="popover" data-placement="right" 
                       data-content="<?= $t['notAvailableEmail'] ?>" data-trigger="manual" required>
            </div></div>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border"><?= $t["changePassword"] ?></legend>
            <div class="form-group">
                <div class="col-lg-6"><label for="newPassword" class="control-label"><?= $t["newPassword"] ?></label>
                    <div class="input-group">
                        <input type="password" name="newPassword" class="form-control">
                        <span class="input-group-addon passwordSwitch">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            <span class="glyphicon glyphicon-eye-close oculto"></span>
                        </span>
                    </div></div>
                <div class="col-lg-6"><label for="repeatPassword" class="control-label"><?= $t["repeatPassword"] ?></label>
                    <div class="input-group">
                        <input type="password" name="repeatPassword" class="form-control" data-container="body" 
                   data-toggle="popover" data-placement="right" data-content="<?= $t['passwordDoesNotMatch'] ?>" 
                   data-trigger="manual">
                        <span class="input-group-addon passwordSwitch">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            <span class="glyphicon glyphicon-eye-close oculto"></span>
                        </span>
                    </div></div>
            </div>

            <div class="col-lg-8">
                <div id="passwordsMatch" class="alert alert-success hide"><?= $t["passwordsMatch"] ?></div>
                <div id="passwordsDoesNotMatch" class="alert alert-danger hide"><?= $t["passwordDoesNotMatch"] ?></div>
            </div>   
        </fieldset>
        <div class="form-group">
            <div class="col-lg-6">
                <label for="currentPassword" class="control-label"><?= $t["currentPassword"] ?></label>
                <div class="input-group">
                    <input type="password" name="currentPassword" class="form-control" required>
                    <span class="input-group-addon passwordSwitch">
                        <span class="glyphicon glyphicon-eye-open"></span>
                        <span class="glyphicon glyphicon-eye-close oculto"></span>
                    </span>
                </div>
            </div></div>

    </div>

    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <img src="<?= $profileImage ?>" class="img-circle profileImg">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-6">
                <input type="file" name="img">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <?php if (isset($message)) { ?> <div class="alert alert-info"><?= $t[$message] ?></div> <?php } ?>
        </div>
        <div class="col-lg-2">
            <input type="submit" value="<?= $t["saveChanges"] ?>" class="btn btn-primary">
        </div>
    </div>
</form>

<input type="hidden" name="id" value="<?= $_SESSION['user']->id ?>">