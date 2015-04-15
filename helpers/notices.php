<?php

class WPUPG_Notices {

    public function __construct()
    {
        add_action( 'admin_notices',    array( $this, 'admin_notices' ) );
    }

    public function admin_notices()
    {
        if( $notices = get_option( 'wpupg_deferred_admin_notices' ) ) {
            foreach( $notices as $notice ) {
                echo '<div class="updated"><p>'.$notice.'</p></div>';
            }

            delete_option('wpupg_deferred_admin_notices');
        }
    }

    public function add_admin_notice( $notice )
    {
        $notices = get_option( 'wpupg_deferred_admin_notices', array() );
        $notices[] = $notice;
        update_option( 'wpupg_deferred_admin_notices', $notices );
    }
}