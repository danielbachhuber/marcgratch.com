<?php
/*
Plugin Name: Disable Gravity Forms Field Validation
*/


class GWUnrequire {

    var $_args = null;

    public function __construct( $args = array() ) {

        $this->_args = wp_parse_args( $args, array(
            'admins_only' => true,
            'require_query_param' => true
        ) );

        add_filter( 'gform_pre_validation', array( $this, 'unrequire_fields' ) );

    }

    function unrequire_fields( $form ) {

        if( $this->_args['admins_only'] && ! current_user_can( 'activate_plugins' ) )
            return $form;

        if( $this->_args['require_query_param'] && ! isset( $_GET['gwunrequire'] ) )
            return $form;

        foreach( $form['fields'] as &$field ) {
            $field['isRequired'] = false;
        }

        return $form;
    }

}

new GWUnrequire( array(
    'admins_only' => true,
    'require_query_param' => false
) );

