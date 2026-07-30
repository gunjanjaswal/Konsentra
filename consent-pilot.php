<?php
/**
 * Plugin Name:       Consent Pilot
 * Plugin URI:        https://www.gunjanjaswal.me
 * Description:       A lightweight, privacy-first GDPR cookie consent banner with category controls and automatic script blocking until the visitor opts in.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Gunjan Jaswal
 * Author URI:        https://www.gunjanjaswal.me
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       consent-pilot
 * Domain Path:       /languages
 *
 * @package ConsentPilot
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Current plugin version.
 */
define( 'CONSENT_PILOT_VERSION', '1.0.0' );

/**
 * Plugin file, directory path and URL helpers.
 */
define( 'CONSENT_PILOT_FILE', __FILE__ );
define( 'CONSENT_PILOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONSENT_PILOT_URL', plugin_dir_url( __FILE__ ) );
define( 'CONSENT_PILOT_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Option key used to store all plugin settings.
 */
define( 'CONSENT_PILOT_OPTION', 'consent_pilot_settings' );

/**
 * Cookie name the front end reads and writes.
 */
define( 'CONSENT_PILOT_COOKIE', 'consent_pilot_consent' );

// Load core classes.
require_once CONSENT_PILOT_PATH . 'includes/class-consent-pilot.php';
require_once CONSENT_PILOT_PATH . 'includes/class-consent-pilot-settings.php';
require_once CONSENT_PILOT_PATH . 'includes/class-consent-pilot-frontend.php';

if ( is_admin() ) {
	require_once CONSENT_PILOT_PATH . 'admin/class-consent-pilot-admin.php';
}

/**
 * Boot the plugin.
 *
 * @return Consent_Pilot
 */
function consent_pilot() {
	return Consent_Pilot::instance();
}

// Get things going.
consent_pilot();

/**
 * Activation: seed default settings if none exist yet.
 *
 * @return void
 */
function consent_pilot_activate() {
	if ( false === get_option( CONSENT_PILOT_OPTION ) ) {
		add_option( CONSENT_PILOT_OPTION, Consent_Pilot_Settings::get_defaults() );
	}
}
register_activation_hook( __FILE__, 'consent_pilot_activate' );
