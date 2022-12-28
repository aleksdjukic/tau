<?php
/**
 * Edit product variation settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="options_group pdr-edit-product-wrapper">
	<p class="form-field">
		<label><?php esc_html_e( 'Select a product base' , 'product-designer-for-woocommerce' ) ; ?></label>
		<?php
		$product_base_ids = get_post_meta( $post_id , 'pdr_product_base_ids' , true ) ;
		pdr_select2_html( array(
			'id'                => 'pdr_product_base_ids' ,
			'class'             => 'pdr_product_base_ids' ,
			'name'              => 'pdr_product_base_ids[' . $loop . ']' ,
			'list_type'         => 'post' ,
			'action'            => 'pdr_json_search_product_bases' ,
			'custom_attributes' => array( 'data-nonce' => wp_create_nonce( 'search-product-bases' ) ) ,
			'placeholder'       => esc_html__( 'Search for a product base&hellip;' , 'product-designer-for-woocommerce' ) ,
			'multiple'          => true ,
			'options'           => $product_base_ids ,
				)
		) ;
		?>
	</p>
</div>
<?php
