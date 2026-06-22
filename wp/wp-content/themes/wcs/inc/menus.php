<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function wcs_register_menus() {
    register_nav_menus( array(
        'menu-header' => __( 'Header Menu', 'wcs' ),
        'menu-footer' => __( 'Footer Menu', 'wcs' ),
    ) );
}
add_action( 'init', 'wcs_register_menus' );

function get_menu_array($menu_location){
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations[$menu_location] ?? null;

    if (!$menu_id) {
        return [];
    }

    $menu_items = wp_get_nav_menu_items($menu_id);
    if (!$menu_items) {
        return [];
    }

    $menu_array = [];
    foreach ($menu_items as $item) {
        $menu_array[$item->ID] = [
            'ID' => $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'parent' => (int) $item->menu_item_parent,
            'target' => $item->target,
            'children' => [],
            'icon' => $item->menu_icon,
            'submenu_title' => $item->menu_title
        ];
    }

    // Build the tree structure
    foreach ($menu_array as $id => &$item) {
        if ($item['parent'] && isset($menu_array[$item['parent']])) {
            $menu_array[$item['parent']]['children'][] = &$item;
        }
    }

    // Keep only top-level items
    return array_filter($menu_array, function ($item) {
        return $item['parent'] === 0;
    });
}



