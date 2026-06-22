<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcs_numeric_posts_nav() {
  
    if( is_singular() )
        return;
  
    global $wp_query;
  
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
  
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
  
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
  
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
  
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
  
    echo '<div class="navigation"><ul>' . "\n";
  
    /** Previous Post Link */
    if ( get_previous_posts_link() ) {
        $prev_url = get_previous_posts_page_link();
        printf( 
            '<li><a href="%s"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.25 7.5001C11.247 7.17129 11.1145 6.85692 10.8813 6.6251L8.2 3.9376C8.0829 3.8212 7.92449 3.75586 7.75938 3.75586C7.59426 3.75586 7.43585 3.8212 7.31875 3.9376C7.26017 3.99571 7.21367 4.06483 7.18194 4.14099C7.15021 4.21716 7.13388 4.29885 7.13388 4.38135C7.13388 4.46386 7.15021 4.54555 7.18194 4.62172C7.21367 4.69788 7.26017 4.767 7.31875 4.8251L9.375 6.8751L3.125 6.8751C2.95924 6.8751 2.80027 6.94095 2.68306 7.05816C2.56585 7.17537 2.5 7.33434 2.5 7.5001C2.5 7.66586 2.56585 7.82484 2.68306 7.94205C2.80027 8.05926 2.95924 8.1251 3.125 8.1251H9.375L7.31875 10.1814C7.20106 10.2982 7.13461 10.457 7.13403 10.6229C7.13344 10.7887 7.19876 10.948 7.31563 11.0657C7.43249 11.1834 7.59131 11.2499 7.75717 11.2505C7.92302 11.251 8.08231 11.1857 8.2 11.0689L10.8813 8.38135C11.116 8.14801 11.2486 7.83109 11.25 7.5001Z" fill="var(--main)" style="transform: scale(-1, 1); transform-origin: center;" /></svg></a></li>' . "\n", 
            esc_url($prev_url) 
        );
    }
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
  
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
  
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
  
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
  
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
  
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
  
    /** Next Post Link */
    if ( get_next_posts_link() ) {
        $next_url = get_next_posts_page_link();
        printf( 
            '<li><a href="%s"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.25 7.5001C11.247 7.17129 11.1145 6.85692 10.8813 6.6251L8.2 3.9376C8.0829 3.8212 7.92449 3.75586 7.75938 3.75586C7.59426 3.75586 7.43585 3.8212 7.31875 3.9376C7.26017 3.99571 7.21367 4.06483 7.18194 4.14099C7.15021 4.21716 7.13388 4.29885 7.13388 4.38135C7.13388 4.46386 7.15021 4.54555 7.18194 4.62172C7.21367 4.69788 7.26017 4.767 7.31875 4.8251L9.375 6.8751L3.125 6.8751C2.95924 6.8751 2.80027 6.94095 2.68306 7.05816C2.56585 7.17537 2.5 7.33434 2.5 7.5001C2.5 7.66586 2.56585 7.82484 2.68306 7.94205C2.80027 8.05926 2.95924 8.1251 3.125 8.1251H9.375L7.31875 10.1814C7.20106 10.2982 7.13461 10.457 7.13403 10.6229C7.13344 10.7887 7.19876 10.948 7.31563 11.0657C7.43249 11.1834 7.59131 11.2499 7.75717 11.2505C7.92302 11.251 8.08231 11.1857 8.2 11.0689L10.8813 8.38135C11.116 8.14801 11.2486 7.83109 11.25 7.5001Z" fill="var(--main)"/></svg></a></li>' . "\n", 
            esc_url($next_url) 
        );
    }
    echo '</ul></div>' . "\n";
  
}

?>