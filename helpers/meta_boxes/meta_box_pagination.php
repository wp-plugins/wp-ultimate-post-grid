<?php
// Grid should never be null. Construct just allows easy access to WPUPG_Grid functions in IDE.
if( is_null( $grid ) ) $grid = new WPUPG_Grid(0);

$pagination = $grid->pagination();
?>
<table id="wpupg_form_pagination" class="wpupg_form">
    <tbody class="wpupg_pagination_none">
    <tr>
        <td><label for="wpupg_pagination_type"><?php _e( 'Type', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <select name="wpupg_pagination_type" id="wpupg_pagination_type" class="wpupg-select2">
                <?php
                $pagination_type_options = array(
                    'none' => __( 'No pagination (all posts visible at once)', 'wp-ultimate-post-grid' ),
                    'pages' => __( 'Divide posts in pages', 'wp-ultimate-post-grid' ),
                );

                foreach( $pagination_type_options as $pagination_type => $pagination_type_name ) {
                    $selected = $pagination_type == $grid->pagination_type() ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr( $pagination_type ) . '"' . $selected . '>' . $pagination_type_name . '</option>';
                }
                ?>
            </select>
        </td>
        <td><?php _e( 'Type of pagination to be used for this grid.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
    </tbody>
    <tbody class="wpupg_pagination_pages">
    <tr class="wpupg_divider">
        <td><label for="wpupg_pagination_pages_posts_per_page"><?php _e( 'Posts per page', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <div id="wpupg_pagination_pages_posts_per_page_slider"></div>
        </td>
        <td><input type="text" name="wpupg_pagination_pages_posts_per_page" id="wpupg_pagination_pages_posts_per_page" value="<?php echo $pagination['pages']['posts_per_page']; ?>" /><?php _e( 'posts', 'wp-ultimate-posts-grid' ); ?></td>
    </tr>
    </tbody>
</table>