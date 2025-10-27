(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	 	$('.select2').select2({width: 'resolve'});
	 	$('.select2-products').select2({
	 		width: 'resolve', 
	 		maximumSelectionLength: 2
		}).on("select2:selecting", function (e) {
	        if ($(this).val() && $(this).val().length >= 2) {
	            e.preventDefault();
	        }
	    });

		if( jQuery().intlTelInput ) {
			$(".intl-tel-input").intlTelInput({
				formatOnDisplay: true,
				utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
			});  
		}

		if( jQuery().iris ) {
			$('.color-picker').iris({
				// or in the data-default-color attribute on the input
				defaultColor: true,
				// a callback to fire whenever the color changes to a valid color
				change: function(event, ui) {
					// $('input:not(.color-picker)').iris('hide');
				},
				// a callback to fire when the input is emptied or an invalid color
				clear: function() {},
				// hide the color picker controls on load
				hide: true,
				// show a group of common colors beneath the square
				palettes: true
			});
		}
		
		if( typeof CTAButtonObj !== "undefined" ) {
			if(
				CTAButtonObj.slider_id === 'redirect' || 
				CTAButtonObj.slider_id === 'accommodation' || 
				CTAButtonObj.slider_id === 'appointment' || 
				CTAButtonObj.slider_id === 'consultation'
			) {
				toggle_redirect_field($('#ctabutton-settings-' + CTAButtonObj.slider_id + '-external_1').is(':checked'));

				// Redirect Page
				// Toggle fields on redirect checkbox toggle
				$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-external_1').on('change', function(e) {
					toggle_redirect_field(this.checked);
				});
			}

			if( CTAButtonObj.slider_id === 'call' || CTAButtonObj.slider_id === 'message') {
				$( 'form' ).on('click', '#submit', function(e) {
					e.preventDefault();

					var values = $(this).closest('form').serializeArray()
					var submitBtn;

					// There might be multiple inputs
					$(".intl-tel-input").each(function( e ) {
						var $intlTelInput  = $(this);
						
						var phoneFieldName = $intlTelInput.attr('name');

						var phoneData = $intlTelInput.intlTelInput("getSelectedCountryData")
						var number = $intlTelInput.intlTelInput("getNumber")
			
						if( number.charAt(0) !== '+' ) {
							number = '+' + phoneData.dialCode + number
						}
						
						values.find(input => input.name == phoneFieldName).value = number;

					})

					submitBtn = $(this);

					submitBtn.prop('disabled', true).val('Saving Changes...')
		
					$.post(CTAButtonObj.settings_url, values, function() {
						if( ! $('#wpbody-content .notice').length > 0) {
							$('#wpbody-content').prepend('<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"><p><strong>Settings saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>')
						}
		
						submitBtn.prop('disabled', false).val('Save Changes')
					})
				})
			}
		}

	    function toggle_redirect_field(checked) {
	    	if(checked) {
	    		$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-page').closest('tr').hide();
	    		$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-link').closest('tr').show();

				// Show redirect to new tab toggle
				$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-redirect-to-tab_1').closest('tr').show();
	    	} else {
	    		$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-link').closest('tr').hide();
	    		$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-page').closest('tr').show();

				// Hide redirect to new tab toggle
				$('#ctabutton-settings-' + CTAButtonObj.slider_id + '-redirect-to-tab_1').closest('tr').hide();
	    	}
	    }

		$('.tabs').on('click', '.tab-label', function() {
			
			$(".tab input").not('.wppd-ui-toggle').each(function (index, element) {
				$(element).prop('checked', false);
			});
			$(".tab-content").each(function (index, element) {
				$(element).hide();
			});

			$(this).closest('.tab').find('.tab-content').show();
			$(this).closest('.tab').find("input").not('.wppd-ui-toggle').prop('checked', true);
		});
	});
	 
})( jQuery );
