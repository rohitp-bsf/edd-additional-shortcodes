<?php
/*
Plugin Name: Easy Digital Downloads - Additional Shortcodes
Plugin URI: http://filament-studios.com
Description: Adds additional shortcodes to EDD
Version: 1.0
Author: Chris Klosowski
Author URI: http://kungfugrep.com
Text Domain: edd-asc-txt
*/

if ( !defined( 'EDD_VERSION' ) )
	return;

// Register shortcodes
add_shortcode( 'edd_cart_has_contents', 'edd_asc_cart_has_contents' );
function edd_asc_cart_has_contents( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_cart_has_contents' ) );

	if ( edd_get_cart_contents() )
		return $content;
}

add_shortcode( 'edd_cart_is_empty', 'edd_asc_cart_is_empty' );
function edd_asc_cart_is_empty( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_cart_has_contents' ) );

	if ( !edd_get_cart_contents() )
		return $content;
}