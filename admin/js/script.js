'use strict';

;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));
'use strict';

;( function( $, window, document, undefined )
{
	$( '.inputfile' ).each( function()
	{
		var $input	 = $( this ),
			$label	 = $input.next( 'label' ),
			labelVal = $label.html();

		$input.on( 'change', function( e )
		{
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				$label.find( 'span' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
})

async function supImage(id){
	var div = document.getElementById("image");
	var idImg = document.getElementById("img" + id);
	var idBtn = document.getElementById(id);
	var fichier = $(idImg).attr("name");

	var response = await fetch("../API/apiway.php?demande=image&name=" + fichier);
    // var status = await response.json();
	// console.log(status);
	// if(status == "sup"){
		div.removeChild(idImg);
		div.removeChild(idBtn);
	// } 
}

$(".custom-select").change(function () {
	$.get("../assets/src/regionCarte/position.json", function(data){
		for(var i=0; i < data.length; i++){
			if($(".custom-select option:selected").text() == data[i]["region"]){
				$("#longitude").val(data[i]["coordinateX"]);
				$("#latitude").val(data[i]["coordinateY"]);
			}
		}
	});
})