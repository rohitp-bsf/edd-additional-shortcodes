<?php
/*
 * Plugin Name: Easy Digital Downloads - Additional Shortcodes
 * Plugin URI:  https://easydigitaldownloads.com/downloads/edd-additional-shortcodes/
 * Description: Adds additional shortcodes to Easy Digital Downloads.
 * Version:     1.4.1
 * Author:      Sandhills Development, LLC
 * Author URI:  https://sandhillsdev.com
 * Text Domain: edd-asc-txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants.
 */
define( 'EDD_ASC_VERSION',   '1.4.1' );
define( 'EDD_ASC_FILE',      __FILE__ );
define( 'EDD_ASC_BASENAME',  plugin_basename( EDD_ASC_FILE ) );
define( 'EDD_ASC_DIR',       trailingslashit( plugin_dir_path( EDD_ASC_FILE ) ) );
define( 'EDD_ASC_URL',       trailingslashit( plugin_dir_url( EDD_ASC_FILE ) ) );

/**
 * Main plugin class (singleton).
 */
final class EDD_Additional_Shortcodes {

	/** @var EDD_Additional_Shortcodes|null */
	private static $instance = null;

	/** @var string */
	public $version = EDD_ASC_VERSION;

	/** @var string */
	public $plugin_dir = EDD_ASC_DIR;

	/** @var string */
	public $plugin_url = EDD_ASC_URL;

	/** @var EDD_Additional_Shortcodes_Core */
	public $shortcodes;

	/** @var array */
	public $integrations = array();

	/**
	 * Get the singleton instance.
	 *
	 * @return EDD_Additional_Shortcodes
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->load_textdomain();
			self::$instance->includes();
			self::$instance->boot();
		}

		return self::$instance;
	}
	

	/**
	 * Private constructor to enforce singleton.
	 */
	private function __construct() {}

	/**
	 * Load plugin textdomain.
	 */
	private function load_textdomain() {
		add_action(
			'init',
			function () {
				load_plugin_textdomain( 'edd-asc-txt', false, dirname( EDD_ASC_BASENAME ) . '/languages' );
			}
		);
	}

	/**
	 * Include required files.
	 */
	private function includes() {
		// Core shortcodes and BC layer.
		require_once EDD_ASC_DIR . 'includes/shortcodes.php';
		require_once EDD_ASC_DIR . 'includes/backwards-compatibility.php';

		// Conditional integrations.
		if ( class_exists( 'EDD_Software_Licensing' ) ) {
			require_once EDD_ASC_DIR . 'includes/integrations/software-licensing.php';
		}
	}

	/**
	 * Initialize runtime objects.
	 */
	private function boot() {
		$this->shortcodes = new EDD_Additional_Shortcodes_Core();

		if ( class_exists( 'EDD_Software_Licensing' ) && class_exists( 'EDD_Additional_Shortcodes_SL' ) ) {
			$this->integrations['software_licensing'] = new EDD_Additional_Shortcodes_SL();
		}
	}

	/**
	 * Maybe execute the shortcode as a shortcode, or simply return the content.
	 *
	 * @param string $content Content to process.
	 * @return string
	 */
	public function maybe_do_shortcode( $content ) {
		$do_shortcode = apply_filters( 'edd_asc_do_shortcode', true );
		return $do_shortcode ? do_shortcode( $content ) : $content;
	}
}

/**
 * Bootstrap the plugin once EDD is available.
 */
add_action( 'plugins_loaded', function () {
	if ( defined( 'EDD_VERSION' ) ) {
		EDD_Additional_Shortcodes::instance();
		return;
	}

	// Show an admin notice if EDD is missing.
	add_action( 'admin_notices', function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-error">
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: Easy Digital Downloads */
						__( 'Easy Digital Downloads - Additional Shortcodes requires %s to be installed and active.', 'edd-asc-txt' ),
						'<strong>Easy Digital Downloads</strong>'
					)
				);
				?>
			</p>
		</div>
		<?php
	} );
}, 100 );

/**
 * Helper accessor for the singleton instance.
 *
 * @return EDD_Additional_Shortcodes|null
 */
function edd_additional_shortcodes() {
	if ( class_exists( 'EDD_Additional_Shortcodes' ) ) {
		return EDD_Additional_Shortcodes::instance();
	}
	return null;
}
