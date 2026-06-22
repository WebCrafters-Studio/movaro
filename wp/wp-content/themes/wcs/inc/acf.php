<?php
/**
 * Advanced Custom Fields Configuration
 *
 * @package wcs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set ACF JSON save point
 */
function wcs_acf_json_save_point( $path ) {
	return get_theme_file_path( 'acf-json' );
}
add_filter( 'acf/settings/save_json', 'wcs_acf_json_save_point' );

/**
 * Set ACF JSON load point
 */
function wcs_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = get_theme_file_path( 'acf-json' );
	return $paths;
}
add_filter( 'acf/settings/load_json', 'wcs_acf_json_load_point' );
