<?php
/**
 * Plugin Name:       Konsentra - Cookie Consent Banner
 * Plugin URI:        https://github.com/gunjanjaswal/Konsentra
 * Description:       A lightweight, privacy-first GDPR cookie consent banner with category controls and automatic script blocking until the visitor opts in.
 * Version:           1.0.3
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Gunjan Jaswal
 * Author URI:        https://www.gunjanjaswal.me
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       konsentra
 * Domain Path:       /languages
 *
 * @package Konsentra
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Current plugin version.
 */
define( 'KONSENTRA_VERSION', '1.0.3' );

/**
 * Plugin file, directory path and URL helpers.
 */
define( 'KONSENTRA_FILE', __FILE__ );
define( 'KONSENTRA_PATH', plugin_dir_path( __FILE__ ) );
define( 'KONSENTRA_URL', plugin_dir_url( __FILE__ ) );
define( 'KONSENTRA_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Option key used to store all plugin settings.
 */
define( 'KONSENTRA_OPTION', 'konsentra_settings' );

/**
 * Cookie name the front end reads and writes.
 */
define( 'KONSENTRA_COOKIE', 'konsentra_consent' );

// Load core classes.
require_once KONSENTRA_PATH . 'includes/class-konsentra.php';
require_once KONSENTRA_PATH . 'includes/class-konsentra-settings.php';
require_once KONSENTRA_PATH . 'includes/class-konsentra-frontend.php';

if ( is_admin() ) {
	require_once KONSENTRA_PATH . 'admin/class-konsentra-admin.php';
}

/**
 * Boot the plugin.
 *
 * @return Konsentra
 */
function konsentra() {
	return Konsentra::instance();
}

// Get things going.
konsentra();

/**
 * Activation: seed default settings if none exist yet.
 *
 * @return void
 */
function konsentra_activate() {
	if ( false === get_option( KONSENTRA_OPTION ) ) {
		add_option( KONSENTRA_OPTION, Konsentra_Settings::get_defaults() );
	}
}
register_activation_hook( __FILE__, 'konsentra_activate' );
