<?php
/**
 * Clipart panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="pdr-clipart-data-wrapper pdr-settings-wrapper">

	<p class="form-table">
		<label><?php esc_html_e( 'Price' , 'product-designer-for-woocommerce' ) ; ?></label>
		<input type="text" class="pdr-clipart-price" name="pdr_price" value="<?php echo esc_attr( $clipart->get_price() ) ; ?>"/>
	</p>
	<p class="form-table">
		<label><?php esc_html_e( 'Featured' , 'product-designer-for-woocommerce' ) ; ?></label>
		<input type="checkbox" class="pdr-clipart-featured" name="pdr_featured" value="yes" <?php checked( 'yes' , $clipart->get_featured() ) ; ?>/>
	</p>
<!--	<p class="form-table">
			<label><?php // esc_html_e( 'Usage Count' , 'product-designer-for-woocommerce' ) ; ?></label>
			<input type="number" min="1" class="pdr-clipart-usage-count" name="pdr_usage_count" value="<?php // echo esc_attr( $clipart->get_usage_count() ) ; ?>"/>
	</p>-->
</div>
<?php
