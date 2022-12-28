<?php
if (!defined('ABSPATH')) {
	exit;
}
if ($get_product) {

	if (pdr_show_product_selection_button()) :
		?>
		<button class="ui button pdr-popup-product-base-btn primary segment"><?php echo esc_html(pdr_get_designer_choose_product_label()); ?></button>
	<?php endif; ?>

	<form class="ui form pdr-product-details-form">
		<div class="ui error message"></div>
		<div class="field">
			<input type="hidden" id="pdr-file-product-name" name="pdr-file-product-name" value="<?php echo esc_html(strtolower(str_replace(' ', '-', $product_name))); ?>"/>
			<h3>
				<?php
				echo esc_html($product_name);
				$hide_price_is_zero = 0 == $get_price && 1 == $hide_price_label ? false : true;
				if ($price_html && $hide_price_is_zero) {
					?>
					<a class="ui large green tag label">
						<?php echo wp_kses_post($price_html); ?>
					</a> 
				<?php } ?>
			</h3>
			<?php if (pdr_show_product_description()) : ?>
				<p class="pdr-product-description"><?php echo wp_kses_post($description); ?></p>
			<?php endif; ?>
		</div>
		<div class="ui divider"></div>
		<?php
		if (is_array($attributes) && !empty($attributes)) {
			foreach ($attributes as $each_attribute => $attribute_data) {
				$is_base_image_background = ( 'background' == get_post_meta($pdr_id, 'pdr_image_segmentation', true) ) ? true : false;
				if (( $is_base_image_background &&  'product_color' != $attribute_data['type'] ) || !$is_base_image_background) {
					echo do_shortcode(pdr_attributes_render_html($attribute_data['type'], $attribute_data['options'], $attribute_data, $each_attribute));
				}
				?>
				<div class="ui divider"></div>
				<?php
			}
		}
		?>
		<div class="field">
			<label><?php echo esc_html(pdr_get_designer_product_quantity_label()); ?> </label>
			<div class="ui input">
				<input class="pdr-input-fields" name="quantity" type="number" id="quantity" min="1" value="1" />
			</div>
		</div>
	</form>
<?php } ?>
