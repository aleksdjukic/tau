<?php
/**
 * Product base panel.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<li class="pdr-product-base-data-tab pdr-product-base-tab-<?php echo esc_attr( $key ) ; ?>">
	<a href="#pdr_product_base_panel_<?php echo esc_attr( $key ) ; ?>" class="pdr-product-base-data-tab-link">
		<?php echo esc_html( $rule[ 'title' ] ) ; ?>
	</a>
</li>
<?php
