<?php
/**
 * Admin: settings page, registration and asset loading.
 *
 * @package Konsentra
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Konsentra_Admin
 */
class Konsentra_Admin {

	/**
	 * Settings page hook suffix.
	 *
	 * @var string
	 */
	private $hook = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'plugin_action_links_' . KONSENTRA_BASENAME, array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 2 );
	}

	/**
	 * Add the options page under Settings.
	 *
	 * @return void
	 */
	public function add_menu() {
		$this->hook = add_options_page(
			__( 'Konsentra', 'konsentra' ),
			__( 'Konsentra', 'konsentra' ),
			'manage_options',
			'konsentra',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the single settings option and its sanitize callback.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'konsentra_group',
			KONSENTRA_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Konsentra_Settings', 'sanitize' ),
				'default'           => Konsentra_Settings::get_defaults(),
			)
		);
	}

	/**
	 * Enqueue admin styles only on our settings screen.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( $hook !== $this->hook ) {
			return;
		}

		wp_enqueue_style(
			'konsentra-admin',
			KONSENTRA_URL . 'admin/css/admin.css',
			array(),
			KONSENTRA_VERSION
		);

		wp_enqueue_script(
			'konsentra-admin',
			KONSENTRA_URL . 'admin/js/admin.js',
			array( 'wp-color-picker' ),
			KONSENTRA_VERSION,
			true
		);
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Add a quick "Settings" link on the plugins screen.
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function action_links( $links ) {
		$url  = admin_url( 'options-general.php?page=konsentra' );
		$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'konsentra' ) . '</a>';
		array_unshift( $links, $link );
		return $links;
	}

	/**
	 * Add support and author links under the plugin description.
	 *
	 * @param array  $links Existing row meta links.
	 * @param string $file  Plugin file being processed.
	 * @return array
	 */
	public function row_meta( $links, $file ) {
		if ( KONSENTRA_BASENAME !== $file ) {
			return $links;
		}

		$links[] = '<a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support on Ko-fi', 'konsentra' ) . '</a>';
		$links[] = '<a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Author', 'konsentra' ) . '</a>';

		return $links;
	}

	/**
	 * Render the settings page view.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings   = Konsentra::get_settings();
		$categories = Konsentra_Settings::get_categories();

		require KONSENTRA_PATH . 'admin/views/settings-page.php';
	}
}
