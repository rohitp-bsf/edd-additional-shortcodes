<?php
/*
 * Plugin Name: Easy Digital Downloads - Additional Shortcodes
 * Plugin URI: https://easydigitaldownloads.com/downloads/edd-additional-shortcodes/
 * Description: Adds additional shortcodes to Easy Digital Downloads.
 * Version: 1.4.1
 * Author: Sandhills Development, LLC
 * Author URI: https://sandhillsdev.com
 * Text Domain: edd-asc-txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class EDD_Additional_Shortcodes {

	/**
	 * @var EDD_Additional_Shortcodes|null The single instance
	 */
	private static ?EDD_Additional_Shortcodes $instance = null;

	public string $plugin_dir;
	public string $plugin_url;
	public string $version = '1.4.1';

	public EDD_Additional_Shortcodes_Core $shortcodes;
	public array $integrations = [];

	/**
	 * Private constructor to enforce singleton.
	 */
	private function __construct() {
		if ( ! defined( 'EDD_VERSION' ) ) {
			return;
		}
	}

	/**
	 * Get active instance
	 *
	 * @since 1.0
	 */
	public static function instance(): ?EDD_Additional_Shortcodes {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->setup_properties();
			self::$instance->includes();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Setup properties for the class
	 *
	 * @since 1.4
	 */
	private function setup_properties(): void {
		$this->plugin_dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Include the necessary files
	 *
	 * @since 1.4
	 */
	private function includes(): void {
		require_once $this->plugin_dir . 'includes/shortcodes.php';
		require_once $this->plugin_dir . 'includes/backwards-compatibility.php';

		if ( class_exists( 'EDD_Software_Licensing' ) ) {
			require_once $this->plugin_dir . 'includes/integrations/software-licensing.php';
		}
	}

	/**
	 * Initialize shortcodes and integrations
	 */
	private function init(): void {
		$this->shortcodes = new EDD_Additional_Shortcodes_Core();

		if ( class_exists( 'EDD_Software_Licensing' ) ) {
			$this->integrations['software_licensing'] = new EDD_Additional_Shortcodes_SL();
		}
	}

	/**
	 * Maybe execute the shortcode as a shortcode, or simply return the content.
	 *
	 * @since 1.4
	 */
	public function maybe_do_shortcode( string $content ): string {
		$do_shortcode = apply_filters( 'edd_asc_do_shortcode', true );
		return $do_shortcode ? do_shortcode( $content ) : $content;
	}
}

/**
 * Load the class
 *
 * @since 1.4
 */
function edd_additional_shortcodes(): ?EDD_Additional_Shortcodes {
	return EDD_Additional_Shortcodes::instance();
}
add_action( 'plugins_loaded', 'edd_additional_shortcodes', 100 );
