<?php
if (! defined('ABSPATH')) {
	exit;
}

$logo = get_field("logo", "options") ?? '';
$partner_logo = get_field("partner_logo", "options");
$partner_link = get_field("partner_link", "options") ?? '';
$landing_page_link = get_field("landing_page_link", "options") ?? '';

$photo = get_field("photo") ?? '';
$title = get_field("title") ?? '';
$subtitle = get_field("subtitle") ?? '';

$logo_description = get_field("logo_description") ?? '';

$list = get_field("list") ?? [];
$description = get_field("description") ?? '';
?>

<section class="b-introduction container">
	<div class="b-introduction__photo">
		<?php if ($photo): ?>
			<?= wp_get_attachment_image($photo['ID'], 'large') ?>
		<?php endif; ?>
	</div>
	<div class="b-introduction__content">
		<header class="b-introduction__content-header">
			<?php if ($subtitle): ?>
				<h3><?= esc_html($subtitle) ?></h3>
			<?php endif; ?>
			<?php if ($title): ?>
				<h2><?= esc_html($title) ?></h2>
			<?php endif; ?>
		</header>
		<div class="b-introduction__content-showcase">
			<div class="b-introduction__content-logos">
				<?php if ($landing_page_link): ?>
					<a href="<?= esc_url($landing_page_link['url']) ?>">
						<?php if ($logo): ?>
							<?= wp_get_attachment_image($logo['ID'], 'small') ?>
						<?php else: ?>
							<span><?= $landing_page_link['title'] ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" viewBox="0 0 7 7" fill="none">
					<path d="M0.137654 1.01853L1.00481 0.151381L6.63442 5.781L5.76727 6.64815L0.137654 1.01853ZM5.89115 -2.63481e-05L6.78583 0.894655L0.894693 6.78579L1.12899e-05 5.89111L5.89115 -2.63481e-05Z" fill="#FFC107" />
				</svg>
				<?php if ($partner_link): ?>
					<a href="<?= esc_url($partner_link['url']) ?>" target="_blank">
						<?php if ($partner_logo): ?>
							<?= wp_get_attachment_image($partner_logo['ID'], 'small') ?>
						<?php else: ?>
							<span>Meble Bory</span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
			<?php if ($logo_description): ?>
				<div class="b-introduction__content-desc">
					<p><?= esc_html($logo_description) ?></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="b-introduction__content-list-container">
			<ul class="b-introduction__content-list">
				<?php if ($list): ?>
					<?php foreach ($list as $item): ?>
						<li class="b-introduction__container-list-item">
							<div class="b-introduction__content-list-wrapper">
								<h5><?= htmlspecialchars($item['title'] ?? '') ?></h5>
								<span><?= htmlspecialchars($item['description'] ?? '') ?></span>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
		<?php if ($description): ?>
			<footer class="b-introduction__content-footer">
				<p><?= esc_html($description) ?></p>
			</footer>
		<?php endif; ?>
	</div>
</section>