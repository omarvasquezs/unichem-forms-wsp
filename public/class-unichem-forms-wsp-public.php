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
		add_action('admin_post_nopriv_unichem_whatsapp_form_submit', array($this, 'handle_form_submission'));
		add_action('admin_post_unichem_whatsapp_form_submit', array($this, 'handle_form_submission'));
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
		<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="unichem-whatsapp-form">
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
		<title>Redirecting to WhatsApp...</title>
		<script>
		window.onload = function() {
		  window.location.replace(<?php echo json_encode($wa_url); ?>);
		};
		</script>
		</head>
		<body>
		<p><?php esc_html_e('Redirecting to WhatsApp...', 'unichem-forms-wsp'); ?></p>
		<noscript>
		  <meta http-equiv="refresh" content="0;url=<?php echo esc_attr($wa_url); ?>">
		  <p><?php esc_html_e('If you are not redirected, click', 'unichem-forms-wsp'); ?> <a href="<?php echo esc_attr($wa_url); ?>">WhatsApp</a>.</p>
		</noscript>
		</body>
		</html><?php
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

}
