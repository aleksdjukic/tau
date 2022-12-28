<?php
/**
 * Product base panels.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="pdr-product-base-product-selection-wrapper pdr-settings-wrapper">
	<p class="form-field">
		<label><?php esc_html_e( 'Assign Product' , 'product-designer-for-woocommerce' ) ; ?></label>
		<?php
		pdr_select2_html( array(
			'id'          => 'pdr_product_ids' ,
			'class'       => 'pdr_product_ids' ,
			'list_type'   => 'products' ,
			'action'      => 'pdr_json_search_products' ,
			'placeholder' => esc_html__( 'Search for a product&hellip;' , 'product-designer-for-woocommerce' ) ,
			'multiple'    => true ,
			'name'        => 'pdr_product_ids' ,
			'options'     => $product_ids ,
				)
		) ;
		?>
	</p>

	<p class="form-field">
		<label><?php esc_html_e( 'Description' , 'product-designer-for-woocommerce' ) ; ?></label>
		<textarea name="pdr_description"><?php echo esc_textarea( $description ) ; ?></textarea>
	</p>

	<p class="form-field">
		<label><?php esc_html_e( 'Base Image Segmentation' , 'product-designer-for-woocommerce' ) ; ?></label>
		<select name="pdr_image_segmentation">
			<?php
			$options = array( 'overlay' => __( 'Foreground/Overlay' , 'product-designer-for-woocommerce' ) , 'background' => __( 'Background' , 'product-designer-for-woocommerce' ) ) ;

			foreach ( $options as $each_option => $option_name ) {
				$selected = $segmentation == $each_option ? 'selected=selected' : '' ;
				?>
				<option value="<?php echo esc_html($each_option) ; ?>" <?php echo do_shortcode($selected) ; ?>><?php echo esc_html($option_name) ; ?></option>
				<?php
			}
			?>
		</select>
	</p>
	<i>
		<?php esc_html_e( 'Select "Foreground/Overlay" if your base image is transparent. Select "Background" if your base image is non-transparent Note: Base Image Design Area selection and Product Color Attribute will not work in Background segmentation.' , 'product-designer-for-woocommerce' ) ; ?>
	</i>
</div>
<?php
