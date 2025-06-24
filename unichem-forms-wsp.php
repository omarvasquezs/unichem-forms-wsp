<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://beacons.ai/omarvasquez
 * @since             1.0.0
 * @package           Unichem_Forms_Wsp
 *
 * @wordpress-plugin
 * Plugin Name:       Unichem Forms - Whatsapp Integration
 * Plugin URI:        https://beacons.ai/omarvasquez
 * Description:       Unichem Forms - WhatsApp Integration allows users to create a custom form that redirect submissions to a WhatsApp API URL, enabling personalized customer engagement via WhatsApp conversations.
 * Version:           1.1.1
 * Author:            Omar Vásquez
 * Author URI:        https://beacons.ai/omarvasquez/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       unichem-forms-wsp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UNICHEM_FORMS_WSP_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-unichem-forms-wsp-activator.php
 */
function activate_unichem_forms_wsp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-unichem-forms-wsp-activator.php';
	Unichem_Forms_Wsp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-unichem-forms-wsp-deactivator.php
 */
function deactivate_unichem_forms_wsp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-unichem-forms-wsp-deactivator.php';
	Unichem_Forms_Wsp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_unichem_forms_wsp' );
register_deactivation_hook( __FILE__, 'deactivate_unichem_forms_wsp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-unichem-forms-wsp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_unichem_forms_wsp() {

	$plugin = new Unichem_Forms_Wsp();
	$plugin->run();

}
run_unichem_forms_wsp();
