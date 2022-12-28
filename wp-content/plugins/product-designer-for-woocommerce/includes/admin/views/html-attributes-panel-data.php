<?php
/**
 * Product base attribute panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
$name          = 'pdr_attributes[' . $key . ']' ;
?>
<div id="pdr_product_base_panel_<?php echo esc_attr( $key ) ; ?>" class="pdr-attributes-panel-content">

	<div class="options_group">
		<p class="form-field pdr-form-field">
			<label for="pdr_attributes_title"><?php esc_html_e( 'Name' , 'product-designer-for-woocommerce' ) ; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ) ; ?>[title]" class="pdr-product-base-attribute-name" value="<?php echo esc_attr( $rule[ 'title' ] ) ; ?>">
		</p>
		<?php
		$options_types = array(
			''              => __( 'Choose Type' , 'product-designer-for-woocommerce' ) ,
			'product_color' => __( 'Product Color' , 'product-designer-for-woocommerce' ) ,
			'text'          => __( 'Text Field' , 'product-designer-for-woocommerce' ) ,
			'textarea'      => __( 'Text Area' , 'product-designer-for-woocommerce' ) ,
			'select'        => __( 'Single Select' , 'product-designer-for-woocommerce' ) ,
			'checkbox'      => __( 'Checkbox' , 'product-designer-for-woocommerce' ) ,
			'radio'         => __( 'Radiobox' , 'product-designer-for-woocommerce' ) ,
				) ;
		?>
		<p class="form-field pdr-form-field">
			<label for="pdr_attributes_type"><?php esc_html_e( 'Choose Attribute Types' , 'product-designer-for-woocommerce' ) ; ?></label>
			<select class="pdr-attributes-type" name="<?php echo esc_attr( $name ) ; ?>[type]" class="pdr-product-base-attribute-type">
				<?php
				if ( is_array( $options_types ) && ! empty( $options_types ) ) {
					foreach ( $options_types as $each_type_key => $each_type_value ) {
						?>
						<option value="<?php echo esc_attr( $each_type_key ) ; ?>" <?php selected( $rule[ 'type' ] , $each_type_key ) ; ?>><?php echo esc_html( $each_type_value ) ; ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<p class="form-field pdr-form-field">
			<label for="pdr_attributes_options"><?php esc_html_e( 'Options' , 'product-designer-for-woocommerce' ) ; ?></label>
			<textarea rows="5" cols="5" name="<?php echo esc_attr( $name ) ; ?>[options]" class="pdr-product-base-attribute-options pdr-attribute-color-field" placeholder="<?php echo esc_attr_e( 'Option Value | Option Name | Price without Currency Symbol, Option Value | Option Name | Price without Currency Symbol' , 'product-designer-for-woocommerce' ) ; ?>"><?php echo esc_textarea( $rule[ 'options' ] ) ; ?></textarea>
		</p>
		<p class="form-field pdr-form-field">
			<label for="pdr_attribute_label"><?php esc_html_e( 'Enter a Label' , 'product-designer-for-woocommerce' ) ; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ) ; ?>[label]" class="pdr-product-base-attribute-label pdr-attribute-color-field" value="<?php echo esc_attr( $rule[ 'label' ] ) ; ?>" placeholder="<?php echo esc_attr_e( 'Enter a Label' , 'product-designer-for-woocommerce' ) ; ?>" />
		</p>
		<div class="form-field pdr-form-field pdr-attribute-color-wrapper">
			<label for="pdr_attributes_colors"><?php esc_html_e( 'Colors' , 'product-designer-for-woocommerce' ) ; ?></label>
			<div class="pdr-attribute-color-content pdr-product-base-attribute-color pdr-attribute-color-field">
				<ul class="pdr-attribute-color-lists" data-key='<?php echo esc_attr( $key ) ; ?>'>

					<?php
					if ( isset( $rule[ 'product_color' ] ) && pdr_check_is_array( $rule[ 'product_color' ] ) ) :
						foreach ( $rule[ 'product_color' ] as $color => $each_color ) :
							$product_color_price = isset( $rule[ 'product_color_price' ][ $color ] ) && ! empty( $rule[ 'product_color_price' ][ $color ] ) ? $rule[ 'product_color_price' ][ $color ] : 0 ;
							?>
							<li class="pdr-atrribute-color" title='<?php echo esc_attr( $each_color ) ; ?>' style="background:<?php echo esc_attr( $color ) ; ?>">
								<span class="dashicons dashicons-dismiss pdr-attribute-remove-color"></span>
								<span data-price="<?php echo esc_attr( $product_color_price ) ; ?>" data-color="<?php echo esc_attr($color); ?>" data-colortitle="<?php echo esc_attr($each_color); ?>" class="dashicons dashicons-edit pdr-attribute-edit-color"></span>
								<input type="hidden" name="<?php echo esc_attr( $name ) ; ?>[product_color][<?php echo esc_attr( $color ) ; ?>]" value="<?php echo esc_attr( $each_color ) ; ?>"/>
								<input type="hidden" name="<?php echo esc_attr( $name ) ; ?>[product_color_price][<?php echo esc_attr( $color ) ; ?>]" value="<?php echo esc_attr( $product_color_price ) ; ?>"/>
							</li>
							<?php
						endforeach ;
					endif ;
					?>
				</ul>
				<input type="button" class="pdr-add-attribute-color" value="<?php esc_html_e( 'Add New Color' , 'product-designer-for-woocommerce' ) ; ?>"/>
			</div>
		</div>
		<p class="form-field pdr-form-field">

			<label for="pdr_attributes_price"><?php esc_html_e( 'Price' , 'product-designer-for-woocommerce' ) ; ?></label>
			<input type="text" name="<?php echo esc_attr( $name ) ; ?>[price]" class="pdr-product-base-attribute-price pdr-attribute-color-field" value="<?php echo esc_attr( $rule[ 'price' ] ) ; ?>"/>
		</p>

		<p>
			<button type="button" class="pdr-remove-attributes button" data-key="<?php echo esc_attr( $key ) ; ?>"><?php esc_html_e( 'Remove Panel' , 'product-designer-for-woocommerce' ) ; ?></button>
		</p>
	</div>
</div>
<?php
