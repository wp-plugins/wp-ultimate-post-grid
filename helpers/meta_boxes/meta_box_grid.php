<?php
// Grid should never be null. Construct just allows easy access to WPUPG_Grid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Grid(0);
?>

<table id="wpupg_form_grid" class="wpupg_form">
    <tr>
        <td><label for="wpupg_link_type"><?php _e( 'Links', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_link_type" id="wpupg_link_type" class="wpupg-select2">
                <?php
                $link_type_options = array(
                    '_self' => __( 'Open in same tab', 'wp-ultimate-post-grid' ),
                    '_blank' => __( 'Open in new tab', 'wp-ultimate-post-grid' ),
                    'none' => __( "Don't use links", 'wp-ultimate-post-grid' ),
                );

                foreach( $link_type_options as $link_type => $link_type_name ) {
                    $selected = $link_type == $grid->link_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $link_type ) . '"' . $selected . '>' . $link_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Options for links surrounding the grid items.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
    <tr class="wpupg_divider">
        <td><label for="wpupg_template"><?php _e( 'Template', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_template" id="wpupg_template" class="wpupg-select2">
                <?php
                $templates = WPUltimatePostGrid::addon( 'custom-templates' )->get_mapping();
                $templates = apply_filters( 'wpupg_meta_box_grid_templates', $templates );

                foreach ( $templates as $index => $template ) {
                    $selected = $index == $grid->template_id() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $index ) . '"' . $selected . '>' . $template . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Template to be used for grid items.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
</table>