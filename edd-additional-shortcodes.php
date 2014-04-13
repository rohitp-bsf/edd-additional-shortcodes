<?php
/*
Plugin Name: Easy Digital Downloads - Additional Shortcodes
Plugin URI: http://filament-studios.com
Description: Adds additional shortcodes to EDD
Version: 1.1
Author: Chris Klosowski
Author URI: http://filament-studios.com
Text Domain: edd-asc-txt
*/

if ( !defined( 'EDD_VERSION' ) )
	return;

// Register shortcodes
add_shortcode( 'edd_cart_has_contents', 'edd_asc_cart_has_contents' );
function edd_asc_cart_has_contents( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_cart_has_contents' ) );

	if ( edd_get_cart_contents() )
		return edd_asc_maybe_do_shortcode( $content );
}

add_shortcode( 'edd_cart_is_empty', 'edd_asc_cart_is_empty' );
function edd_asc_cart_is_empty( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_asc_cart_is_empty' ) );

	if ( !edd_get_cart_contents() )
		return edd_asc_maybe_do_shortcode( $content );
}

add_shortcode( 'edd_user_has_purchases', 'edd_asc_user_has_purchases' );
function edd_asc_user_has_purchases( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_user_has_purchases' ) );

	$user_id = get_current_user_id();
	if ( edd_has_purchases( $user_id ) )
		return edd_asc_maybe_do_shortcode( $content );
}

add_shortcode( 'edd_user_has_no_purchases', 'edd_asc_user_has_no_purchases' );
function edd_asc_user_has_no_purchases( $attributes, $content = null ) {
	extract( shortcode_atts( array( 'loggedout' => 'true' ), $attributes, 'edd_user_has_no_purchases' ) );

	// If the user is logged out, and we aren't concerned with logged out users, don't show the content
	if ( $loggedout == 'false' && !is_user_logged_in() )
		return;

	$user_id = get_current_user_id();
	if ( !edd_has_purchases( $user_id ) )
		return edd_asc_maybe_do_shortcode( $content );
}

add_shortcode( 'edd_is_user_logged_in', 'edd_asc_is_user_logged_in' );
function edd_asc_is_user_logged_in( $attributes, $content = null ) {
	extract( shortcode_atts( array(), $attributes, 'edd_is_user_logged_in' ) );

	if ( !is_user_logged_in() )
		return;

	return edd_asc_maybe_do_shortcode( $content );
}

// Helper functions
function edd_asc_maybe_do_shortcode( $content )  {
	$do_shortcode = apply_filters( 'edd_asc_do_shortcode', true );

	if ( $do_shortcode )
		return do_shortcode( $content );

	return $content;
}
