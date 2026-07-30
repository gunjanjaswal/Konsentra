<?php
/**
 * Admin: settings page, registration and asset loading.
 *
 * @package ConsentBannerNest
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Consent_Banner_Nest_Admin
 */
class Consent_Banner_Nest_Admin {

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
		add_filter( 'plugin_action_links_' . CONSENT_BANNER_NEST_BASENAME, array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 2 );
	}

	/**
	 * Add the options page under Settings.
	 *
	 * @return void
	 */
	public function add_menu() {
		$this->hook = add_options_page(
			__( 'Consent Banner Nest', 'consent-banner-nest' ),
			__( 'Consent Banner Nest', 'consent-banner-nest' ),
			'manage_options',
			'consent-banner-nest',
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
			'consent_banner_nest_group',
			CONSENT_BANNER_NEST_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Consent_Banner_Nest_Settings', 'sanitize' ),
				'default'           => Consent_Banner_Nest_Settings::get_defaults(),
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
			'consent-banner-nest-admin',
			CONSENT_BANNER_NEST_URL . 'admin/css/admin.css',
			array(),
			CONSENT_BANNER_NEST_VERSION
		);

		wp_enqueue_script(
			'consent-banner-nest-admin',
			CONSENT_BANNER_NEST_URL . 'admin/js/admin.js',
			array( 'wp-color-picker' ),
			CONSENT_BANNER_NEST_VERSION,
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
		$url  = admin_url( 'options-general.php?page=consent-banner-nest' );
		$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'consent-banner-nest' ) . '</a>';
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
		if ( CONSENT_BANNER_NEST_BASENAME !== $file ) {
			return $links;
		}

		$links[] = '<a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support on Ko-fi', 'consent-banner-nest' ) . '</a>';
		$links[] = '<a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Author', 'consent-banner-nest' ) . '</a>';

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

		$settings   = Consent_Banner_Nest::get_settings();
		$categories = Consent_Banner_Nest_Settings::get_categories();

		require CONSENT_BANNER_NEST_PATH . 'admin/views/settings-page.php';
	}
}
