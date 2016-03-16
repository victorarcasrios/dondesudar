
$(document).ready(function() {
    GYM_ID = $("[name='id']").val();
    USER_ID = $("[name='userId']").val();
    MAX_STARS = 10;

    $commentsDiv = $("#comments");
    $commentInput = $("[name='comment']");
    $addComment = $("[name='addComment']");
    $userVote = $("[name='userVote']");
    $commentInput.on("focus", function() {
        $addComment.parent().fadeIn();
    });
    $addComment.on("click", addComment);
    $userVote.on("change", changeUserVote);
});

/**
 * Crea un nuevo comentario para el gimnasio mostrado via AJAX y llama a refreshComments()
 * @returns {undefined}
 */
function addComment() {
    console.log($commentInput.val());
    if (!isNotFilled($commentInput))
    {
        $.ajax({
            url: "controllers/ajax/gymsAjaxController.php",
            type: "POST",
            data: {action: "addCommentTo", gymId: GYM_ID, authorId: USER_ID, comment: $commentInput.val()}
        }).done(function() {
            empty($commentInput);
            refreshComments();
            $addComment.parent().hide();
        });
    }
}

/**
 * Recoge los comentarios para este gimnasio via AJAX
 * @returns {undefined}
 */
function refreshComments() {
    var comments = "";

    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: {action: "getCommentsWhereGymIdIs", gymId: GYM_ID}
    }).done(function(response) {
        comments = $.parseJSON(response);
        removeActualCommentsAndPutThese(comments);
    });
}

/**
 * Refresca la zona de comentarios con los nuevos comentarios añadidos
 * @param JSON commentsObject
 * @returns {undefined}
 */
function removeActualCommentsAndPutThese(commentsObject) {
    var html = "";

    $.each(commentsObject, function(key, val) {
        html += '<div class="panel panel-default"><div class="panel-heading">';
        html += '<span><b>' + val.nick + '</b>   </span>';
        html += '<span><img src="' +val.avatar+ '" class="img-circle avatarOnComment"></span>';
        html += ' <span class="text-right">' + val.post_date + '</span></div>';
        html += '<div class="panel-body">' + val.text + '</div></div>';
    });

    $commentsDiv.html(html);
}

/**
 * Comienza el proceso para añadir o (si ya existe) cambiar el voto del usuario actual a este gimnasio
 * @returns {undefined}
 */
function changeUserVote() {
    var $this = $(this);
    var vote = $(this).val();
    removeEmptyFirstOption($this);

    createOrUpdateVote(GYM_ID, USER_ID, vote);
}

/**
 * Crea o actualiza el voto del usuario userId para el gimnasio gymId con el voto vote via AJAX
 * @param Integer gymId
 * @param Integer userId
 * @param Integer vote
 * @returns {undefined}
 */
function createOrUpdateVote(gymId, userId, vote) {
    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: {
            action: "createOrUpdateVote",
            gymId: gymId,
            userId: userId,
            vote: vote
        }
    }).done(function(response) {
        console.log(response);
        refreshGymScore();
    });
}

/**
 * Recoge la nueva puntuación media para el gimnasio mostrado via AJAX
 * @returns {undefined}
 */
function refreshGymScore() {
    var newScore = "";
    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: {action: "getGymScoreFor", gymId: GYM_ID}
    }).done(function(response) {
        newScore = $.parseJSON(response);
        refreshGymScoreInputAndStars(newScore);
    });
}

/**
 * Refresca la casilla de puntuacion del gimnasio y llama a calculateStars()
 * @param Integer newScore
 * @returns {undefined}
 */
function refreshGymScoreInputAndStars(newScore) {
    $gymScore = $("[name='gymScore']");
    $starsContainer = $("#starsContainer");
    var oneDecimalScore = Math.round(newScore * 10)/10;

    $gymScore.val(oneDecimalScore);
    calculateStars(newScore);
}

/**
 * Calcula el numero de estrellas de cada tipo a mostrar y llama a displayStars()
 * @param {Integer] newScore
 * @returns {undefined}
 */
function calculateStars(newScore){
    var hasDecimals = (newScore % 1 !== 0);
    var noDecimals = Math.floor(newScore);    
    var emptyStars  = MAX_STARS - noDecimals;
    var halfStar = false;
    
    if(hasDecimals){
        halfStar = true;
        emptyStars--;
    }
    displayStars(noDecimals, emptyStars, halfStar);
}

/**
 * Genera las estrellas pertinentes en formato html y sobreescribe las actuales con las nuevas
 * @param {Integer} filled
 * @param {Integer} empty
 * @param {Boolean} half
 * @returns {undefined}
 */
function displayStars(filled, empty, half){
    var stars = "";

    for( var i = 0; i < filled; i++)
        stars += "<i class='fa fa-star fa-lg'></i>";
    stars += (half) ? "<i class='fa fa-star-half-o fa-lg'></i>" : "";
    for( var i = 0; i < empty; i++)
        stars += "<i class='fa fa-star-o fa-lg'></i>";
    
    $starsContainer.html(stars);
}

