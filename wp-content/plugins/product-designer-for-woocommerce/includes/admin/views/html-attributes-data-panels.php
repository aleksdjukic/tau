<?php
/**
 * Product base panels.
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="pdr-attributes-data-panels-wrapper">
	<div class="pdr-product-base-data-tab-wrapper">
		<ul class="pdr-attributes-data-tabs">
			<?php
			if ( is_array( $rules ) && !empty( $rules ) ) {
				foreach ( $rules as $key => $rule ) :
					include 'html-attributes-panel-tab.php';
				endforeach;
			}
			?>
		</ul>

		<input type="button" class="pdr-add-new-attributes" value="<?php echo esc_attr( 'Add New', 'product-designer-for-woocommerce' ); ?>" />
	</div>

	<div class="pdr-attributes-data-panel-wrapper">
		<?php
		if ( is_array( $rules ) && !empty( $rules ) ) {
			foreach ( $rules as $key => $rule ) :
			   
				include 'html-attributes-panel-data.php';
			endforeach;
		}
		?>
	</div>
	<div class="clear"></div>

	<?php wp_nonce_field( 'pdr_save_data', 'pdr_meta_nonce' ); ?>
</div>
<?php
