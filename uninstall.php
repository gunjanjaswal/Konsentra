<?php
/**
 * Runs when the plugin is deleted from the WordPress admin.
 * Removes all data the plugin created.
 *
 * @package Konsentra
 */

// If uninstall is not called from WordPress, bail.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'konsentra_settings' );
delete_option( 'konsentra_log' );

// Clean up on multisite installs as well.
if ( is_multisite() ) {
	$konsentra_site_ids = get_sites( array( 'fields' => 'ids' ) );
	foreach ( $konsentra_site_ids as $konsentra_site_id ) {
		switch_to_blog( $konsentra_site_id );
		delete_option( 'konsentra_settings' );
		delete_option( 'konsentra_log' );
		restore_current_blog();
	}
}
