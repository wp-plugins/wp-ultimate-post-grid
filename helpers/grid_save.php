<?php

class WPUPG_Grid_Save {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    public function save( $post_id, $post )
    {
        if( $post->post_type == WPUPG_POST_TYPE )
        {
            if ( !isset( $_POST['wpupg_nonce'] ) || !wp_verify_nonce( $_POST['wpupg_nonce'], 'grid' ) ) {
                return;
            }

            $grid = new WPUPG_Grid( $post_id );

            // Basic metadata
            $fields = $grid->fields();

            foreach ( $fields as $field )
            {
                $old = get_post_meta( $post_id, $field, true );
                $new = isset( $_POST[$field] ) ? $_POST[$field] : null;

                // Field specific adjustments
                if( isset( $new ) && $field == 'wpupg_post_types' ) {
                    $new = array( $new );
                }

                // Update or delete meta data if changed
                if( isset( $new ) && $new != $old ) {
                    update_post_meta( $post_id, $field, $new );
                } elseif ( $new == '' && $old ) {
                    delete_post_meta( $post_id, $field, $old );
                }
            }

            // Filter Taxonomies
            $post_type = $_POST['wpupg_post_types'];

            if( isset( $_POST['wpupg_filter_taxonomy_' . $post_type] ) ) {
                $filter_taxonomies = array( $_POST['wpupg_filter_taxonomy_' . $post_type] );
                update_post_meta( $post_id, 'wpupg_filter_taxonomies', $filter_taxonomies );
            }

            // Filter style metadata
            $styles = $grid->style_fields();
            $filter_style = array();

            foreach( $styles as $style => $fields) {
                $filter_style[$style] = array();

                foreach( $fields as $field => $default ) {
                    $field_name  = 'wpupg_' . $style . '_filter_style_' . $field;
                    if( isset( $_POST[$field_name] ) ) {
                        $filter_style[$style][$field] = $_POST[$field_name];
                    }
                }
            }

            update_post_meta( $post_id, 'wpupg_filter_style', $filter_style );

            // Cache gets automatically generated in WPUPG_Grid_Cache
        }
    }
}