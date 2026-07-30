<?php
/**
 * Runs when the plugin is deleted from the WordPress admin.
 * Removes all data the plugin created.
 *
 * @package ConsentBannerNest
 */

// If uninstall is not called from WordPress, bail.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'consent_banner_nest_settings' );
delete_option( 'consent_banner_nest_log' );

// Clean up on multisite installs as well.
if ( is_multisite() ) {
	$consent_banner_nest_site_ids = get_sites( array( 'fields' => 'ids' ) );
	foreach ( $consent_banner_nest_site_ids as $consent_banner_nest_site_id ) {
		switch_to_blog( $consent_banner_nest_site_id );
		delete_option( 'consent_banner_nest_settings' );
		delete_option( 'consent_banner_nest_log' );
		restore_current_blog();
	}
}
