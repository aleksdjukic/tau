<?php
/**
 * Attributes Label
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<li class="pdr-attributes-data-tab pdr-attributes-tab-<?php echo esc_attr( $key ) ; ?>">
	<a href="#pdr_product_base_panel_<?php echo esc_attr( $key ) ; ?>" class="pdr-attributes-data-tab-link">
		<?php echo esc_html( $rule[ 'title' ] ) ; ?>
	</a>
</li>
<?php
