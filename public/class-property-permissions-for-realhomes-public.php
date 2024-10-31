<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hassan-raza.com
 * @since      1.0.0
 *
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Property_Permissions_For_Realhomes
 * @subpackage Property_Permissions_For_Realhomes/public
 * @author     Hassan Raza <hassanazmy@gmail.com>
 */
class Property_Permissions_For_Realhomes_Public {

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
		 * defined in Property_Permissions_For_Realhomes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Property_Permissions_For_Realhomes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/property-permissions-for-realhomes-public.css', array(), $this->version, 'all' );

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
		 * defined in Property_Permissions_For_Realhomes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Property_Permissions_For_Realhomes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/property-permissions-for-realhomes-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Register properties alter function
	 *
	 * @param $search_args
	 * @since    1.0.0
	 */
	public function alter_rh_properties_query( $search_args ) {

		$meta_query = $search_args[ 'meta_query' ];
		$meta_query['relation'] = 'AND';

		if( is_user_logged_in() ){

			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$current_user_roles = $current_user->roles;

			if ( ! in_array( "administrator", $current_user_roles ) ) {

				$meta_query[] = array(
					'relation' => 'AND',
					array(
						'relation' => 'OR',
						array(
							'key'     => 'ppfr_property_access_state',
							'value'   => 'private',
							'compare' => '!='
						),
						array(
							'key'     => 'ppfr_property_users_' . $current_user_id,
							'value'   => 1,
							'compare' => '='
						)
					)
				);
			}

		} else {

			$meta_query[] = array(
				'key'     => 'ppfr_property_access_state',
				'value'   => 'private',
				'compare' => '!=',
			);

		}

		$search_args[ 'meta_query' ] = $meta_query;

		return $search_args;

	}

	/**
	 * Register property archive query alter
	 *
	 * @param $query array
	 * @return array
	 * @since    1.0.0
	 */
	public function alter_rh_properties_archive_query( $query ) {

		if( $query->is_tax() ) {
			if ( is_user_logged_in() ) {

				$current_user       = wp_get_current_user();
				$current_user_id    = $current_user->ID;
				$current_user_roles = $current_user->roles;

				if ( ! in_array( "administrator", $current_user_roles ) ) {

					$query->set( 'meta_query', array(
						'relation' => 'AND',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'ppfr_property_access_state',
								'value'   => 'private',
								'compare' => '!='
							),
							array(
								'key'     => 'ppfr_property_users_' . $current_user_id,
								'value'   => 1,
								'compare' => '='
							)
						)
					) );

				}

			} else {

				$query->set( 'meta_query', array(
					'key'     => 'ppfr_property_access_state',
					'value'   => 'private',
					'compare' => '!=',
				) );

			}
		}

		return $query;

	}


	/**
	 * Register property single redirect in case user isn't allowed
	 *
	 * @param   array   $template
	 * @return  string
	 * @since   1.0.0
	 */
	public function alter_single_property_template( $template ) {

		if( is_singular( 'property' ) ){
			global $post;
			$current_property_id = $post->ID;
			$property_state = get_post_meta( $current_property_id, 'ppfr_property_access_state', true );

			if ( is_user_logged_in() ) {

				$current_user       = wp_get_current_user();
				$current_user_id    = $current_user->ID;
				$current_user_roles = $current_user->roles;
				$user_allowed = get_post_meta( $current_property_id, 'ppfr_property_users_' . $current_user_id, true );

				if ( ! in_array( "administrator", $current_user_roles ) ) {
					if( $property_state == 'private' && ! $user_allowed ){
						wp_redirect( home_url('/') );
					}
				}

			} else {
				if( $property_state == 'private' ){
					wp_redirect( home_url('/') );
				}
			}

		}

		return $template;

	}

}
