<?php

class WPUPG_Grid {

    private $post;
    private $meta;
    private $fields = array(
        'wpupg_images_only',
        'wpupg_filter_type',
        'wpupg_post_types',
        'wpupg_order_by',
        'wpupg_order',
        'wpupg_template',
    );

    // Filter style fields with defaults
    private $style_fields = array(
        'isotope' => array(
            'background_color'          => '#2E5077',
            'background_hover_color'    => '#1C3148',
            'text_color'                => '#FFFFFF',
            'text_hover_color'          => '#FFFFFF',
            'border_color'              => '#1C3148',
            'border_hover_color'        => '#1C3148',
            'border_width'              => '1',
            'margin_vertical'           => '5',
            'margin_horizontal'         => '5',
            'padding_vertical'          => '5',
            'padding_horizontal'        => '10',
        )
    );

    public function __construct( $post )
    {
        // Get associated post
        if( is_object( $post ) && $post instanceof WP_Post ) {
            $this->post = $post;
        } else if( is_numeric( $post ) ) {
            $this->post = get_post( $post );
        } else {
            throw new InvalidArgumentException( 'Grids can only be instantiated with a Post object or Post ID.' );
        }

        // Get metadata
        $this->meta = get_post_custom( $this->post->ID );
    }

    public function is_present( $field )
    {
        switch( $field ) {
            default:
                $val = $this->meta( $field );
                return isset( $val ) && trim( $val ) != '';
        }
    }

    public function meta( $field )
    {
        if( isset( $this->meta[$field] ) ) {
            return $this->meta[$field][0];
        }

        return null;
    }

    public function fields()
    {
        return $this->fields;
    }

    public function style_fields()
    {
        return $this->style_fields;
    }

    /**
     * Grid fields
     */

    public function filter()
    {
        return $this->meta( 'wpupg_filter' );
    }

    public function filter_taxonomies()
    {
        $filter_taxonomies = unserialize( $this->meta( 'wpupg_filter_taxonomies' ) );
        return is_array( $filter_taxonomies ) ? $filter_taxonomies : array();
    }

    public function filter_style()
    {
        $filter_style = unserialize( $this->meta( 'wpupg_filter_style' ) );
        $filter_style = is_array( $filter_style ) ? $filter_style : array();

        // Set defaults
        foreach( $this->style_fields() as $style => $fields ) {
            $filter_style[$style] = isset( $filter_style[$style] ) ? $filter_style[$style] + $fields : $fields;
        }

        return $filter_style;
    }

    public function filter_type()
    {
        return $this->meta( 'wpupg_filter_type' );
    }

    public function ID()
    {
        return $this->post->ID;
    }

    public function images_only()
    {
        return $this->meta( 'wpupg_images_only' );
    }

    public function limit_initial() // TODO
    {
        return -1;
    }

    public function limit_load() // TODO
    {
        return 5;
    }

    public function order()
    {
        return $this->meta( 'wpupg_order' );
    }

    public function order_by()
    {
        return $this->meta( 'wpupg_order_by' );
    }

    public function posts()
    {
        $posts = unserialize( $this->meta( 'wpupg_posts' ) );
        return is_array( $posts ) ? $posts : array();
    }

    public function post() // TODO
    {
        return $this->post;
    }

    public function post_status() // TODO
    {
        return 'publish';
    }

    public function post_types()
    {
        $post_types = unserialize( $this->meta( 'wpupg_post_types' ) );
        return is_array( $post_types ) ? $post_types : array();
    }

    public function template()
    {
        return WPUltimatePostGrid::addon( 'custom-templates' )->get_template( $this->template_id() );
    }

    public function template_id()
    {
        return $this->meta( 'wpupg_template' );
    }

    public function title()
    {
        return $this->post->post_title;
    }

    /**
     * Helper functions
     */

    public function get_posts( $page = 0 )
    {
        $grid_posts = $this->posts();
        $post_ids = $grid_posts['all'];

        $posts_per_page = $page == 0 ? $this->limit_initial() : $this->limit_load();

        $offset = 0;
        if( $page > 0 ) {
            $offset = $this->limit_initial() + ( $page - 1 ) * $posts_per_page;
        }

        $args = array(
            'post_type' => 'any',
            'orderby' => $this->order_by(),
            'order' => $this->order(),
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
            'post__in' => $post_ids,
        );

        if( $posts_per_page == -1 ) {
            $args['nopaging'] = true;
        }

        $query = new WP_Query( $args );
        $posts = $query->have_posts() ? $query->posts : array();
        return $posts;
    }

    public function draw_posts()
    {
        $output = '';
        $grid_posts = $this->posts();
        $posts = $this->get_posts();

        foreach( $posts as $post ) {
            $classes = array(
                'wpupg-item',
                'wpupg-post-' . $post->ID,
                'wpupg-type-' . $post->post_type,
            );

            if( isset( $grid_posts['terms'][$post->ID] ) ) {
                foreach( $grid_posts['terms'][$post->ID] as $taxonomy => $terms ) {
                    foreach( $terms as $term ) {
                        $classes[] = 'wpupg-tax-' . $taxonomy . '-' . $term;
                    }
                }
            }

            $template = apply_filters( 'wpupg_output_grid_template', $this->template() );

            $output .= '<a href="' . get_post_permalink( $post->ID ) . '">';
            $output .= $template->output_string( $post, $classes );
            $output .= '</a>';
        }

        return $output;
    }
}