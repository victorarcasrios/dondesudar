<?php
$header = "{$t["editGym"]} <small>{$gymName}</small>";
$jsAdditionalScripts = array(
    "gyms/editGym",
    "bootbox"
)
?>

<div class="row">
    <form>
        <div class="form-group">
            <div class="col-lg-4">
                <input type="file" name="images[]" multiple="multiple">
            </div>
            <div class="pull-right">
                <input type='button' name='delete' value='<?= $t["delete"] ?>' class='btn btn-danger'
                data-container="body" data-toggle="popover" data-placement="left" 
                data-content="<?= $t['noImagesSeleted'] ?>" data-trigger="manual">
            </div>
        </div>    
    </form>
</div>

<div class="row" id="uploadedImages">
    <?php
    if ($images) {
        foreach ($images as $img) {
            echo "<div class='col-lg-3'><img src='{$img["ruta"]}' class='img-rounded gymImageOnEdit'>";
            echo "<input type='checkbox' class='imageCheckbox' name='imageCheckbox[]' value='{$img['id_imagen']}'></div>";
        }
    }
    ?>
</div>

<input type="hidden" name="gymId" value="<?= $gymId ?>">
<input type="hidden" name='wantToDelete' value="<?= $t["wantToDelete"] ?>"