<?php
$logo_footer = get_field("logo_footer", "options") ?? '';
$landing_page_link = get_field("landing_page_link", "options") ?? '';
$producer_logo = get_field("producer_logo", "options") ?? '';
$producer_link = get_field("producer_link", "options") ?? '';
$privacy_policy_link = get_field("privacy_policy_link") ?? '';
?>

<div class="c-footer__wrapper">
    <footer class="c-footer container">
        <div class="c-footer__top">
            <div class="c-footer__top-logo">
                <?php if ($landing_page_link): ?>
                    <a href="<?= esc_url($landing_page_link['url']) ?>">
                        <?php if ($logo_footer): ?>
                            <?= wp_get_attachment_image($logo_footer['ID'], 'small') ?>
                        <?php else: ?>
                            <span class="c-footer__logo-alt"><?= $landing_page_link['title'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>
            <nav class="c-footer__top-nav">
                <?= wp_nav_menu(['menu' => 'header-menu']) ?>
            </nav>
        </div>
        <div class="c-footer__bottom">
            <?php if ($privacy_policy_link): ?>
                <a href="<?= esc_url($privacy_policy_link['url']) ?>" class="c-footer__bottom-privacy-policy">
                    <?= esc_html($privacy_policy_link['title']) ?>
                </a>
            <?php endif; ?>

            <div class="c-footer__bottom-producer">
                <span><?= __("Realizacja", "movaro") ?></span>
                <?php if ($producer_link): ?>
                    <a href="<?= esc_url($producer_link['url']) ?>" class="c-footer__producer-link" target="_blank">
                        <?php if ($producer_link['title']): ?>
                            <?= wp_get_attachment_image($producer_logo['ID'], 'small') ?>
                        <?php else: ?>
                            <span class="c-footer__producer-alt"><?= $producer_link['title'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>

</html>