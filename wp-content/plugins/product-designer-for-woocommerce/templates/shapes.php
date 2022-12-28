<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div class="ui fluid pdr-shapes-category selection dropdown">
	<i class="dropdown icon"></i>
	<div class="default text"><?php esc_html_e( 'All Categories' , 'product-designer-for-woocommerce' ) ; ?></div>
	<div class="menu">
		<div class="item" data-value="0"> <?php esc_html_e( 'All Categories' , 'product-designer-for-woocommerce' ) ; ?></div>
		<?php
		$custom_taxonomy = 'pdr_shapes_cat' ;
		$tax_terms       = get_terms( $custom_taxonomy , array( 'hide_empty' => false ) ) ;

		if ( ! empty( $tax_terms ) && is_array( $tax_terms ) ) {
			foreach ( $tax_terms as $tax_term ) {
				?>
				<div class="item" data-value="<?php echo esc_attr( $tax_term->term_id ) ; ?>"> <?php echo esc_html( $tax_term->name . ' (' . $tax_term->count . ')' ) ; ?></div>
				<?php
			}
		}
		?>
	</div>
</div>
<div class="pdr-shapes-container">
	<?php
	include('html-shapes.php') ;
	?>
</div>

