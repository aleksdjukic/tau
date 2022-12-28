<?php
if (!defined('ABSPATH')) {
	exit;
}
$hide_price_is_zero = 0 == $price && 1 == $hide_price_label ? false : true;
?>
<div class="cliparts-image-container">
	<div class="pdr-custom-image-container">
		<?php
		if ($hide_price_is_zero) {
			?>
			<a class="ui green label"><?php echo wp_kses_post(wc_price($price)); ?></a>
			<?php
		}
		?>
		<input data-price="<?php echo esc_attr($price); ?>"  type="file" id="pdr-image-file">

		<div class="pdf_preview">

		</div>
	</div>
</div>
