<?php

function load_more_shop_callback()
{
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (! wp_verify_nonce($nonce, 'load_more_shop_nonce')) {
        wp_send_json_error(array('message' => 'Nonce check failed.'), 403);
    }

    $offset   = isset($_POST['offset']) ? max(0, intval($_POST['offset'])) : 0;
    $per_page = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 6;

    $results = wc_get_products([
        'status'   => 'publish',
        'limit'    => $per_page,
        'offset'   => $offset,
        'orderby'  => 'menu_order title',
        'order'    => 'ASC',
        'paginate' => true,
    ]);

    $wc_products = $results->products;
    $total = (int) $results->total;
    $remaining   = ($offset + count($wc_products)) < $total;

    ob_start();
    foreach ($wc_products as $wc_product) :
        $regular_price = (float) $wc_product->get_regular_price();
        $sale_price    = (float) $wc_product->get_sale_price();
        $is_on_sale    = $wc_product->is_on_sale() && $sale_price > 0 && $regular_price > 0;

        $discount_rate = $is_on_sale
            ? (($regular_price - $sale_price) / $regular_price)
            : 0;

        $product = array(
            'discount_rate'  => $discount_rate,
            'thumbnail_id'   => (int) $wc_product->get_image_id(),
            'title'          => $wc_product->get_name(),
            'description'    => wpautop($wc_product->get_description()),
            'height'         => $wc_product->get_height(),
            'width'          => $wc_product->get_width(),
            'discount'       => $is_on_sale,
            'discount_price' => $sale_price,
            'price'          => $regular_price ?: (float) $wc_product->get_price(),
            'link'           => $wc_product->get_permalink(),
        );
?>
        <div class="b-shop__item">
            <?php if ($product['discount']) : ?>
                <span class="b-shop__item-discount">
                    <?= __("Dziś", 'movaro') ?> -<?= number_format($product['discount_rate'] * 100, 0) ?>%
                </span>
            <?php endif; ?>
            <div class="b-shop__item-thumbnail">
                <?php if ($product['thumbnail_id']) : ?>
                    <?= wp_get_attachment_image($product['thumbnail_id'], 'medium') ?>
                <?php else : ?>
                    <span>Thumbnail</span>
                <?php endif; ?>
            </div>
            <div class="b-shop__item-content">
                <h3><?= esc_html($product['title'] ?? '') ?></h3>
                <?php if ($product['description']) : ?>
                    <div class="b-shop__item-content-list">
                        <?= $product['description'] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="b-shop__item-dimensions">
                <div class="b-shop__item-dimensions-item">
                    <h5><?= esc_html($product['height'] ?? '') ?><?= __("cm", 'movaro') ?></h5>
                    <span><?= __("Wysokość", "movaro") ?></span>
                </div>
                <div class="b-shop__item-dimensions-item">
                    <h5><?= esc_html($product['width'] ?? '') ?><?= __("cm", 'movaro') ?></h5>
                    <span><?= __("Szerokość", "movaro") ?></span>
                </div>
            </div>
            <div class="b-shop__item-price">
                <?php if ($product['discount']) : ?>
                    <span class="b-shop__item-price-discount">
                        <span class="b-shop__item-price-discount main"><?= number_format($product['discount_price'], 2, ',', '') ?><?= __("zł", 'movaro') ?></span>
                        <span class="b-shop__item-price-discount secondary"><?= number_format($product['price'], 2, ',', ''); ?><?= __("zł", 'movaro') ?></span>
                    </span>
                <?php else : ?>
                    <span class="b-shop__item-price-regular"><?= number_format($product['price'], 2, ',', ''); ?><?= __("zł", 'movaro') ?></span>
                <?php endif; ?>
            </div>
            <a href="<?= esc_url($product['link']) ?>" class="b-shop__item-button button--full">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M15.5145 5.63618C15.3263 4.78193 14.6528 4.11143 13.7985 3.92693C13.3433 3.82868 12.8873 3.74468 12.4305 3.67493C12.4118 3.40718 12.39 3.14318 12.366 2.88893C12.267 1.84643 11.4728 1.01318 10.434 0.862426C9.40278 0.713176 8.59878 0.713176 7.56753 0.862426C6.52878 1.01318 5.73378 1.84643 5.63478 2.88968C5.61078 3.14393 5.58903 3.40793 5.57028 3.67568C5.11353 3.74618 4.65753 3.82943 4.20228 3.92768C3.34728 4.11218 2.67378 4.78343 2.48628 5.63768C1.79103 8.79518 1.79103 11.8657 2.48628 15.0239C2.67378 15.8782 3.34728 16.5494 4.20228 16.7339C5.79453 17.0774 7.39728 17.2499 9.00003 17.2499C10.6028 17.2499 12.2063 17.0782 13.7978 16.7339C14.652 16.5494 15.3255 15.8782 15.5138 15.0239C16.209 11.8664 16.209 8.79593 15.5138 5.63768L15.5145 5.63618ZM7.12803 3.03068C7.16103 2.68493 7.43628 2.39693 7.78278 2.34668C8.67078 2.21768 9.33078 2.21768 10.2188 2.34668C10.5653 2.39693 10.8405 2.68493 10.8735 3.03068C10.8878 3.18068 10.9013 3.33593 10.9133 3.49343C9.63903 3.38468 8.36253 3.38468 7.08828 3.49343C7.10103 3.33593 7.11378 3.18068 7.12803 3.03068ZM14.0498 14.6999C13.9883 14.9789 13.7603 15.2062 13.482 15.2662C10.509 15.9082 7.49253 15.9082 4.51878 15.2662C4.24053 15.2062 4.01253 14.9789 3.95103 14.7007C3.30378 11.7599 3.30378 8.90018 3.95103 5.95943C4.01253 5.68118 4.24053 5.45393 4.51878 5.39393C4.84203 5.32418 5.16528 5.26268 5.48853 5.20793C5.46078 5.96543 5.45553 6.64268 5.47128 7.07018C5.48703 7.48418 5.82453 7.80518 6.24903 7.79168C6.66303 7.77593 6.98553 7.42868 6.97053 7.01393C6.95403 6.56768 6.96378 5.82068 6.99828 5.00768C7.66503 4.94393 8.33253 4.91168 9.00078 4.91168C9.66903 4.91168 10.3365 4.94393 11.0033 5.00768C11.0378 5.82068 11.0475 6.56768 11.031 7.01393C11.0153 7.42793 11.3385 7.77593 11.7525 7.79168C11.7623 7.79168 11.7713 7.79168 11.781 7.79168C12.1823 7.79168 12.5153 7.47368 12.5295 7.06943C12.5453 6.64193 12.54 5.96393 12.5123 5.20718C12.8363 5.26193 13.1595 5.32343 13.4828 5.39318C13.761 5.45318 13.989 5.68043 14.0505 5.95868C14.6978 8.89943 14.697 11.7592 14.0498 14.6999Z" fill="#04071E" />
                </svg>
                <span><?= __("Kup teraz", "movaro") ?></span>
            </a>
        </div>
<?php endforeach;

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'remaining' => $remaining,
        'total'     => $total,
    ]);
}

add_action('wp_enqueue_scripts', function () {
    wp_localize_script('app', 'shop_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('load_more_shop_nonce'),
    ]);
});
