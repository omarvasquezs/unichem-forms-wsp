<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://beacons.ai/omarvasquez
 * @since      1.0.0
 *
 * @package    Unichem_Forms_Wsp
 * @subpackage Unichem_Forms_Wsp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Unichem_Forms_Wsp
 * @subpackage Unichem_Forms_Wsp/public
 * @author     Omar Vásquez <omarvs91@gmail.com>
 */
class Unichem_Forms_Wsp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Unichem_Forms_Wsp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Unichem_Forms_Wsp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/unichem-forms-wsp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Unichem_Forms_Wsp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Unichem_Forms_Wsp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/unichem-forms-wsp-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register shortcode and handle form submission for WhatsApp integration.
	 */
	public function register_form_shortcode() {
		add_shortcode('unichem_whatsapp_form', array($this, 'render_whatsapp_form'));
		
		// Handle form submissions for both logged-in and non-logged-in users
		add_action('admin_post_nopriv_unichem_whatsapp_form_submit', array($this, 'handle_form_submission'));
		add_action('admin_post_unichem_whatsapp_form_submit', array($this, 'handle_form_submission'));
		
		// Add meta tags to help with redirects and referrer policy
		add_action('wp_head', array($this, 'add_meta_tags'));
	}

	/**
	 * Render the WhatsApp form via shortcode, using the form builder fields.
	 */
	public function render_whatsapp_form($atts = array()) {
		$form_fields = get_option('unichem_forms_wsp_form_fields', []);
		if (empty($form_fields)) {
			return '<p>' . esc_html__('No form fields defined. Please configure the form in the admin.', 'unichem-forms-wsp') . '</p>';
		}
		$submit_text = get_option('unichem_forms_wsp_submit_text', __('Send to WhatsApp', 'unichem-forms-wsp'));
		ob_start();
		?>
		<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="unichem-whatsapp-form" target="_blank">
			<input type="hidden" name="action" value="unichem_whatsapp_form_submit" />
			<?php foreach ($form_fields as $field):
				$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
				$hide_label = !empty($field['hide_label']);
			?>
				<div class="unichem-form-field">
				<?php if (!$hide_label): ?>
					<label><?php echo esc_html($field['label']); ?>:<br>
				<?php endif; ?>
					<?php if ($field['type'] === 'textarea'): ?>
						<textarea name="<?php echo esc_attr($field['name']); ?>" <?php if ($field['required']) echo 'required'; ?> placeholder="<?php echo esc_attr($placeholder); ?>"></textarea>
					<?php elseif ($field['type'] === 'phone'): ?>
						<input type="tel" name="<?php echo esc_attr($field['name']); ?>" <?php if ($field['required']) echo 'required'; ?> placeholder="<?php echo esc_attr($placeholder); ?>" />
					<?php else: ?>
						<input type="<?php echo esc_attr($field['type']); ?>" name="<?php echo esc_attr($field['name']); ?>" <?php if ($field['required']) echo 'required'; ?> placeholder="<?php echo esc_attr($placeholder); ?>" />
					<?php endif; ?>
				<?php if (!$hide_label): ?>
					</label>
				<?php endif; ?>
				</div>
			<?php endforeach; ?>
			<button type="submit"><?php echo esc_html($submit_text); ?></button>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Handle form submission and send data to WhatsApp API URL, using dynamic fields.
	 */
	public function handle_form_submission() {
		$form_fields = get_option('unichem_forms_wsp_form_fields', []);
		$data = [];
		foreach ($form_fields as $field) {
			$name = $field['name'];
			if ($field['required'] && empty($_POST[$name])) {
				wp_die(__('Please fill all required fields.', 'unichem-forms-wsp'));
			}
			if ($field['type'] === 'textarea') {
				$data[$name] = sanitize_textarea_field($_POST[$name] ?? '');
			} else {
				$data[$name] = sanitize_text_field($_POST[$name] ?? '');
			 }
		}
		$country_code = get_option('unichem_forms_wsp_country_code');
		$phone_number = get_option('unichem_forms_wsp_phone_number');
		$full_phone = $country_code . $phone_number;
		// WhatsApp API URL (standard)
		$base_url = 'https://api.whatsapp.com/send';
		// Build message text from form data, using field labels and line breaks
		$message = '*¡NUEVO MENSAJE!*' . "\r\n\r\n";
		foreach ($form_fields as $field) {
			$name = $field['name'];
			$label = isset($field['label']) ? $field['label'] : $name;
			$value = isset($data[$name]) ? $data[$name] : '';
			$message .= '*' . $label . '*: ' . $value . "\r\n\r\n";
		}
		// WhatsApp expects %0A for newlines, so use rawurlencode for the message
		$wa_url = $base_url . '?phone=' . rawurlencode($full_phone) . '&text=' . rawurlencode($message);
		?><!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title><?php esc_html_e('Redirecting to WhatsApp', 'unichem-forms-wsp'); ?></title>
			<style>
				body {
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
					line-height: 1.5;
					color: #333;
					max-width: 600px;
					margin: 0 auto;
					padding: 20px;
					text-align: center;
				}
				h1 {
					margin-bottom: 30px;
				}
				.button {
					display: inline-block;
					padding: 12px 24px;
					background-color: #25D366;
					color: white;
					text-decoration: none;
					border-radius: 6px;
					font-weight: bold;
					margin: 20px 0;
					font-size: 16px;
					border: none;
					cursor: pointer;
				}
				.button:hover {
					background-color: #22c15e;
				}
				.loading {
					display: block;
					margin: 20px auto;
					width: 40px;
					height: 40px;
					border: 4px solid rgba(0, 0, 0, 0.1);
					border-radius: 50%;
					border-top-color: #25D366;
					animation: spin 1s ease-in-out infinite;
				}
				@keyframes spin {
					to { transform: rotate(360deg); }
				}
				.info {
					background-color: #f8f9fa;
					border-left: 4px solid #25D366;
					padding: 15px;
					margin: 20px 0;
					text-align: left;
				}
				.hidden {
					display: none;
				}
			</style>
		</head>
		<body>
			<h1><?php esc_html_e('Connecting to WhatsApp...', 'unichem-forms-wsp'); ?></h1>
			
			<div id="loading">
				<div class="loading"></div>
				<p><?php esc_html_e('Opening WhatsApp...', 'unichem-forms-wsp'); ?></p>
			</div>
			
			<div id="whatsapp-link" class="hidden">
				<div class="info">
					<p><?php esc_html_e('Please click the button below to continue to WhatsApp:', 'unichem-forms-wsp'); ?></p>
				</div>
				
				<a href="<?php echo esc_url($wa_url); ?>" id="whatsapp-button" class="button" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" style="vertical-align: middle; margin-right: 8px;">
						<path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
					</svg>
					<?php esc_html_e('Open WhatsApp', 'unichem-forms-wsp'); ?>
				</a>
				
				<p style="margin-top: 25px; font-size: 0.9em; color: #666;">
					<?php esc_html_e('If WhatsApp doesn\'t open automatically, check your browser settings or use the button above.', 'unichem-forms-wsp'); ?>
				</p>
				
				<div style="margin-top: 30px;">
					<button onclick="window.close();" style="background: none; border: 1px solid #ccc; padding: 8px 15px; border-radius: 4px; cursor: pointer; color: #666;">
						<?php esc_html_e('Close this window', 'unichem-forms-wsp'); ?>
					</button>
				</div>
			</div>
			
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					var waUrl = <?php echo json_encode($wa_url); ?>;
					var loadingElement = document.getElementById('loading');
					var whatsappLinkElement = document.getElementById('whatsapp-link');
					var whatsappButton = document.getElementById('whatsapp-button');
					
					// Function to handle WhatsApp redirection
					function handleWhatsAppRedirect() {
						// Since we're already in a new tab (from form target="_blank"),
						// direct navigation works best and avoids popup blocking
						// Try to redirect to WhatsApp
						window.location.href = waUrl;
						
						// Show the manual button after a short delay in case the redirect is blocked
						// or the user's device needs special handling
						setTimeout(function() {
							loadingElement.classList.add('hidden');
							whatsappLinkElement.classList.remove('hidden');
						}, 1500); // Shorter delay for better user experience
					}
					
					// Add click listener for analytics or future enhancements
					whatsappButton.addEventListener('click', function(e) {
						// Let the link work naturally, no need to prevent default
					});
					
					// Start the redirect process
					handleWhatsAppRedirect();
				});
			</script>
		</body>
		</html>
		<?php
		exit;
	}

	/**
	 * Output custom CSS from settings in the page head for the frontend form.
	 */
	public function output_custom_css() {
		$custom_css = get_option('unichem_forms_wsp_custom_css', '');
		if (!empty($custom_css)) {
			echo '<style id="unichem-forms-wsp-custom-css">' . esc_html($custom_css) . '</style>';
		}
	}

	/**
	 * Add custom meta tag to ensure forms work properly
	 */
	public function add_meta_tags() {
		echo '<meta name="referrer" content="no-referrer-when-downgrade">' . "\n";
	}

}
