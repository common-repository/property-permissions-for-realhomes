<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://hassan-raza.com
 * @since      1.0.0
 *
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/includes
 * @author     Hassan Raza <hassanazmy@gmail.com>
 */
class Property_Permissions_For_Realhomes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'property-permissions-for-realhomes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
