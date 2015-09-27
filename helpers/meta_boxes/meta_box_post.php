<?php
$premium_only = WPUltimatePostGrid::is_premium_active() ? '' : ' (' . __( 'Premium only', 'wp-ultimate-post-grid' ) . ')';
?>

<input type="hidden" name="wpupg_nonce" value="<?php echo wp_create_nonce( 'grid' ); ?>" />
<table id="wpupg_form_post" class="wpupg_form">
    <tr>
        <td><label for="wpupg_custom_link"><?php _e( 'Custom Link', 'wp-ultimate-post-grid' ); ?></label></td>
        <td>
            <input type="text" name="wpupg_custom_link" id="wpupg_custom_link" value="<?php echo esc_attr( get_post_meta( $post->ID, 'wpupg_custom_link', true ) ); ?>"/>
        </td>
        <td><?php _e( 'Override the default link for this post.', 'wp-ultimate-post-grid' ); ?></td>
    </tr>
</table>