<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://hassan-raza.com
 * @since      1.0.0
 *
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/includes
 * @author     Hassan Raza <hassanazmy@gmail.com>
 */
class Property_Permissions_For_Realhomes {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Property_Permissions_For_Realhomes_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PPFR_VERSION' ) ) {
			$this->version = PPFR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'property-permissions-for-realhomes';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Property_Permissions_For_Realhomes_Loader. Orchestrates the hooks of the plugin.
	 * - Property_Permissions_For_Realhomes_i18n. Defines internationalization functionality.
	 * - Property_Permissions_For_Realhomes_Admin. Defines all hooks for the admin area.
	 * - Property_Permissions_For_Realhomes_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-property-permissions-for-realhomes-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-property-permissions-for-realhomes-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-property-permissions-for-realhomes-admin.php';

		/**
		 * The class responsible for creating permission metaboxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-type-metaboxes.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-property-permissions-for-realhomes-public.php';

		$this->loader = new Property_Permissions_For_Realhomes_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Property_Permissions_For_Realhomes_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Property_Permissions_For_Realhomes_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Property_Permissions_For_Realhomes_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$plugin_metaboxes = new PPFR_Post_Type_Meta( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'framework_theme_meta', $plugin_metaboxes, 'register_meta_boxes' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Property_Permissions_For_Realhomes_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Property query alters
		$this->loader->add_action( 'inspiry_properties_filter', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'real_homes_search_parameters', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'real_homes_homepage_properties', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'inspiry_featured_properties_filter', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'inspiry_gallery_properties_filter', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'inspiry_agent_properties_filter', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'inspiry_similar_properties_filter', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'ere_agent_featured_properties_widget', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'ere_agent_properties_widget', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'ere_properties_widget', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'ere_featured_properties_widget', $plugin_public, 'alter_rh_properties_query', 20, 1 );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'alter_rh_properties_archive_query', 20, 1 );
		$this->loader->add_action( 'template_include', $plugin_public, 'alter_single_property_template', 99, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Property_Permissions_For_Realhomes_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
