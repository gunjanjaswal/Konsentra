<?php
/**
 * Plugin Name:       Consent Banner Nest
 * Plugin URI:        https://www.gunjanjaswal.me
 * Description:       A lightweight, privacy-first GDPR cookie consent banner with category controls and automatic script blocking until the visitor opts in.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Gunjan Jaswal
 * Author URI:        https://www.gunjanjaswal.me
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       consent-banner-nest
 * Domain Path:       /languages
 *
 * @package ConsentBannerNest
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Current plugin version.
 */
define( 'CONSENT_BANNER_NEST_VERSION', '1.0.0' );

/**
 * Plugin file, directory path and URL helpers.
 */
define( 'CONSENT_BANNER_NEST_FILE', __FILE__ );
define( 'CONSENT_BANNER_NEST_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONSENT_BANNER_NEST_URL', plugin_dir_url( __FILE__ ) );
define( 'CONSENT_BANNER_NEST_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Option key used to store all plugin settings.
 */
define( 'CONSENT_BANNER_NEST_OPTION', 'consent_banner_nest_settings' );

/**
 * Cookie name the front end reads and writes.
 */
define( 'CONSENT_BANNER_NEST_COOKIE', 'consent_banner_nest_consent' );

// Load core classes.
require_once CONSENT_BANNER_NEST_PATH . 'includes/class-consent-banner-nest.php';
require_once CONSENT_BANNER_NEST_PATH . 'includes/class-consent-banner-nest-settings.php';
require_once CONSENT_BANNER_NEST_PATH . 'includes/class-consent-banner-nest-frontend.php';

if ( is_admin() ) {
	require_once CONSENT_BANNER_NEST_PATH . 'admin/class-consent-banner-nest-admin.php';
}

/**
 * Boot the plugin.
 *
 * @return Consent_Banner_Nest
 */
function consent_banner_nest() {
	return Consent_Banner_Nest::instance();
}

// Get things going.
consent_banner_nest();

/**
 * Activation: seed default settings if none exist yet.
 *
 * @return void
 */
function consent_banner_nest_activate() {
	if ( false === get_option( CONSENT_BANNER_NEST_OPTION ) ) {
		add_option( CONSENT_BANNER_NEST_OPTION, Consent_Banner_Nest_Settings::get_defaults() );
	}
}
register_activation_hook( __FILE__, 'consent_banner_nest_activate' );
