<?php
/**
 * Design Template panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="pdr-design-template-data-wrapper pdr-settings-wrapper">
	<p class="form-table">
		<label><?php esc_html_e( 'Price', 'product-designer-for-woocommerce' ); ?></label>
		<input type="text" class="pdr-design-template-price" name="pdr_price" value="<?php echo esc_attr( $design_template->get_price() ); ?>"/>
	</p>
	<p class="form-table">
		<label><?php esc_html_e( 'Upload Image', 'product-designer-for-woocommerce' ); ?></label>
		<img src="<?php echo do_shortcode( $design_template->get_content() ); ?>" class="pdr-design-template-preview"/> 
		<input type="hidden" class="pdr-design-template-content" name="pdr_content" value="<?php echo do_shortcode( $design_template->get_content() ); ?>"/>
		<input type="hidden" class="pdr-design-template-canvas" name="pdr_canvas" value="<?php echo wc_esc_json( $design_template->read_canvas_from_file() ); ?>"/>
		<span class="button">
			<input type="file" class="pdr-design-template-upload"/>
			<?php esc_html_e( 'Upload File', 'product-designer-for-woocommerce' ); ?>
		</span>
	</p>
	<p class="form-table">
		<label><?php esc_html_e( 'Featured', 'product-designer-for-woocommerce' ); ?></label>
		<input type="checkbox" class="pdr-design-template-featured" name="pdr_featured" value="yes" <?php checked( 'yes', $design_template->get_featured() ); ?>/>
	</p>
</div>
<?php
