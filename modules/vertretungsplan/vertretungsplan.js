$(function() {
/*	$( '.data-table' ).tablesorter({
		headers: {
			0: {sorter: false},
			6: {sorter: false}
		}
	});
*/
	// data table selector
	$( '#showAll-switch' ).on('change', function() {
		console.log(123);
		if ($( this ).prop('checked')) {
			window.location.href = '{{MAIN_URL}}/vertretungsplan/?showAll=1';
		} else {
			window.location.href = '{{MAIN_URL}}/vertretungsplan/?showAll=0';
		}
	});
})
