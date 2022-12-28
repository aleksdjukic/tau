<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_Admin_Dashboard' ) ) {

	class PDR_Admin_Dashboard {

		public static function dashboard() {
			?>
			<style type="text/css">

				.pdr-admin-content {
					background:#ff7d2b;
					float:left;
					padding:20px;
					margin:20px;
					width:200px;
					display: flex;
				}
				.pdr-heading {
					font-size:18px;
					font-weight:bold;
					text-transform: uppercase;
					text-align: center;
					display: block;
					width:100%;
				}
				.pdr-count {
					font-size:20px;
					font-weight: bold;
				}


			</style>
			<h2><?php esc_html_e( 'Dashboard' , 'product-designer-for-woocommerce' ) ; ?></h2>
			<?php
			$dashboard_details = array(
				__( 'Orders' , 'product-designer-for-woocommerce' )        => 'pdr_orders' ,
				__( 'Cliparts' , 'product-designer-for-woocommerce' )      => 'pdr_cliparts' ,
				__( 'Shapes' , 'product-designer-for-woocommerce' )        => 'pdr_shapes' ,
				__( 'Templates' , 'product-designer-for-woocommerce' )     => 'pdr_design_templates' ,
				__( 'Product Bases' , 'product-designer-for-woocommerce' ) => 'pdr_product_base' ,
					) ;
			?>
			<div class="pdr-admin-dashboard-area">
				<?php
				if ( is_array( $dashboard_details ) && ! empty( $dashboard_details ) ) {
					foreach ( $dashboard_details as $title => $type ) {
						?>
						<div class="pdr-admin-content">
							<div class="pdr-heading"><?php esc_attr_e($title) ; ?></div>
							<div class="pdr-count"><?php echo do_shortcode( wp_count_posts( $type )->publish ) ; ?></div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		}

	}

}
