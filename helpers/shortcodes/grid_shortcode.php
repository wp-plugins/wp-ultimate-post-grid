<?php

class WPUPG_Grid_Shortcode {

    public function __construct()
    {
        add_shortcode( 'wpupg-grid', array( $this, 'shortcode' ) );

//        add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
    }

    public function shortcode( $options )
    {
        $output = '';

        $options = shortcode_atts( array(
            'id' => false
        ), $options );

        $slug = strtolower( trim( $options['id'] ) );

        if( $slug ) {
            $post = get_page_by_path( $slug, OBJECT, WPUPG_POST_TYPE );

            if( !is_null( $post ) ) {
                $grid = new WPUPG_Grid( $post );

                $output = '<div id="wpupg-grid-' . esc_attr( $slug ) . '" class="wpupg-grid">';
                $output .= $grid->draw_posts();
                $output .= '</div>';
            }
        }

        return $output;
    }

    public function tinymce_plugin( $plugin_array )
    {
        $plugin_array['wpupg_grid_shortcode'] = WPUltimatePostGrid::get()->coreUrl . '/js/tinymce_shortcode.js';
        return $plugin_array;
    }
}