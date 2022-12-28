<?php
if (!defined('ABSPATH')) {
	exit;
}
if (pdr_check_is_array($cliparts)) {
	foreach ($cliparts as $clipart) {
		$get_price = $clipart->get_price();
		$hide_price_is_zero = 0 == $get_price && 1 == $hide_price_label ? false : true;
		?>
		<div class="cliparts-thumbnail">
			<div class="ui image">
				<?php
				if ($hide_price_is_zero) {
					?>
					<a class="ui green right ribbon label"><?php echo wp_kses_post($clipart->get_price() ? wc_price(esc_attr($clipart->get_price())) : wc_price(0) ); ?></a>
				<?php } ?>
				<img class="clipart-thumbnail-image" src="<?php echo esc_url($clipart->get_thumbnail_url()); ?>" data-thumbnail_id ="<?php echo esc_attr($clipart->get_thumbnail_id()); ?>" data-price="<?php echo esc_attr($clipart->get_price()); ?>"/>
			</div>
		</div>
		<?php
	}
}
