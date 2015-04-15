<?php

class WPUPG_Activate {

    public function __construct()
    {
        register_activation_hook( WPUltimatePostGrid::get()->pluginFile, array( $this, 'activate_plugin' ) );
    }

    public function activate_plugin()
    {
    }
}