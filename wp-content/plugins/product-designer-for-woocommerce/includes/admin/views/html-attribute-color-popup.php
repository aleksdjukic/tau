<?php
/**
 * Attribute color popup.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="pdr-attribute-color-popup-wrapper">
	<div class="pdr-attribute-color-popup-content">
		<div class="pdr-attribute-color-popup-header">
			<label><?php esc_html_e( 'Manage Colors' , 'product-designer-for-woocommerce' ) ; ?></label>
			<span class="dashicons dashicons-no-alt pdr-attribute-color-close-popup"></span>
		</div>
		<div class="pdr-attribute-color-popup-body">
			<input type="text" placeholder="<?php esc_html_e( 'Color Title' , 'product-designer-for-woocommerce' ) ; ?>" class="pdr-color-name" value="<?php echo esc_attr( $title ) ; ?>"/>
			<input type="text" placeholder="<?php esc_html_e( 'Hex code of Color' , 'product-designer-for-woocommerce' ) ; ?>" class="pdr-color-value" value="<?php echo esc_attr( $color_code ) ; ?>"/>
			<input type="color" class="pdr-color-picker" value="<?php echo esc_attr( $color_code ) ; ?>"/>
			<input type="number" placeholder="<?php esc_html_e( 'Price without currency symbol' , 'product-designer-for-woocommerce' ) ; ?>" class="pdr-color-price" value="<?php echo esc_attr( $color_price ) ; ?>"/>
			<?php
			$popup_button_class = 'pdr-attribute-save-color' ;
			$popup_button_label = esc_html__( 'Add Color' , 'product-designer-for-woocommerce' ) ;
			if ( $color_code ) {
				$popup_button_class = 'pdr-attribute-update-color' ;
				$popup_button_label = esc_html__( 'Update Color' , 'product-designer-for-woocommerce' ) ;
			}
			?>
			<input type="button" class="<?php echo esc_attr( $popup_button_class ) ; ?>" value="<?php echo esc_attr( $popup_button_label ) ; ?>">
		</div>
	</div>
</div>
<?php
