<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles from the build directory.
 */
function wcs_enqueue_custom_script($dir_name, $filename)
{

	$build_dir = get_template_directory() . '/build/scripts/' . $dir_name . '/';
	$build_uri = get_template_directory_uri() . '/build/scripts/' . $dir_name . '/';

	$handle = 'wcs-script-' . $filename;
	$asset_path = $build_dir . $filename . '.asset.php';
	$deps = array();
	$ver = '1.0.0';

	if (file_exists($asset_path)) {
		$asset = require $asset_path;
		$deps = $asset['dependencies'];
		$ver = $asset['version'];
	}

	wp_enqueue_script(
		$handle,
		$build_uri . $filename . '.js',
		$deps,
		$ver,
		true
	);
}

function wcs_enqueue_custom_style($dir_name, $filename)
{
	$build_dir = get_template_directory() . '/build/styles/' . $dir_name . '/';
	$build_uri = get_template_directory_uri() . '/build/styles/' . $dir_name . '/';

	// $handle = 'wcs-style-' . $filename . '-rtl';
	
	$deps = array();
	$ver = '1.0.0';


	// $asset_path = $build_dir . $filename . '-rtl.asset.php';
	// if (file_exists($asset_path)) {
	// 	$asset = require $asset_path;
	// 	$deps = $asset['dependencies'];
	// 	$ver = $asset['version'];
	// }
	// wp_enqueue_style(
	// 	$handle,
	// 	$build_uri . $filename . '-rtl.css',
	// 	$deps,
	// 	$ver,
	// 	'all'
	// );
	
	$handle = 'wcs-style-' . $filename;
	$asset_path = $build_dir . $filename . '.asset.php';
	if (file_exists($asset_path)) {
		$asset = require $asset_path;
		$deps = $asset['dependencies'];
		$ver = $asset['version'];
	}

	wp_enqueue_style(
		$handle,
		$build_uri . $filename . '.css',
		$deps,
		$ver,
		'all'
	);
}

add_filter('wpcf7_autop_or_not', '__return_false');