(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
	$(function() {
	 	$('.ctabutton .bottom-sheet').on('click', '.close', function(e) {
	 		e.preventDefault(); 		

			 $( '.ctabutton .bottom-sheet' )
			 	.removeClass('open');

			 $( '.ctabutton button.launch-btn' ).fadeIn(500);
	 	});

		$('.ctabutton button.launch-btn').on('click', function(e) {
		   	$(this).css( 'display', 'none' );
			$( '.ctabutton .bottom-sheet' )
			   .addClass('open');
		});
	});

})( jQuery );
