<?php
/**
 * Order List Table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Order_List_Table' ) ) {

	/**
	 * PDR_Order_List_Table Class.
	 * */
	class PDR_Order_List_Table {

		/**
		 * Post Type.
		 * 
		 * @var String
		 */
		protected static $post_type = 'pdr_orders' ;

		/**
		 * Object.
		 * 
		 * @var Object
		 */
		protected static $object ;

		/**
		 * Initialize the hooks.
		 */
		public static function init() {
			//            add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns' , array( __CLASS__ , 'define_sortable_columns' ) ) ;
			add_filter( 'manage_' . self::$post_type . '_posts_columns' , array( __CLASS__ , 'define_columns' ) ) ;
			add_action( 'manage_' . self::$post_type . '_posts_custom_column' , array( __CLASS__ , 'render_columns' ) , 10 , 2 ) ;
			add_filter( 'bulk_actions-edit-' . self::$post_type , array( __CLASS__ , 'define_bulk_actions' ) ) ;
			add_filter( 'post_row_actions' , array( __CLASS__ , 'row_actions' ) , 100 , 2 ) ;
		}

		/**
		 * Define the which columns to show on this screen.
		 *
		 * @return array
		 */
		public static function define_columns( $columns ) {
			if ( empty( $columns ) && ! is_array( $columns ) ) {
				$columns = array() ;
			}

			unset( $columns[ 'comments' ] , $columns[ 'date' ] , $columns[ 'title' ] ) ;

			$columns[ 'cb' ]            = '<input type="checkbox" />' ;
			$columns[ 'wc_order_id' ]   = esc_html__( 'Order ID' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'user_details' ]  = esc_html__( 'User Details' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'product_id' ]    = esc_html__( 'Product' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'product_base' ]  = esc_html__( 'Product Base' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'image_url' ]     = esc_html__( 'Image URL' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'product_price' ] = esc_html__( 'Product Price' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'download' ]      = esc_html__( 'Download Design' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'date' ]          = esc_html__( 'Date' , 'product-designer-for-woocommerce' ) ;

			return $columns ;
		}

		/**
		 * Define which columns are sortable.
		 *
		 * @return array
		 */
		public static function define_sortable_columns( $columns ) {
			$custom_columns = array(
				'wc_order_id' => array( 'wc_order_id' , true ) ,
					) ;

			return wp_parse_args( $custom_columns , $columns ) ;
		}

		/**
		 * Define bulk actions.
		 * 
		 * @return array
		 */
		public static function define_bulk_actions( $actions ) {

			unset( $actions[ 'edit' ] ) ;

			return $actions ;
		}

		/**
		 * Set row actions.
		 *
		 * @return array
		 */
		public static function row_actions( $actions, $post ) {
			if ( self::$post_type !== $post->post_type ) {
				return $actions ;
			}

			//Unset the Quick edit.
			unset( $actions[ 'inline hide-if-no-js' ] ) ;
			unset( $actions[ 'edit' ] ) ;

			return $actions ;
		}

		/**
		 * Render individual columns.
		 */
		public static function render_columns( $column, $post_id ) {
			self::prepare_row_data( $post_id ) ;

			if ( ! self::$object ) {
				return ;
			}

			switch ( $column ) {
				case 'wc_order_id':
					echo wp_kses_post( '<a href="' . get_edit_post_link( self::$object->get_wc_order_id() ) . '">#' . self::$object->get_wc_order_id() . '</a>' ) ;
					$item_id = wp_kses_post( self::$object->get_wc_item_id() ) ;
					if ( $item_id ) {
						try {
							$order_item = new WC_Order_Item_Product( self::$object->get_wc_item_id() ) ;
							if ( $order_item ) {
								echo do_shortcode( wc_display_item_meta( $order_item ) ) ;
							}
						} catch ( Exception $ex ) {
							return ;
						}
					}
					break ;
				case 'user_details':
					echo wp_kses_post( self::$object->get_user_name() . ' (' . self::$object->get_user_email() . ')' ) ;
					break ;
				case 'product_id':
					echo wp_kses_post( '<a href="' . get_edit_post_link( self::$object->get_product_id() ) . '">' . self::$object->get_product()->get_name() . '</a>' ) ;
					break ;
				case 'product_base':
					echo wp_kses_post( '<a href="' . get_edit_post_link( self::$object->get_product_base_id() ) . '">' . get_the_title(self::$object->get_product_base_id()) . '</a>' ) ;
					break ;
				case 'image_url':
					$content = get_post_field( 'post_content' , $post_id ) ;

					$bg = isset( json_decode( $content )->pdr_extra->bg ) ? 'background:' . json_decode( $content )->pdr_extra->bg : '' ;



					$image_url  = self::$object->get_image_url() ;
					$image_data = json_decode( $image_url , true , JSON_UNESCAPED_SLASHES ) ;
					if ( is_array( $image_data ) && ! empty( $image_data ) ) {
						foreach ( $image_data as $each_image_url ) {
							?>
							<img style="<?php echo '' != $bg ? wp_kses_post( $bg ) : '' ; ?>" width="80" height="80" src="<?php echo wp_kses_post( $each_image_url ) ; ?>"/>
							<?php
						}
					}
					break ;
				case 'download':
					$order_id = self::$object->get_wc_order_id() ;
					$wc_order = wc_get_order( $order_id ) ;
					if ( $wc_order ) {
						$order_key         = $wc_order->get_order_key() ;
						$url               = get_permalink( get_option( 'pdr_product_designer_page_id' ) ) ;
						$design_editor_url = add_query_arg( array( 'product_id' => self::$object->get_product_id() , 'pdr_id' => self::$object->get_product_base_id() , 'order_key' => $order_key , 'order_id' => $post_id ) , $url ) ;
						echo '<a href="' . esc_url( $design_editor_url ) . '" class="pdr-order-item-download-design">' . esc_html__( 'Download Design' , 'product-designer-for-woocommerce' ) . '</a>' ;
					} else {
						echo esc_html__( 'WooCommerce order not exists' , 'product-designer-for-woocommerce' ) ;
					}
					break ;
				case 'product_price':
					echo wp_kses_post( wc_price( self::$object->get_product_price() ) ) ;
					break ;
			}
		}

		/**
		 * Pre-fetch any data for the row each column has access to it.
		 */
		public static function prepare_row_data( $post_id ) {
			if ( empty( self::$object ) || self::$object->get_id() !== $post_id ) {
				self::$object = pdr_get_order( $post_id ) ;
			}
		}

	}

	PDR_Order_List_Table::init() ;
}
