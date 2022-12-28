<?php
/**
 * Product base panels.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="pdr-product-base-data-panels-wrapper">
	<div class="pdr-product-base-data-tab-wrapper">
		<ul class="pdr-product-base-data-tabs">
			<?php
			foreach ( $rules as $key => $rule ) :
				include 'html-product-base-panel-tab.php' ;
			endforeach ;
			?>
		</ul>

		<input type="button" class="pdr-add-new-panel" value="<?php echo esc_attr( 'Add View' , 'product-designer-for-woocommerce' ) ; ?>" />
	</div>

	<div class="pdr-product-base-data-panel-wrapper">
		<?php
		foreach ( $rules as $key => $rule ) :
			include 'html-product-base-panel-data.php' ;
		endforeach ;
		?>
	</div>
	<div class="clear"></div>

	<?php wp_nonce_field( 'pdr_save_data' , 'pdr_meta_nonce' ) ; ?>
</div>
<?php
