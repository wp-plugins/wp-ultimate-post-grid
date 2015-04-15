<?php

class WPUPG_Meta_Box {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_meta_box' ));
    }

    public function add_meta_box()
    {
        add_meta_box(
            'wpupg_meta_box_shortcode',
            __( 'Shortcode', 'wp-ultimate-post-grid' ),
            array( $this, 'meta_box_shortcode' ),
            WPUPG_POST_TYPE,
            'side',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_data_source',
            __( 'Data Source', 'wp-ultimate-post-grid' ),
            array( $this, 'meta_box_data_source' ),
            WPUPG_POST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_filter',
            __( 'Filter', 'wp-ultimate-post-grid' ),
            array( $this, 'meta_box_filter' ),
            WPUPG_POST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_isotope_filter_style',
            __( 'Filter Style', 'wp-ultimate-post-grid' ),
            array( $this, 'meta_box_isotope_filter_style' ),
            WPUPG_POST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'wpupg_meta_box_grid',
            __( 'Grid', 'wp-ultimate-post-grid' ),
            array( $this, 'meta_box_grid' ),
            WPUPG_POST_TYPE,
            'normal',
            'high'
        );
    }

    public function meta_box_shortcode( $post )
    {
        $grid = new WPUPG_Grid( $post );
        include( WPUltimatePostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_shortcode.php' );
    }

    public function meta_box_data_source( $post )
    {
        $grid = new WPUPG_Grid( $post );
        include( WPUltimatePostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_data_source.php' );
    }

    public function meta_box_filter( $post )
    {
        $grid = new WPUPG_Grid( $post );
        include( WPUltimatePostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_filter.php' );
    }

    public function meta_box_isotope_filter_style( $post )
    {
        $grid = new WPUPG_Grid( $post );
        include( WPUltimatePostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_isotope_filter_style.php' );
    }

    public function meta_box_grid( $post )
    {
        $grid = new WPUPG_Grid( $post );
        include( WPUltimatePostGrid::get()->coreDir . '/helpers/meta_boxes/meta_box_grid.php' );
    }
}