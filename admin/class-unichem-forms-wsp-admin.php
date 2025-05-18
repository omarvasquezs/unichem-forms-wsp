<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://beacons.ai/omarvasquez
 * @since      1.0.0
 *
 * @package    Unichem_Forms_Wsp
 * @subpackage Unichem_Forms_Wsp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Unichem_Forms_Wsp
 * @subpackage Unichem_Forms_Wsp/admin
 * @author     Omar Vásquez <omarvs91@gmail.com>
 */
class Unichem_Forms_Wsp_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/unichem-forms-wsp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/unichem-forms-wsp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add admin menu and submenus for WhatsApp integration.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__('Unichem WhatsApp', 'unichem-forms-wsp'),
			__('Unichem WhatsApp', 'unichem-forms-wsp'),
			'manage_options',
			'unichem-forms-wsp', // main menu slug
			array($this, 'form_builder_page_html'), // Set Form Builder as default
			'dashicons-whatsapp',
			56
		);
		add_submenu_page(
			'unichem-forms-wsp', // parent slug matches main menu slug
			 __('Form Builder', 'unichem-forms-wsp'),
			__('Form Builder', 'unichem-forms-wsp'),
			'manage_options',
			'unichem-forms-wsp', // first submenu slug matches main menu slug
			array($this, 'form_builder_page_html')
		);
		add_submenu_page(
			'unichem-forms-wsp',
			__('Settings', 'unichem-forms-wsp'),
			__('Settings', 'unichem-forms-wsp'),
			'manage_options',
			'unichem-forms-wsp-settings',
			array($this, 'settings_page_html')
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting('unichem_forms_wsp_settings', 'unichem_forms_wsp_country_code');
		register_setting('unichem_forms_wsp_settings', 'unichem_forms_wsp_phone_number');
		register_setting('unichem_forms_wsp_settings', 'unichem_forms_wsp_submit_text');
		register_setting('unichem_forms_wsp_settings', 'unichem_forms_wsp_custom_css'); // Register custom CSS option
	}

	/**
	 * Add plugin settings link to the plugins page.
	 *
	 * @since    1.1.0
	 * @param    array    $links    The array of plugin action links.
	 * @return   array    The filtered array of plugin action links.
	 */
	public function add_plugin_settings_link($links) {
		// Add form builder link
		$builder_link = '<a href="' . admin_url('admin.php?page=unichem-forms-wsp') . '">' . __('Form Builder', 'unichem-forms-wsp') . '</a>';
		
		// Add settings link
		$settings_link = '<a href="' . admin_url('admin.php?page=unichem-forms-wsp-settings') . '">' . __('Settings', 'unichem-forms-wsp') . '</a>';
		
		// Insert our links at the beginning of the array
		array_unshift($links, $settings_link);
		array_unshift($links, $builder_link);
		
		return $links;
	}

	/**
	 * Output the Unichem WhatsApp Forms instructions box (for admin pages).
	 */
	public static function render_instructions_box($context = 'form_builder') {
		?>
		<div class="unichem-instructions" style="background: #f8fafc; border: 1px solid #dbeafe; border-radius: 6px; padding: 18px 24px; margin-bottom: 32px; max-width: 900px;">
		  <h3 style="margin-top:0; font-size:1.2em; font-weight:600; color:#1e293b;">How to use Unichem WhatsApp Forms</h3>
		  <ul style="margin:0 0 0 18px; padding:0; color:#334155; font-size:1em;">
		    <li><strong>1. Build your form:</strong> Add, remove, reorder, and configure fields<?php echo $context === 'settings' ? ' in the <b>Form Builder</b> tab' : ' below'; ?>. Supported types: Text, Textarea, Email, Phone.</li>
		    <li><strong>2. Save your form fields:</strong> Click <b>Save Form Fields</b> after making changes.</li>
		    <li><strong>3. Configure WhatsApp destination:</strong> <?php echo $context === 'settings' ? 'Set the country code and phone number below' : 'Go to <b>Settings</b> (left menu) and set the country code and phone number'; ?> where form submissions will be sent.</li>
		    <li><strong>4. Display the form:</strong> Use the shortcode <code>[unichem_whatsapp_form]</code> in any post, page, or widget to show your form on the frontend.</li>
		    <li><strong>5. Submission:</strong> When a user submits the form, they will be redirected to WhatsApp with the form data pre-filled, ready to send to your configured number.</li>
		  </ul>
		  <div style="margin-top:10px; color:#64748b; font-size:0.97em;">
		    <b>Tip:</b> You can add this form multiple times to your site by using the shortcode in different pages.
		  </div>
		</div>
		<?php
	}

	/**
	 * Add form builder to the admin settings page.
	 */
	public function render_form_builder() {
		$form_fields = get_option('unichem_forms_wsp_form_fields', [
			['type' => 'text', 'label' => 'Your Name', 'name' => 'unichem_name', 'required' => true],
			['type' => 'textarea', 'label' => 'Your Message', 'name' => 'unichem_message', 'required' => true],
		]);
		if (isset($_POST['unichem_forms_wsp_save_form_fields'])) {
			$fields = isset($_POST['form_fields']) ? $_POST['form_fields'] : [];
			$clean_fields = [];
			foreach ($fields as $field) {
				if (!empty($field['label']) && !empty($field['name'])) {
					$clean_fields[] = [
						'type' => sanitize_text_field($field['type']),
						'label' => sanitize_text_field($field['label']),
						'name' => sanitize_key($field['name']),
						'placeholder' => isset($field['placeholder']) ? sanitize_text_field($field['placeholder']) : '',
						'hide_label' => !empty($field['hide_label']) ? 1 : 0,
						'required' => !empty($field['required'])
					];
				}
			}
			update_option('unichem_forms_wsp_form_fields', $clean_fields);
			$form_fields = $clean_fields;
			printf('<div class="updated"><p>%s</p></div>', esc_html__('Form fields updated.', 'unichem-forms-wsp'));
		}
		?>
		<h2 style="margin-top: 0; font-size: 2em; letter-spacing: 1px; font-weight: 700; color: #222; margin-bottom: 32px;">UNICHEM FORMS - FORM BUILDER</h2>
		<?php self::render_instructions_box('form_builder'); ?>
		<form method="post" class="unichem-form-builder-form" style="margin-top: 32px;">
			<table class="acf-table" id="unichem-form-builder-table">
				<thead>
					<tr>
						<th style="width: 5%"></th>
						<th style="width: 20%">Type</th>
						<th style="width: 30%">Label</th>
						<th style="width: 25%">Name</th>
						<th style="width: 25%">Placeholder</th>
						<th style="width: 10%">Required</th>
						<th style="width: 10%">Hide Label</th>
						<th style="width: 10%">Remove</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($form_fields as $i => $field): ?>
				<tr class="acf-row">
						<td class="acf-drag-handle" style="text-align:center; cursor:move;">
							<span class="dashicons dashicons-menu"></span>
						</td>
					<td>
						<select name="form_fields[<?php echo $i; ?>][type]" class="acf-input">
							<option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
							<option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
							<option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
							<option value="phone" <?php selected($field['type'], 'phone'); ?>>Phone</option>
						</select>
					</td>
					<td><input type="text" name="form_fields[<?php echo $i; ?>][label]" value="<?php echo esc_attr($field['label']); ?>" class="acf-input" required /></td>
					<td><input type="text" name="form_fields[<?php echo $i; ?>][name]" value="<?php echo esc_attr($field['name']); ?>" class="acf-input" required /></td>
						<td><input type="text" name="form_fields[<?php echo $i; ?>][placeholder]" value="<?php echo isset($field['placeholder']) ? esc_attr($field['placeholder']) : ''; ?>" class="acf-input" /></td>
					<td style="text-align: center;"><input type="checkbox" name="form_fields[<?php echo $i; ?>][required]" value="1" <?php checked($field['required']); ?> /></td>
					<td style="text-align: center;"><input type="checkbox" name="form_fields[<?php echo $i; ?>][hide_label]" value="1" <?php checked(!empty($field['hide_label'])); ?> /></td>
					<td style="text-align: center;"><button type="button" class="remove-field button acf-remove">×</button></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p><button type="button" class="button button-secondary" id="add-form-field">Add Field</button></p>
			<p><input type="submit" name="unichem_forms_wsp_save_form_fields" class="button button-primary" value="<?php esc_attr_e('Save Form Fields', 'unichem-forms-wsp'); ?>" /></p>
		</form>
			<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
		<script>
		(function($){
			$('#add-form-field').on('click', function(){
				var i = $('#unichem-form-builder-table tbody tr').length;
				var row = '<tr class="acf-row">' +
					'<td class="acf-drag-handle" style="text-align:center; cursor:move;"><span class="dashicons dashicons-menu"></span></td>' +
					'<td><select name="form_fields['+i+'][type]" class="acf-input">' +
						'<option value="text">Text</option>' +
						'<option value="textarea">Textarea</option>' +
						'<option value="email">Email</option>' +
						'<option value="phone">Phone</option>' +
					'</select></td>' +
					'<td><input type="text" name="form_fields['+i+'][label]" class="acf-input" required /></td>' +
					'<td><input type="text" name="form_fields['+i+'][name]" class="acf-input" required /></td>' +
					'<td><input type="text" name="form_fields['+i+'][placeholder]" class="acf-input" /></td>' +
					'<td style="text-align: center;"><input type="checkbox" name="form_fields['+i+'][required]" value="1" /></td>' +
					'<td style="text-align: center;"><input type="checkbox" name="form_fields['+i+'][hide_label]" value="1" /></td>' +
					'<td style="text-align: center;"><button type="button" class="remove-field button acf-remove">×</button></td>' +
				'</tr>';
				$('#unichem-form-builder-table tbody').append(row);
			});
			$(document).on('click', '.remove-field', function(){
				$(this).closest('tr').remove();
			});

			// Make rows sortable
			$('#unichem-form-builder-table tbody').sortable({
				handle: '.acf-drag-handle',
				axis: 'y',
				placeholder: 'acf-row-placeholder',
				update: function(event, ui) {
					// Re-index the field names after sorting
					$('#unichem-form-builder-table tbody tr').each(function(i, tr){
						$(tr).find('select, input').each(function(){
							var name = $(this).attr('name');
							if(name) {
								var newName = name.replace(/form_fields\[[0-9]+\]/, 'form_fields['+i+']');
								$(this).attr('name', newName);
							}
						});
					});
				}
			});
		})(jQuery);
		</script>
		<style>
		.unichem-form-builder-form {
			max-width: 1100px;
		}
		.acf-table {
			width: 100%;
			border-collapse: collapse;
			background: #fff;
			margin-bottom: 20px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.03);
		}
		.acf-table th, .acf-table td {
			border: 1px solid #e1e1e1;
			padding: 10px 8px;
			font-size: 15px;
		}
		.acf-table th {
			background: #f9f9f9;
			font-weight: 600;
		}
		.acf-row:nth-child(even) {
			background: #f7f7f7;
		}
		.acf-input {
			width: 100%;
			padding: 6px 8px;
			font-size: 15px;
			border: 1px solid #d1d1d1;
			border-radius: 3px;
			background: #fff;
		}
		.acf-remove {
			color: #b32d2e;
			font-size: 18px;
			background: #fff;
			border: 1px solid #e1e1e1;
			border-radius: 3px;
			cursor: pointer;
			transition: background 0.2s;
		}
		.acf-remove:hover {
			background: #fbeaea;
		}
		#add-form-field {
			margin-bottom: 10px;
		}
		.acf-row-placeholder {
			height: 48px;
			background: #eaf6ff;
			border: 2px dashed #007cba;
		}
		.acf-row {
			cursor: move;
		}
		.acf-drag-handle {
			width: 32px;
			color: #888;
			font-size: 22px;
			vertical-align: middle;
			user-select: none;
		}
		.acf-drag-handle .dashicons {
			font-size: 22px;
			line-height: 1.2;
			vertical-align: middle;
		}
		</style>
		<?php
	}

	/**
	 * Add a settings section for custom CSS in the plugin settings page.
	 */
	public function render_custom_css_setting() {
		$custom_css = get_option('unichem_forms_wsp_custom_css', '');
		?>
		<h2 style="margin-top: 40px; font-size: 1.3em; font-weight: 600; color: #1e293b;">Form Style Customization</h2>
		<p style="color:#334155; margin-bottom:10px;">Add your own CSS to customize the appearance of the Unichem WhatsApp form. This will override the default style. <br><span style="font-size:0.97em; color:#64748b;">(Tip: Use <code>.unichem-whatsapp-form</code> as the selector.)</span></p>
		<textarea name="unichem_forms_wsp_custom_css" rows="7" style="width:100%; font-family:monospace; font-size:1em; border-radius:6px; border:1px solid #cbd5e1; padding:10px; background:#f8fafc; color:#334155;"><?php echo esc_textarea($custom_css); ?></textarea>
		<?php
	}

	/**
	 * Render only the WhatsApp settings page (no form builder).
	 */
	public function settings_page_html() {
		if (!current_user_can('manage_options')) {
			return;
		}
		$country_codes = array(
			array('code' => '1', 'name' => 'United States (+1)'),
			array('code' => '7', 'name' => 'Russia (+7)'),
			array('code' => '20', 'name' => 'Egypt (+20)'),
			array('code' => '27', 'name' => 'South Africa (+27)'),
			array('code' => '30', 'name' => 'Greece (+30)'),
			array('code' => '31', 'name' => 'Netherlands (+31)'),
			array('code' => '32', 'name' => 'Belgium (+32)'),
			array('code' => '33', 'name' => 'France (+33)'),
			array('code' => '34', 'name' => 'Spain (+34)'),
			array('code' => '36', 'name' => 'Hungary (+36)'),
			array('code' => '39', 'name' => 'Italy (+39)'),
			array('code' => '40', 'name' => 'Romania (+40)'),
			array('code' => '41', 'name' => 'Switzerland (+41)'),
			array('code' => '43', 'name' => 'Austria (+43)'),
			array('code' => '44', 'name' => 'United Kingdom (+44)'),
			array('code' => '45', 'name' => 'Denmark (+45)'),
			array('code' => '46', 'name' => 'Sweden (+46)'),
			array('code' => '47', 'name' => 'Norway (+47)'),
			array('code' => '48', 'name' => 'Poland (+48)'),
			array('code' => '49', 'name' => 'Germany (+49)'),
			array('code' => '51', 'name' => 'Peru (+51)'),
			array('code' => '52', 'name' => 'Mexico (+52)'),
			array('code' => '53', 'name' => 'Cuba (+53)'),
			array('code' => '54', 'name' => 'Argentina (+54)'),
			array('code' => '55', 'name' => 'Brazil (+55)'),
			array('code' => '56', 'name' => 'Chile (+56)'),
			array('code' => '57', 'name' => 'Colombia (+57)'),
			array('code' => '58', 'name' => 'Venezuela (+58)'),
			array('code' => '60', 'name' => 'Malaysia (+60)'),
			array('code' => '61', 'name' => 'Australia (+61)'),
			array('code' => '62', 'name' => 'Indonesia (+62)'),
			array('code' => '63', 'name' => 'Philippines (+63)'),
			array('code' => '64', 'name' => 'New Zealand (+64)'),
			array('code' => '65', 'name' => 'Singapore (+65)'),
			array('code' => '66', 'name' => 'Thailand (+66)'),
			array('code' => '81', 'name' => 'Japan (+81)'),
			array('code' => '82', 'name' => 'South Korea (+82)'),
			array('code' => '84', 'name' => 'Vietnam (+84)'),
			array('code' => '86', 'name' => 'China (+86)'),
			array('code' => '90', 'name' => 'Turkey (+90)'),
			array('code' => '91', 'name' => 'India (+91)'),
			array('code' => '92', 'name' => 'Pakistan (+92)'),
			array('code' => '93', 'name' => 'Afghanistan (+93)'),
			array('code' => '94', 'name' => 'Sri Lanka (+94)'),
			array('code' => '95', 'name' => 'Myanmar (+95)'),
			array('code' => '98', 'name' => 'Iran (+98)'),
			array('code' => '212', 'name' => 'Morocco (+212)'),
			array('code' => '213', 'name' => 'Algeria (+213)'),
			array('code' => '216', 'name' => 'Tunisia (+216)'),
			array('code' => '218', 'name' => 'Libya (+218)'),
			array('code' => '234', 'name' => 'Nigeria (+234)'),
			array('code' => '251', 'name' => 'Ethiopia (+251)'),
			array('code' => '351', 'name' => 'Portugal (+351)'),
			array('code' => '352', 'name' => 'Luxembourg (+352)'),
			array('code' => '353', 'name' => 'Ireland (+353)'),
			array('code' => '354', 'name' => 'Iceland (+354)'),
			array('code' => '358', 'name' => 'Finland (+358)'),
			array('code' => '380', 'name' => 'Ukraine (+380)'),
			array('code' => '381', 'name' => 'Serbia (+381)'),
			array('code' => '385', 'name' => 'Croatia (+385)'),
			array('code' => '386', 'name' => 'Slovenia (+386)'),
			array('code' => '420', 'name' => 'Czech Republic (+420)'),
			array('code' => '421', 'name' => 'Slovakia (+421)'),
			array('code' => '972', 'name' => 'Israel (+972)'),
			array('code' => '973', 'name' => 'Bahrain (+973)'),
			array('code' => '974', 'name' => 'Qatar (+974)'),
			array('code' => '975', 'name' => 'Bhutan (+975)'),
			array('code' => '976', 'name' => 'Mongolia (+976)'),
			array('code' => '977', 'name' => 'Nepal (+977)'),
			array('code' => '994', 'name' => 'Azerbaijan (+994)'),
			// ...add more as needed...
		);
		$current_code = get_option('unichem_forms_wsp_country_code');
		?>
		<div class="wrap">
			<h2 style="margin-top: 0; font-size: 2em; letter-spacing: 1px; font-weight: 700; color: #222; margin-bottom: 32px;">UNICHEM FORMS - SETTINGS</h2>
			<?php self::render_instructions_box('settings'); ?>
			<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']): ?>
				<div id="message" class="updated notice is-dismissible"><p><?php _e('Settings saved.', 'unichem-forms-wsp'); ?></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">
				<?php
				settings_fields('unichem_forms_wsp_settings');
				do_settings_sections('unichem_forms_wsp_settings');
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Country Code', 'unichem-forms-wsp'); ?></th>
						<td>
							<select name="unichem_forms_wsp_country_code" required>
								<option value=""><?php _e('Select country code', 'unichem-forms-wsp'); ?></option>
								<?php foreach ($country_codes as $c): ?>
									<option value="<?php echo esc_attr($c['code']); ?>" <?php selected($current_code, $c['code']); ?>><?php echo esc_html($c['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Phone Number', 'unichem-forms-wsp'); ?></th>
						<td><input type="text" name="unichem_forms_wsp_phone_number" value="<?php echo esc_attr(get_option('unichem_forms_wsp_phone_number')); ?>" size="20" inputmode="numeric" autocomplete="off" pattern="[0-9]+" title="Only digits" required oninput="this.value=this.value.replace(/[^0-9]/g,''); this.setCustomValidity('');" oninvalid="this.setCustomValidity('Only digits');" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Submit Button Text', 'unichem-forms-wsp'); ?></th>
						<td><input type="text" name="unichem_forms_wsp_submit_text" value="<?php echo esc_attr(get_option('unichem_forms_wsp_submit_text', 'Send to WhatsApp')); ?>" size="32" required /></td>
					</tr>
				</table>
				<?php $this->render_custom_css_setting(); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render only the form builder page (no WhatsApp settings).
	 */
	public function form_builder_page_html() {
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
		<div class="wrap">
			<?php $this->render_form_builder(); ?>
		</div>
		<?php
	}

}
