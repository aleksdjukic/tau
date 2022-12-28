<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( pdr_check_is_array( $design_templates ) ) {
	foreach ( $design_templates as $design_template ) {
		$price              = $design_template->get_price();
		$hide_price_is_zero = 0 == $price && 1 == $hide_price_label ? false : true;
		?>
		<div class="pdr-template">
			<div class="ui image">
				<?php if ( $hide_price_is_zero ) { ?>
					<a class="ui green right ribbon label"><?php echo wp_kses_post( $design_template->get_price() ? wc_price( $design_template->get_price() ) : wc_price( 0 )  ); ?></a>
				<?php } ?>
				<img class="pdr-template-image" src="<?php echo do_shortcode( $design_template->get_content() ); ?>" data-id="<?php echo esc_attr( $design_template->get_id() ); ?>" data-price="<?php echo esc_attr( $design_template->get_price() ); ?>" data-has-canvas="<?php echo '' === $design_template->read_canvas_from_file() ? 'no' : 'yes'; ?>"/>
			</div>
		</div>
		<?php
	}
}

