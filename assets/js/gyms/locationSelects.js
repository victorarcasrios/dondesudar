/**
 * Recoge las provincias de la comunidad autonoma seleccionada, las pasa por getHtmlOptionsFromObject() y las muestra en
 * el desplegable de seleccion
*/
function actionShowProvinciasForCA(){
	var $caSelect = $( this );
	var $provinciaSelect = $( "[name='provincia']");
	var selectedCaId = $( $caSelect ).val();
	var options;

	removeEmptyFirstOption( $caSelect );

	$.ajax({
		type: "POST",
		url: "controllers/ajax/gymsAjaxController.php",
		data: { action: "getProvinciasForCA", caId: selectedCaId }
	}).done( function( response ){

		provincias = $.parseJSON( response );
		options = getHtmlOptionsFromObject( provincias );	
		$provinciaSelect.html( options );
		$provinciaSelect.removeAttr( "disabled" );		
	});
}

/**
 * Devuelve unas options html generadas a partir del objeto provincias que recibe
*/
function getHtmlOptionsFromObject( provincias ){
	var options = "";

	$.each( provincias, function( key, val ){
		options += "<option value='" + val.id + "'>" + val.nombre + "</option>";
	} );

	return options;
}