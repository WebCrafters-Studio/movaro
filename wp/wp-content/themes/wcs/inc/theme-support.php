<?php
/**
 * Theme Support Features
 *
 * @package wcs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup theme support features
 */
function wcs_theme_support() {
	// HTML5 support for core features
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Custom logo
	add_theme_support( 'custom-logo', array(
		'height' => 100,
		'width' => 400,
		'flex-height' => true,
		'flex-width' => true,
	) );

	// Title tag
	add_theme_support( 'title-tag' );

	// Automatic feed links
	add_theme_support( 'automatic-feed-links' );

	// Responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Editor styles
	add_theme_support( 'editor-styles' );

	// Align wide blocks
	add_theme_support( 'align-wide' );

	// Custom line height
	add_theme_support( 'custom-line-height' );

	// Custom spacing
	add_theme_support( 'custom-spacing' );

	// Custom units
	add_theme_support( 'custom-units' );

	// Appearance tools (border, padding, etc.)
	add_theme_support( 'appearance-tools' );

	// // WooCommerce support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'wcs_theme_support' );
