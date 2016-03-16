$( document ).ready(function(){

	$cifInput = $( "[name='cif']" );
	$accordionsTriggers = $( ".panel-heading" );
	$comunidadAutonoma = $("[name='comunidadAutonoma']");

	$cifInput.on( "keyup", actionCheckIfCifIsAvailable );
	$( "[name='comunidadAutonoma']" ).on( "change", actionShowProvinciasForCA );
	$accordionsTriggers.on( "click", actionToggleRespectiveAccordion );
	$( "#gymDataForm").on( "submit", actionSubmitGymDataForm );
	$( "#signerDataForm").on( "submit", actionSubmitSignerDataForm );

	$cifInput.popover({
		container: "body",
		content: $( "[name='cifPopoverAlertMessage']" ).val(),
		placement: "top",
		trigger: "manual"
	});
	$( "[name='contactName']" ).popover({
		container: "#signerDataFormModal",
		content: $( "[name='weNeedYourName']" ).val(),
		placement: "bottom",
		trigger: "hover focus"
	});
		
});

/**
 * Muestra un popover de aviso si el cif introducido no esta disponible
*/
function actionCheckIfCifIsAvailable(){	
	var $cif = $( this );
	var value = $cif.val();

	if( !isNotFilled( $cif ) ){
		$.ajax({
			type: "POST",
			url: "controllers/ajax/gymsAjaxController.php",
			data: {action: "returnOneIfCifIsAvailable", cif: value}
		}).done( function( response ){

			if( response == 1 )
				$cif.popover( "hide" );
			else
				$cif.popover( "show" );			
		});
	} else
		$cif.popover( "hide" );
}


/*
 * Muestra el modal de inserción de datos del anunciante
*/
function actionSubmitGymDataForm( event ){
	event.preventDefault();

	var $pageHeaderPosition = $( ".page-header" ).offset().top;
	var $signerDataFormModal = $( "#signerDataFormModal ");
	var $contactName = $( "[name='contactName']" );

	if( !isAnyPopoverVisible() ){	
		$signerDataFormModal.modal( "show" );
		if( !isNotFilled( $contactName.val() ) ){
			setTimeout( function(){
				$contactName.popover( "show" );
			}, "1000" );
		}
	} 	else
		$( window ).scrollTop( $pageHeaderPosition );
}

/**
 * 
*/
function getAllDataAndReturnItAsAnObject(){
	var data = new Object();

	data.gymName = $( "[name='name']" ).val();
	data.cif = $( "[name='cif']" ).val();
	data.address = $( "[name='address']" ).val();
	data.postcode = $( "[name='postcode']" ).val();
	data.ca = $( "[name='comunidadAutonoma']" ).val();
	data.provincia = $( "[name='provincia']" ).val();
	data.email = $( "[name='email']" ).val();
	data.phoneNumber = $( "[name='telefono']" ).val();

	if( aux = $( "[name='otroTelefono']" ).val() )
		data.otherPhoneNumber = aux;

	data.contactName = $( "[name='contactName']" ).val();
	console.log( data.contactName );
	data.occupation = $( "[name='occupation']" ).val();
	console.log( data.occupation );
	data.gymFeatures = prepareCheckboxesArray( $("input[type='checkbox']:checked") );

	return data;
}

/**
 * Crea un array con los values de los chechboxes marcados a partir del objeto checkboxes recibido y lo devuelve
*/
function prepareCheckboxesArray( checkboxes ){
	var arr = new Array();

	$.each( checkboxes, function( key, val ){
		arr.push( val.value );
	});

	return arr;
}

/*
 * Recoge los datos mediante una llamada a getAllDataAndReturnItAsAnObject() si las contraseñas introducidas
 * coinciden. Envía los datos via AJAX y acaba llamando 
*/
function actionSubmitSignerDataForm( event ){
	event.preventDefault();

	var userId = $( "[name='userId']" ).val();
	var gymData = getAllDataAndReturnItAsAnObject();

	$.ajax({
		type: "POST",
		url: "controllers/ajax/gymsAjaxController.php",
		data: {action: "registerNewGym", userId: userId, gymData: gymData }
	}).done( function( response ){

		showFinalModalAndContinue();
	});
}

/*
 * Muestra el modal informando del registro satisfactorio y cambia de página cuando el usuario lo cierra
*/
function showFinalModalAndContinue(){
	var content = $( "[name='gymSigned']" ).val();
	var $finalFakeForm = $( "#continue" );

	bootbox.alert( content, function(){
		$finalFakeForm.submit();
	} );
}