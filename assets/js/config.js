$(document).ready(function(){
    $("[class*='passwordSwitch']").on("click", switchPasswordVisibility);
});

/**
 * Vacía el elemento parametro
 * @param {DOM Object} el
 * @returns {undefined}
 */
function empty( el ){
	$( el ).val( '' );
}

/**
 * Añade la class hide (Bootstrap) al elemento parametro
 * @param {DOM Object} el
 * @returns {undefined}
 */
function hide( el ){
    $( el ).addClass( "hide" );
}

/**
 * Devuelve true si no hay ningún popover visible
 * @returns {Boolean}
 */
function isAnyPopoverVisible(){
	return $( ".popover" ).is(":visible");
}

/**
 * Devueve true si el elemento parametro no tiene nada en su interior
 * @param {DOM Object} el
 * @returns {Boolean}
 */
function isEmpty( el ){
	return !$.trim( el.html() );
}

/**
 * Devuelve true si el valor del elemento introducido es un caracter vacio
 * @param {String} value
 * @returns {Boolean}
 */
function isNotFilled( el ){
	var value = $( el ).val();
	return ( value == '' || value.length < 0 ) ? true : false;
}

/**
 * Elimina la primera opción del elemento select parametro
 * @param {DOM Object} select
 * @returns {undefined}
 */
function removeEmptyFirstOption( select ){
	$( select ).children( ".vacio" ).remove();
}

/**
 * Elimina la clase hide (Bootstrap) del elemento parametro
 * @param {DOM Object} el
 * @returns {undefined}
 */
function show( el ){
    $( el ).removeClass( "hide" );
}

/**
 * Cambia la visibilidad de los input password al presionar los botones de los ojos
 * @returns {undefined}
 */
function switchPasswordVisibility(){
	if($(this).children("[class*='open']").is(":visible")){
		$(this).children("[class*='open']").hide();
		$(this).children("[class*='close']").show();
		$(this).siblings("[type='password']").prop("type", "text");
	}
	else{
		$(this).children("[class*='open']").show();
		$(this).children("[class*='close']").hide();
		$(this).siblings("[type='text']").prop("type", "password");		
	}
}

/**
 * Devuelve true si la tecla presionada fue ENTER
 * @param {event} e
 * @returns {Boolean}
 */
function wasEnterPressed( e ){
	var code = e.keyCode || e.which;

 	return ( code == 13 ) ? true : false;
}

/**
 * Valida el email (comprueba si esta disponible)
 * @returns {undefined}
 */
function emailIsAvailable() {
	var except = USER_ID || false;
    $email = $( this );
    if ($email.val().length > 0) {
        var email = $email.val();
        $.ajax({
            url: "controllers/ajax/siteAjaxController.php",
            method: "POST",
            data: {action: "isAnAvailableEmail", email: email, except: except}
        }).done(function(response) {
        	console.log(response);
            if(response == 0)
                $email.popover("show");
            else
                $email.popover("hide");
        });
    }else
        $email.popover("hide");
}


/**
 * Valida que el nombre de usuario este disponible
 * @returns {undefined}
 */
function nickIsAvailable() {
	var except = USER_ID || false;
	$nick = $(this);
    if ($nick.val().length > 0) {
        var nick = $nick.val();
        $.ajax({
            url: "controllers/ajax/siteAjaxController.php",
            method: "POST",
            data: {action: "nickIsAvailable", nick: nick, except: except}
        }).done(function(response) {
            if (response == 0) {
                $nick.popover("show");
            } else {
                $nick.popover("hide");
            }
        });
    } else {
        $nick.popover("hide");
    }
}