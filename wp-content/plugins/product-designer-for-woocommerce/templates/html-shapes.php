<?php
if (!defined('ABSPATH')) {
	exit;
}

if (pdr_check_is_array($shapes)) {
	foreach ($shapes as $shape) {
		$price = $shape->get_price();
		$hide_price_is_zero = 0 == $price && 1 == $hide_price_label ? false : true;
		?>
		<div class="shape-thumbnail">
			<div class="ui image">
				<?php if ($hide_price_is_zero) { ?>
					<a class="ui green right ribbon label"><?php echo wp_kses_post($shape->get_price() ? wc_price(esc_attr($shape->get_price())) : wc_price(0) ); ?></a>
				<?php } ?>
				<div class="pdr-shape" data-price="<?php echo esc_attr($shape->get_price()); ?>">
					<?php echo do_shortcode($shape->get_content()); ?>
				</div>
			</div>
		</div>
		<?php
	}
}
