<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
$url = get_permalink( get_option( 'pdr_product_designer_page_id' ) ) ;

$design_editor_url = esc_url_raw( add_query_arg( array( 'product_id' => $product_id , 'pdr_id' => $pdr_id ) , $url ) ) ;
?>
<a class="<?php echo esc_html( pdr_get_customize_product_button_classes() ) ; ?>" href="<?php echo esc_url( $design_editor_url ) ; ?>" target="_blank"><?php echo esc_html( pdr_get_customize_product_button_name() ) ; ?></a>
