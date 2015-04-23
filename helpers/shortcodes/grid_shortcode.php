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

                $output = '<div id="wpupg-grid-' . esc_attr( $slug ) . '" class="wpupg-grid" data-grid="' . esc_attr( $slug ) . '">';
                $output .= $grid->draw_posts();
                $output .= '</div>';

                if( $grid->pagination_type() !== 'none' ) {
                    $pagination_type = $grid->pagination_type();
                    $pagination_style = $grid->pagination_style();

                    $style_data = ' data-margin-vertical="' . $pagination_style['margin_vertical'] . '"';
                    $style_data .= ' data-margin-horizontal="' . $pagination_style['margin_horizontal'] . '"';
                    $style_data .= ' data-padding-vertical="' . $pagination_style['padding_vertical'] . '"';
                    $style_data .= ' data-padding-horizontal="' . $pagination_style['padding_horizontal'] . '"';
                    $style_data .= ' data-border-width="' . $pagination_style['border_width'] . '"';

                    $style_data .= ' data-background-color="' . $pagination_style['background_color'] . '"';
                    $style_data .= ' data-text-color="' . $pagination_style['text_color'] . '"';
                    $style_data .= ' data-border-color="' . $pagination_style['border_color'] . '"';

                    $style_data .= ' data-active-background-color="' . $pagination_style['background_active_color'] . '"';
                    $style_data .= ' data-active-text-color="' . $pagination_style['text_active_color'] . '"';
                    $style_data .= ' data-active-border-color="' . $pagination_style['border_active_color'] . '"';

                    $style_data .= ' data-hover-background-color="' . $pagination_style['background_hover_color'] . '"';
                    $style_data .= ' data-hover-text-color="' . $pagination_style['text_hover_color'] . '"';
                    $style_data .= ' data-hover-border-color="' . $pagination_style['border_hover_color'] . '"';
                    
                    $output .= '<div id="wpupg-grid-' . esc_attr( $slug ) . '-pagination" class="wpupg-pagination wpupg-pagination-' . $pagination_type . '" style="text-align: ' . $pagination_style['alignment'] . ';" data-grid="' . esc_attr( $slug ) . '"' . $style_data . '>';
                    $output .= $grid->draw_pagination();
                    $output .= '</div>';
                }
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