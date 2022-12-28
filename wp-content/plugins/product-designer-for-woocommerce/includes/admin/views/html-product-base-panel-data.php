<?php
/**
 * Product base panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
$name = 'pdr_rules[' . $key . ']' ;
?>
<div id="pdr_product_base_panel_<?php echo esc_attr( $key ) ; ?>" class="pdr-product-base-panel-content">

	<div class="options_group">
		<p class="form-field pdr-form-field">
			<label for="pdr_product_base_title"><?php esc_html_e( 'Title' , 'product-designer-for-woocommerce' ) ; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ) ; ?>[title]" value="<?php echo esc_attr( $rule[ 'title' ] ) ; ?>">
		</p>

		<div class="pdr-upload-img-container">
			<input type="hidden" class="pdr-upload-img-id" name="<?php echo esc_attr( $name ) ; ?>[img_id]" value="<?php echo esc_attr( $rule[ 'img_id' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-url" name="<?php echo esc_attr( $name ) ; ?>[img_url]" value="<?php echo esc_attr( $rule[ 'img_url' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-top" name="<?php echo esc_attr( $name ) ; ?>[x]" value="<?php echo esc_attr( $rule[ 'x' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-left" name="<?php echo esc_attr( $name ) ; ?>[y]" value="<?php echo esc_attr( $rule[ 'y' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-width" name="<?php echo esc_attr( $name ) ; ?>[w]" value="<?php echo esc_attr( $rule[ 'w' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-height" name="<?php echo esc_attr( $name ) ; ?>[h]" value="<?php echo esc_attr( $rule[ 'h' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-img-factor" name="<?php echo esc_attr( $name ) ; ?>[factor]" value="<?php echo esc_attr( $rule[ 'factor' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-original-img-top" name="<?php echo esc_attr( $name ) ; ?>[imgx]" value="<?php echo esc_attr( $rule[ 'imgx' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-original-img-left" name="<?php echo esc_attr( $name ) ; ?>[imgy]" value="<?php echo esc_attr( $rule[ 'imgy' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-original-img-width" name="<?php echo esc_attr( $name ) ; ?>[imgw]" value="<?php echo esc_attr( $rule[ 'imgw' ] ) ; ?>"/>
			<input type="hidden" class="pdr-upload-original-img-height" name="<?php echo esc_attr( $name ) ; ?>[imgh]" value="<?php echo esc_attr( $rule[ 'imgh' ] ) ; ?>"/>

			<div class="pdr-uploaded-img">
				<img id="target" src="<?php echo esc_url( wp_get_attachment_url( $rule[ 'img_id' ] ) ) ; ?>" class="pdr-crop-image pdr-uploaded-img-preview"/>
			</div>
			<button type="button" class="pdr-upload-img"><?php esc_html_e( 'Select a Image' , 'product-designer-for-woocommerce' ) ; ?></button>
			<button type="button" class="pdr-delete-uploaded-img<?php echo ( '' == $rule[ 'img_id' ] ) ? esc_attr( ' pdr_hide' ) : '' ; ?>"><?php esc_html_e( 'Delete Image' , 'product-designer-for-woocommerce' ) ; ?></button>
		</div>
	</div>

	<?php if ( 'general' != $key ) : ?>
		<p>
			<button type="button" class="pdr-remove-panel button" data-key="<?php echo esc_attr( $key ) ; ?>"><?php esc_html_e( 'Remove Panel' , 'product-designer-for-woocommerce' ) ; ?></button>
		</p>
	<?php endif ; ?>
</div>
<?php
