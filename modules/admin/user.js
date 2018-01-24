$(function() {
	$( '.data-table' ).tablesorter({
		headers: {
			0: {sorter: false},
			6: {sorter: false}
		}
	});

	// data table selector
	$( '.select-all-items' ).on('click', function() {
		if ($( this ).prop('checked')) {
			$( 'td.column-select > input' ).prop('checked', 'checked');
		} else {
			$( 'td.column-select > input' ).prop('checked', false);
		}
	})
	$( 'td.column-select > input' ).on('click', function() {
		$( '.select-all-items' ).prop('checked', false);
	})
})
