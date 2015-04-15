<?php
// Grid should never be null. Construct just allows easy access to WPUPG_Grid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Grid(0);
?>

<table id="wpupg_form_grid" class="wpupg_form">
    <tr>
        <td><label for="wpupg_template"><?php _e( 'Template', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_template" id="wpupg_template" class="wpupg-select2">
                <?php
                $templates = WPUltimatePostGrid::addon( 'custom-templates' )->get_mapping();

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