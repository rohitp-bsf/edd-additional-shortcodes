<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EDD_Additional_Shortcodes_SL {

	public function __construct() {
		add_shortcode( 'edd_has_active_license', array( $this, 'has_active_license' ) );
	}

	function has_active_license( $attributes, $content = null ) {
		extract( shortcode_atts( array(), $attributes, 'edd_has_active_license' ) );

		$has_active_license = $this->has_active_license_check();
		if ( $has_active_license ) {
			return edd_additional_shortcodes()->maybe_do_shortcode( $content );
		}
	}

	private function has_active_license_check() {
		$license_keys = edd_software_licensing()->get_license_keys_of_user();
		foreach ( $license_keys as $license ) {

			$status = edd_software_licensing()->get_license_status( $license->ID );

			if ( 'expired' !== $status ) {
				return true;
			}

		}

		return false;
	}

}