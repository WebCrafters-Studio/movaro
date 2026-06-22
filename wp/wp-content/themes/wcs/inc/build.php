<?php
/**
 * Build Assets Enqueue
 *
 * @package wcs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles from the build directory.
 */
function wcs_enqueue_build_assets() {
	$build_dir = get_template_directory() . '/build';
	$build_uri = get_template_directory_uri() . '/build';

	// Enqueue Scripts
		if ( is_dir( $build_dir . '/scripts' ) ) {
			$scripts = glob( $build_dir . '/scripts/*.js' );
			if ( $scripts ) {
				foreach ( $scripts as $script_path ) {
					$filename = basename( $script_path );
					$handle = 'wcs-script-' . pathinfo( $filename, PATHINFO_FILENAME );
					$asset_path = $build_dir . '/scripts/' . pathinfo( $filename, PATHINFO_FILENAME ) . '.asset.php';
					$deps = array();
					$ver = '1.0.0';

					if ( file_exists( $asset_path ) ) {
						$asset = require $asset_path;
						$deps = $asset['dependencies'];
						$ver = $asset['version'];
					}

					wp_enqueue_script(
						$handle,
						$build_uri . '/scripts/' . $filename,
						$deps,
						$ver,
						true
					);
				}
			}
		}

	// Enqueue Styles
	if ( is_dir( $build_dir . '/styles' ) ) {
		$styles = glob( $build_dir . '/styles/*.css' );
		if ( $styles ) {
			foreach ( $styles as $style_path ) {
				$filename = basename( $style_path );
				$handle = 'wcs-style-' . pathinfo( $filename, PATHINFO_FILENAME );
				$asset_path = $build_dir . '/styles/' . pathinfo( $filename, PATHINFO_FILENAME ) . '.asset.php';
				$deps = array();
				$ver = '1.0.0';

				if ( file_exists( $asset_path ) ) {
					$asset = require $asset_path;
					$deps = $asset['dependencies']; // Usually empty for CSS but good to check
					$ver = $asset['version'];
				}

				wp_enqueue_style(
					$handle,
					$build_uri . '/styles/' . $filename,
					$deps,
					$ver
				);
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wcs_enqueue_build_assets' );
