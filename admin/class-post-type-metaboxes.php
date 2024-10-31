<?php
/**
 * Student custom post type class.
 *
 * Defines the Student post type.
 *
 * @package    WP_Academia
 * @subpackage WP_Academia/admin
 *
 */

class PPFR_Post_Type_Meta {

    /**
     * Register meta boxes related to recipes post type
     *
     * @param   array   $metabox
     * @since   0.1.0
     * @return  array   $metabox
     */
    public function register_meta_boxes ( $metabox ){

	    foreach ( $metabox as $k => $meta ) {
		    if ( isset( $meta[ 'id' ] ) &&
		         $meta[ 'id' ] == 'property-meta-box'
		    ) {
			    $tabs = $meta[ 'tabs' ];
			    $fields = $meta[ 'fields' ];

			    $tabs['ppfr-permissions'] =  array(
					    'label' => esc_html__( 'Permissions', 'property-permissions-for-realhomes' ),
					    'icon'  => 'dashicons-unlock'
			    );

			    $fields[] = array(
				    'id'      => "ppfr_property_access_state",
				    'name'    => esc_html__( 'Property Access State', 'property-permissions-for-realhomes' ),
				    'type'    => 'radio',
				    'columns' => 12,
				    'tab'     => 'ppfr-permissions',
				    'options' => array(
					    'public'    =>  esc_html__( 'Public', 'property-permissions-for-realhomes' ),
					    'private'   =>  esc_html__( 'Private', 'property-permissions-for-realhomes' )
				    ),
				    'std' => 'public',
				    'select_all_none' => true
			    );

			    $fields[] = array(
				    'type' => 'heading',
				    'name' => 'Allowed Users',
				    'desc' => 'Logged in users who are allowed to see this property.',
				    'tab'     => 'ppfr-permissions',
				    'columns' => 12
			    );

			    $user_list = get_users( array( 'role__not_in' => 'Administrator' ) );
			    if( is_array( $user_list ) ) {
				    foreach ( $user_list as $user ) {
					    $fields[] = array(
						    'name'      => esc_attr( $user->user_nicename ),
						    'id'        => 'ppfr_property_users_' . $user->ID,
						    'type'      => 'switch',
						    'style'     => 'rounded',
						    'on_label'  => 'Yes',
						    'off_label' => 'No',
						    'columns'   => 4,
						    'tab'       => 'ppfr-permissions'
					    );
				    }
			    }


			    $metabox[ $k ][ 'tabs' ] = $tabs;
			    $metabox[ $k ][ 'fields' ] = $fields;
		    }
	    }

        return $metabox;

    }
}