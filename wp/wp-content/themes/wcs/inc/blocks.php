<?php
/**
 * Register blocks.
 *
 * @package wcs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcs_register_blocks() {
	$blocks_directory = get_theme_file_path( 'build/blocks' );

	if ( file_exists( $blocks_directory ) ) {
		$block_json_files = glob( $blocks_directory . '/*/block.json' );
		foreach ( $block_json_files as $block_json_file ) {

			$block_json_file = str_replace( 'block.json', '', $block_json_file );
			register_block_type( $block_json_file );
		}
	}
}
add_action( 'init', 'wcs_register_blocks' );


function wcs_block_categories( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'wcs-theme',
				'title' => __( 'WCS Theme Blocks', 'wcs-theme' ),
			),
		)
	);
}
add_filter( 'block_categories_all', 'wcs_block_categories' );
