<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcs_acf_json_save_point( $path ) {
	return get_theme_file_path( 'acf-json' );
}
add_filter( 'acf/settings/save_json', 'wcs_acf_json_save_point' );

function wcs_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = get_theme_file_path( 'acf-json' );
	return $paths;
}
add_filter( 'acf/settings/load_json', 'wcs_acf_json_load_point' );