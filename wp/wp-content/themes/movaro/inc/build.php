<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function enqueue_build_assets()
{
    $build_dir = get_template_directory() . '/build';
    $build_uri = get_template_directory_uri() . '/build';
    
    if (!is_dir($build_dir)) {
        return;
    }

    $scripts = glob($build_dir . '/*.js');
    if ($scripts) {
        foreach ($scripts as $script_path) {
            $filename = basename($script_path);
            $handle = 'script-' . pathinfo($filename, PATHINFO_FILENAME);
            $asset_path = $build_dir . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.asset.php';
            $deps = array();
            $ver = '1.0.0';

            if (file_exists($asset_path)) {
                $asset = require $asset_path;
                $deps = $asset['dependencies'];
                $ver = $asset['version'];
            }

            wp_enqueue_script(
                $handle,
                $build_uri . '/' . $filename,
                $deps,
                $ver,
                true
            );
        }
    }

    $styles = glob($build_dir . '/*.css');
    if ($styles) {
        foreach ($styles as $style_path) {
            $filename = basename($style_path);
            $handle = 'style-' . pathinfo($filename, PATHINFO_FILENAME);
            $asset_path = $build_dir . '/styles/' . pathinfo($filename, PATHINFO_FILENAME) . '.asset.php';
            $deps = array();
            $ver = '1.0.0';

            if (file_exists($asset_path)) {
                $asset = require $asset_path;
                $deps = $asset['dependencies']; // Usually empty for CSS but good to check
                $ver = $asset['version'];
            }

            wp_enqueue_style(
                $handle,
                $build_uri . '/' . $filename,
                $deps,
                $ver
            );
        }
    }
}

add_action('wp_enqueue_scripts', 'enqueue_build_assets');
