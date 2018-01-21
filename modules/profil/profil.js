$(function () {
	hideByType($( '.select#type' ).val());

	$( '.select#type' ).change(function(e) {
		hideByType($( this ).val());
	});

	function hideByType(type)
	{
		if (type == 'student')
		{
			$( '.inputGroup-abbreviation' ).hide();
			$( '.inputGroup-class' ).show();
		}
		else
		{
			$( '.inputGroup-abbreviation' ).show();
			$( '.inputGroup-class' ).hide();
		}
	}
});
