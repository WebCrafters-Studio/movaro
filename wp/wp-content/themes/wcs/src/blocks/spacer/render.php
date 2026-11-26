<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$desktop = get_field("desktop") ?? '';
$tablet = get_field("tablet") ?? '';
$mobile = get_field("mobile") ?? '';

?>

<div class="b-spacer" style="--desktop: <?= $desktop ?>px; --tablet: <?= $tablet ?>px; --mobile: <?= $mobile ?>px"></div>