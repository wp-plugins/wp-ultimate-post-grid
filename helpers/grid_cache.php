<?php

class WPUPG_Grid_Cache {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'updated_post' ), 11, 2 );
        add_action( 'edited_terms', array( $this, 'updated_term' ), 10, 2 );
        add_action( 'admin_init', array( $this, 'regenerate_grids_check' ) );
    }

    public function updated_post( $id, $post )
    {
        $update_post_post_type = $post->post_type;

        if( $update_post_post_type == WPUPG_POST_TYPE )
        {
            $this->generate( $id );
        } else {
            $args = array(
                'post_type' => WPUPG_POST_TYPE,
                'post_status' => 'any',
                'posts_per_page' => -1,
                'nopaging' => true,
            );

            $query = new WP_Query( $args );
            $posts = $query->have_posts() ? $query->posts : array();

            foreach ( $posts as $grid_post )
            {
                $grid = new WPUPG_Grid( $grid_post );

                if( in_array( $update_post_post_type, $grid->post_types() ) ) {
                    $this->generate( $grid->ID() );
                }
            }
        }
    }

    public function updated_term( $term_id, $taxonomy )
    {
        $args = array(
            'post_type' => WPUPG_POST_TYPE,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'nopaging' => true,
        );

        $query = new WP_Query( $args );
        $posts = $query->have_posts() ? $query->posts : array();

        $grid_ids = array();
        foreach ( $posts as $post )
        {
            $grid = new WPUPG_Grid( $post );

            if( in_array( $taxonomy, $grid->filter_taxonomies() ) ) {
                $grid_ids[] = $grid->ID();
            }
        }

        if( count( $grid_ids ) > 0 ) {
            update_option( 'wpupg_regenerate_grids_check', $grid_ids );
        }
    }

    public function regenerate_grids_check()
    {
        $grid_ids = get_option( 'wpupg_regenerate_grids_check', false );
        if( $grid_ids ) {
            foreach( $grid_ids as $grid_id ) {
                $this->generate( $grid_id );
            }

            update_option( 'wpupg_regenerate_grids_check', false );
        }
    }

    public function generate( $grid_id )
    {
        $grid = new WPUPG_Grid( $grid_id );

        // Get Posts
        $args = array(
            'post_type' => $grid->post_types(),
            'post_status' => $grid->post_status(),
            'posts_per_page' => -1,
            'nopaging' => true,
            'fields' => 'ids',
        );

        // Images Only
        if( $grid->images_only() ) {
            $args['meta_query'] = array(
                array(
                    'key' => '_thumbnail_id',
                    'value' => '0',
                    'compare' => '>'
                ),
            );
        }

        $query = new WP_Query( $args );
        $posts = $query->have_posts() ? $query->posts : array();
        $post_ids = array_map( 'intval', $posts );

        $cache = array(
            'all' => $post_ids,
        );

        $taxonomies = $grid->filter_taxonomies();

        // Cache arrays
        $posts_per_term = array();
        $terms_per_post = array();
        $filter_terms = array();
        $filter_slugs = array();


        // Loop over all terms
        foreach( $post_ids as $post_id ) {
            if( !isset( $terms_per_post[$post_id] ) ) $terms_per_post[$post_id] = array();

            foreach( $taxonomies as $taxonomy ) {
                if( !isset( $posts_per_term[$taxonomy] ) ) $posts_per_term[$taxonomy] = array();

                $terms = wp_get_post_terms( $post_id, $taxonomy );

                $post_taxonomy_term_ids = array();

                foreach( $terms as $term ) {
                    // Posts per term
                    if( !isset( $posts_per_term[$taxonomy][$term->term_id] ) ) $posts_per_term[$taxonomy][$term->term_id] = array();
                    $posts_per_term[$taxonomy][$term->term_id][] = $post_id;

                    // Terms per post
                    $post_taxonomy_term_ids[] = $term->term_id;

                    // Filter terms
                    $filter_terms[$taxonomy . '-' . $term->term_id] = $term->name;
                    $filter_slugs[$taxonomy . '-' . $term->term_id] = $term->slug;
                }

                $terms_per_post[$post_id][$taxonomy] = $post_taxonomy_term_ids;
            }
        }

        $cache['taxonomies'] = $posts_per_term;
        $cache['terms'] = $terms_per_post;

        // Generate Filter
        $filter = '';

        if( count( $filter_terms ) > 0 ) {
            $filter .= '<div class="wpupg-filter-item wpupg-filter-isotope-term active" data-filter="*" data-slug="">' . __( 'All', 'wp-ultimate-post-grid' ) . '</div>';

            asort( $filter_terms );
            foreach( $filter_terms as $term_id => $term_name ) {
                $filter .= '<div class="wpupg-filter-item wpupg-filter-isotope-term" data-filter=".wpupg-tax-' . $term_id . '" data-slug="' . $filter_slugs[$term_id] . '">' . $term_name . '</div>';
            }
        }

        // Update Metadata
        update_post_meta( $grid_id, 'wpupg_posts', $cache );
        update_post_meta( $grid_id, 'wpupg_filter', $filter );
    }
}