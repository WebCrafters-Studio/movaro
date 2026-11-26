<?php
if (! defined('ABSPATH')) {
	exit;
}

$title = get_field("title") ?? '';
$photos = get_field("photos") ?? [];
?>

<div class="b-story__wrapper">
	<section class="b-story container">
		<?php if ($title): ?>
			<h2 class="b-story__title"><?= esc_html($title) ?></h2>
		<?php endif; ?>
		<div class="b-story__photos">
			<?php if ($photos): ?>
				<?php foreach ($photos as $photo): ?>
					<div class="b-story__photo">
						<?= wp_get_attachment_image($photo['ID'], 'large') ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
</div>