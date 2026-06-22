<?php
/**
 * WCS Theme functions and definitions
 *
 * @package wcs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include theme files
require_once get_theme_file_path( 'inc/performance.php' );
require_once get_theme_file_path( 'inc/theme-support.php' );
require_once get_theme_file_path( 'inc/blocks.php' );
require_once get_theme_file_path( 'inc/acf.php' );
require_once get_theme_file_path( 'inc/build.php' );
require_once get_theme_file_path( 'inc/cpt.php' );
require_once get_theme_file_path( 'inc/menus.php' );
require_once get_theme_file_path( 'inc/text.php' );
require_once get_theme_file_path( 'inc/pagination.php' );
require_once get_theme_file_path( 'inc/woocommerce.php' );
require_once get_theme_file_path( 'inc/custom-enqueue.php' );

add_action( 'init', function() {
    set_error_handler( function ($errno, $errstr, $errfile, $errline) {
        if (
            strpos( $errstr, '_load_textdomain_just_in_time' ) !== false
        ) {
            return true;
        }

        return false;
    } );
} );

function wp_admin_bar_current_template($wp_admin_bar)
{
	if (is_admin() || !is_user_logged_in()) {
		return;
	}

	global $template;

	if (empty($template)) {
		return;
	}

	$wp_admin_bar->add_node(array(
		'id' => 'current-template',
		'title' => 'Template: ' . basename($template).'          Post type: '.get_post_type(),
		'meta' => array(
			'title' => $template,
		),
	));
}

add_action('admin_bar_menu', 'wp_admin_bar_current_template', 100);
if(!function_exists('dump')){
	function dump($variable){
		echo '<pre>';
		print_r($variable);
		echo '</pre>';
	}

}