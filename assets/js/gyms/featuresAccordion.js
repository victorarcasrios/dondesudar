$( document ).ready( function(){
	$accordions = $( "[id^='collapse']" );	
	$accordions.collapse( "hide" );
});


/*
 * Abre o cierra el acordeón
*/
function actionToggleRespectiveAccordion()
{
	$accordions.collapse( "hide" );	
	var $accordionHead = $( this );
	var $accordionBody = $accordionHead.siblings().children( ".panel-body" );
	var accordionId = $accordionHead.children( "h4" ).children( "a" ).attr( "id" ).split( "_" )[1];
	var $accordion = $( "#collapse" + accordionId );

	if( isEmpty( $accordionBody ) )		
		getContentFor( accordionId );
	$accordion.collapse( "toggle" );
}


/*
 * Recoge el las caracteristicas hijas de la caracteristica cuyo id es pasado por parámetro via AJAX
*/
function getContentFor( id ){
	$.ajax({
		type: "POST",
		url: "controllers/ajax/gymsAjaxController.php",
		data: {action: "getDaughtersFeaturesFor", fatherId: id}
	}).done( function( response ){

		var daughters = $.parseJSON( response );
		displayTheseDaughterFeaturesIn( daughters, id );

	} );
}

/*
 * Despliega las el array de categorias pasadas por parametro en el acordeón cuyo id es recogido por parametro
*/
function displayTheseDaughterFeaturesIn( daughters, accordionId ){
	var $accordionBody = $( "#collapse" + accordionId ).children( ".panel-body" );
	var html = "";

	$.each( daughters, function( key, val ){
		html += "<div class='checkbox'><label><input type='checkbox' name='feature[]' value='" + val.id + "'>" + val.nombre +"</label></div>";
	} );

	$accordionBody.html( html );
}