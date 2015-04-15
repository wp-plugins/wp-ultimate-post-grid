<?php
// Grid should never be null. Construct just allows easy access to WPUPG_Grid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Grid(0);
?>
<div id="wpupg_no_taxonomies"><?php _e( 'There are no taxonomies associated with this post type', 'wp-ultimate-post-grid' ); ?></div>
<table id="wpupg_form_filter" class="wpupg_form">
    <tr class="wpupg_no_filter">
        <td><label for="wpupg_filter_type"><?php _e( 'Type', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_filter_type" id="wpupg_filter_type" class="wpupg-select2">
                <?php
                $filter_type_options = array(
                    'none' => __( 'No Filter', 'wp-ultimate-post-grid' ),
                    'isotope' => __( 'Isotope', 'wp-ultimate-post-grid' ),
                );

                foreach( $filter_type_options as $filter_type => $filter_type_name ) {
                    $selected = $filter_type == $grid->filter_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $filter_type ) . '"' . $selected . '>' . $filter_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Type of filter to be used for this grid.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_filter_taxonomy_post"><?php _e( 'Taxonomy', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <?php
            $post_types = get_post_types( '', 'objects' );

            unset( $post_types[WPUPG_POST_TYPE] );
            unset( $post_types['revision'] );
            unset( $post_types['nav_menu_item'] );

            foreach( $post_types as $post_type => $options ) {
                $taxonomies = get_object_taxonomies( $post_type, 'objects' );

                if( count( $taxonomies ) > 0 ) {
                    echo '<div id="wpupg_filter_taxonomy_' . $post_type . '_container" class="wpupg_filter_taxonomy_container">';
                    echo '<select name="wpupg_filter_taxonomy_' . $post_type . '" id="wpupg_filter_taxonomy_' . $post_type . '" class="wpupg-select2">';

                    foreach( $taxonomies as $taxonomy => $tax_options ) {
                        $selected = in_array( $taxonomy, $grid->filter_taxonomies() ) ? ' selected="selected"' : '';
                        echo '<option value="' . esc_attr( $taxonomy ) . '"' . $selected . '>' . $tax_options->labels->name . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }
            }
            ?>
        </td>
        <td><?php _e( 'Taxonomy to be used for filtering the grid.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
</table>