/**
 * Consent Banner Nest — admin settings enhancements.
 * Turns the color inputs into WordPress color pickers.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		if ( $.fn.wpColorPicker ) {
			$( '.cp-color' ).wpColorPicker();
		}
	} );
} )( jQuery );
