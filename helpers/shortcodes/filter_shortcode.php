<?php

class WPUPG_Filter_Shortcode {

    public function __construct()
    {
        add_shortcode( 'wpupg-filter', array( $this, 'shortcode' ) );
    }

    public function shortcode( $options )
    {
        $output = '';

        $options = shortcode_atts( array(
            'id' => false,
        ), $options );

        $slug = strtolower( trim( $options['id'] ) );

        if( $slug ) {
            $post = get_page_by_path( $slug, OBJECT, WPUPG_POST_TYPE );

            if( !is_null( $post ) ) {
                $grid = new WPUPG_Grid( $post );

                $filter_type = $grid->filter_type();

                if( $filter_type !== 'none' ) {
                    $output = '<div id="wpupg-grid-' . esc_attr( $slug ) . '-filter" class="wpupg-filter wpupg-filter-' . $filter_type . '">';
                    $output .= $grid->filter();
                    $output .= '</div>';
                }
            }
        }

        return $output;
    }
}