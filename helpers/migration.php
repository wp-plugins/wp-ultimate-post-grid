<?php

class WPUPG_Migration {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'migrate_if_needed' ) );
    }

    public function migrate_if_needed()
    {
        // Get current migrated to version
        $migrate_version = get_option( 'wpupg_migrate_version', false );

        if( !$migrate_version ) {
            $notices = false;
            $migrate_version = '0.0.1';
        } else {
            $notices = true;
        }

        $migrate_special = '';
        if( isset( $_GET['wpupg_migrate'] ) ) {
            $migrate_special = $_GET['wpupg_migrate'];
        }

        //if( $migrate_version < '0.0.1' ) require_once( WPUltimatePostGrid::get()->coreDir . '/helpers/migration/0_0_1_example_migration.php');

        // Each version update once
        if( $migrate_version < WPUPG_VERSION ) {
            WPUltimatePostGrid::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

            update_option( 'wpurp_migrate_version', WPUPG_VERSION );
        }
    }
}