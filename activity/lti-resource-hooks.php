<?php

use ceLTIc\LTI;

class LtiResourceHooks
{
    private static $_instance = null;

    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function add_post_meta_box(){
        add_meta_box(
            'lti-resource-options-class',      // Unique ID
            esc_html__( 'LTI Resource Options', 'lti-resource-options' ),    // Title
            array(self::get_instance(), 'render_lti_resource_meta_box'),   // Callback function
            'lti-resource',         // Admin page (or post type)
            'side',         // Context
            'default'         // Priority
        );
    }

    public static function render_lti_resource_meta_box(){
        echo 'Render Custome Fields here ................';
    }
}
