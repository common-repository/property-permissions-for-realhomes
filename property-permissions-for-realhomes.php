<?php

/**
 * @since             1.0.0
 * @package           Property_Permissions_For_Realhomes
 *
 * @wordpress-plugin
 * Plugin Name:       Property Permissions For RealHomes
 * Plugin URI:        https://inspirythemes.com
 * Description:       This plugin is created specifically for RealHomes WordPress Theme. It adds permissions tab to the property meta information to manage each property permission against users separately.
 * Version:           1.0.0
 * Author:            Hassan Raza
 * Author URI:        https://hassan-raza.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       property-permissions-for-realhomes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Defining plugin current version
 */
define( 'PROPERTY_PERMISSIONS_FOR_REALHOMES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-property-permissions-for-realhomes-activator.php
 */
function activate_property_permissions_for_realhomes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-property-permissions-for-realhomes-activator.php';
	Property_Permissions_For_Realhomes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-property-permissions-for-realhomes-deactivator.php
 */
function deactivate_property_permissions_for_realhomes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-property-permissions-for-realhomes-deactivator.php';
	Property_Permissions_For_Realhomes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_property_permissions_for_realhomes' );
register_deactivation_hook( __FILE__, 'deactivate_property_permissions_for_realhomes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-property-permissions-for-realhomes.php';

/**
 * Plguin Execution
 * @since    1.0.0
 */
function run_property_permissions_for_realhomes() {

	$plugin = new Property_Permissions_For_Realhomes();
	$plugin->run();

}
run_property_permissions_for_realhomes();
