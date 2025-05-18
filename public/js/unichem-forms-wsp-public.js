(function( $ ) {
	'use strict';

	/**
	 * Handle WhatsApp form submission to avoid popup blocking issues
	 */
	$(document).ready(function() {
		// Initialize WhatsApp form handling
		initWhatsAppFormHandler();
	});

	/**
	 * Set up the WhatsApp form handling
	 * Enhances user experience by providing feedback in the original tab
	 */
	function initWhatsAppFormHandler() {
		$('.unichem-whatsapp-form').on('submit', function(e) {
			// Don't prevent default behavior - we want the form to submit normally to a new tab
			
			// Clear any existing messages
			$('.unichem-form-message').remove();
			
			// Show processing message on the current page
			var formContainer = $(this).parent();
			var formElement = $(this);
			
			// Create success message
			var successMessage = $('<div class="form-success unichem-form-message"></div>')
				.html('<p>✅ Form submitted! WhatsApp should open in a new tab.</p>')
				.hide();
			
			// Add a small loading indicator and disable submit button temporarily
			var submitBtn = formElement.find('button[type="submit"]');
			var originalText = submitBtn.text();
			submitBtn.prop('disabled', true).text('Sending...');
			
			// Show success message and restore button after short delay
			setTimeout(function() {
				submitBtn.prop('disabled', false).text(originalText);
				formContainer.append(successMessage);
				successMessage.fadeIn();
				
				// Remove message after a while
				setTimeout(function() {
					successMessage.fadeOut(function() {
						$(this).remove();
					});
				}, 7000);
			}, 800);
		});
	}

})( jQuery );
