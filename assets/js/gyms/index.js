$( document ).ready( function(){
	$searchInput = $( "[name='searchInput']" );

	$searchInput.on( "keypress", function( e ){

		if( wasEnterPressed( e ) ){
			e.preventDefault();
			search();	
		}
			
	} );
       
	$( "[name='searchButton']" ).on( "click", search );
} );

/**
 * Gestiona el envio del formulario al backend con la busqueda introducida en el campo de texto
*/
function search(){
	SEARCH_ACTION = "?r=gyms/search";
	var newUrl = SEARCH_ACTION;
	var searchText = $searchInput.val();

	if( !isNotFilled( $searchInput ) )
		newUrl += "&searchInput=" + searchText;

	window.location.href = newUrl; 
	
}