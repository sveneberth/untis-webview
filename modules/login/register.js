$(function () {
	$( '.inputGroup-abbreviation' ).hide();

	$( '.select#type' ).change(function(e) {
		var val = $( this ).val();

		if (val == 'student')
		{
			$( '.inputGroup-abbreviation' ).hide();
			$( '.inputGroup-class' ).show();
		}
		else
		{
			$( '.inputGroup-abbreviation' ).show();
			$( '.inputGroup-class' ).hide();
		}
	});
});
