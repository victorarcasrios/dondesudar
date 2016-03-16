;
$(document).ready(function() {
    GYM_ID = $("[name='gymId']").val();
    $uploadImagesInput = $("[name='images[]']");
    $delete = $("[name='delete']");

    $uploadImagesInput.on("change", actionUploadImages);
    $delete.on("click", actionTryDelete);
});

/**
 * Envia un FormData para añadir nuevas imagenes al gimnasio via AJAX
 * @returns {undefined}
 */
function actionUploadImages() {
    var images = this.files;
    var fd = new FormData();

    $.each(images, function(key, val) {
        fd.append(key, val);
    });

    fd.append("action", "uploadImages");
    fd.append("gymId", GYM_ID);

    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: fd,
        contentType: false,
        processData: false,
        cache: false
    }).done(function(response) {
        console.log(response);
        manageImageAdditionResponse(response);
    });
}

function manageImageAdditionResponse(response){
    var obj = $.parseJSON(response);
    
    $.each(obj, function(key, val){
       if(Object.keys(val).length){
           createImageNode(val.id, val.source.substr(6));
       }
    });
}

function createImageNode(id, src){
    $uploadedImages = $("#uploadedImages");
    
    var img = "<div class='col-lg-3'><img src='"+src+"' class='img-rounded gymImageOnEdit'>";
    img += "<input type='checkbox' class='imageCheckbox' name='imageCheckbox[]' value='"+id+"'></div>";
    
    $uploadedImages.append(img);
}

/*#############################################################################################*/
/*#############################################################################################*/
/*#############################################################################################*/

/**
 * Gestiona la accion del usuario de presionar el boton de borrar imagenes
 * @returns {undefined}
 */
function actionTryDelete() {
    var $checkboxes = $("[name='imageCheckbox[]']");

    if ($checkboxes.is(":checked"))
        showConfirmDeleteDialog();
    else
        showNotSelectedImagesPopup();
}

/**
 * Muestra un popup avisando de que no hay ninguna imagen seleccionada para borrar
 * @returns {undefined}
 */
function showNotSelectedImagesPopup(){
    $delete.popover("show");
    setTimeout(function(){
        $delete.popover("hide");
    }, 3000);
}

/**
 * Muestra un dialogo modal para que el usuario confirme si desea borrar las imagenes
 * @returns {undefined}
 */
function showConfirmDeleteDialog(){
    var message = $("[name='wantToDelete']").val();
    bootbox.confirm(message, function(result){
        if(result)
            deleteImages();
    });
}

/**
 * Manda la peticion AJAX para borrar las imagenes y finalmente, llama a 
 * removeImagesNodes() para que actualice la vista eliminando las imagenes de la pantalla
 * @returns {undefined}
 */
function deleteImages(){
    var $checkboxes = $("[name='imageCheckbox[]']:checked");
    var imagesIds = getCheckboxValues($checkboxes);
    
    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: {action: "deleteImages", imagesIds: imagesIds}
    }).done(function(){
        removeImageNodes($checkboxes);
    });
}

/**
 * Elimina los nodos correspondientes a las imagenes de los checkboxes $checkboxes
 * @param {array} $checkboxes
 * @returns {undefined}
 */
function removeImageNodes($checkboxes){
    $.each($checkboxes, function(key, val){
        $(val).parent().remove();
    });
}

/**
 * Devuelve un array con los valores de los checkboxes pasados por parametro
 * @param {array} checkboxes
 * @returns {Array|getCheckboxValues.ids}
 */
function getCheckboxValues(checkboxes){
    var $checkboxes = $(checkboxes);
    var ids = new Array();
    
    $.each($checkboxes, function(key, val){
       ids.push($(val).val()); 
    });
    
    return ids;
}


