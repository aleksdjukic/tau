<?php

/**
 * Product Base List Table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Product_Base_List_Table' ) ) {

	/**
	 * PDR_Product_Base_List_Table Class.
	 * */
	class PDR_Product_Base_List_Table {

		/**
		 * Post Type.
		 * 
		 * @var String
		 */
		protected static $post_type = 'pdr_product_base' ;

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

			$columns[ 'cb' ]          = '<input type="checkbox" />' ;
			$columns[ 'title' ]       = esc_html__( 'Name' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'description' ] = esc_html__( 'Description' , 'product-designer-for-woocommerce' ) ;
			//            $columns[ 'preview' ]     = esc_html__( 'Preview' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'post_status' ] = esc_html__( 'Status' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'date' ]        = esc_html__( 'Date' , 'product-designer-for-woocommerce' ) ;

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

			return $actions ;
		}

		/**
		 * Render individual columns.
		 */
		public static function render_columns( $column, $post_id ) {

			switch ( $column ) {
				case 'post_status':
					echo get_post_status( $post_id ) == 'publish' ? wp_kses_post( esc_html__( 'Published' , 'product-designer-for-woocommerce' ) ) : wp_kses_post( ucfirst( get_post_status( $post_id ) ) ) ;
					break ;
				case 'description':
					echo wp_kses_post( get_post_meta( $post_id , 'pdr_description' , true ) ) ;
					break ;
			}
		}

	}

	PDR_Product_Base_List_Table::init() ;
}
