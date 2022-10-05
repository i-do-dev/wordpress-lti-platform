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
        ?>
            <h4>Deep Linking</h4>
            <div style="width: 100%;">
                <label for="toolurl">Tool Url</label>
                <input type="text" id="lti_tool_url" name="toolurl" style="width: 100%;" />
            </div>
            <div id="preview_lit_connections" style="width: 100%;display: inline-block;margin-top: 10px;">
                <div class="preview button" href="#">Select Content<span class="screen-reader-text"> (opens in a new tab)</span></div>
            </div>
        <?php
    }
}
