<?php
/**
 * Shape panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="pdr-shape-data-wrapper pdr-settings-wrapper">

	<p class="form-table">
		<label><?php esc_html_e( 'Price' , 'product-designer-for-woocommerce' ) ; ?></label>
		<input type="text" class="pdr-shape-price" name="pdr_price" value="<?php echo esc_attr( $shape->get_price() ) ; ?>"/>
	</p>
<!--	<p class="form-table">
		<label><?php // esc_html_e( 'Usage Count' , 'product-designer-for-woocommerce' ) ; ?></label>
		<input type="number" min="1" class="pdr-shape-usage-count" name="pdr_usage_count" value="<?php // echo esc_attr( $shape->get_usage_count() ) ; ?>"/>
	</p>-->
</div>
<?php
