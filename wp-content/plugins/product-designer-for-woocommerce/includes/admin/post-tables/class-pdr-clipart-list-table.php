<?php

/**
 * Clipart List Table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Clipart_List_Table' ) ) {

	/**
	 * PDR_Clipart_List_Table Class.
	 * */
	class PDR_Clipart_List_Table {

		/**
		 * Post Type.
		 * 
		 * @var String
		 */
		protected static $post_type = 'pdr_cliparts' ;

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

			$columns[ 'cb' ]                 = '<input type="checkbox" />' ;
			$columns[ 'title' ]              = esc_html__( 'Name' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'preview' ]            = esc_html__( 'Preview' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'price' ]              = esc_html__( 'Price' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'clipart_categories' ] = esc_html__( 'Categories' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'featured' ]           = esc_html__( 'Featured' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'post_status' ]        = esc_html__( 'Status' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'date' ]               = esc_html__( 'Date' , 'product-designer-for-woocommerce' ) ;

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
			self::prepare_row_data( $post_id ) ;

			if ( ! self::$object ) {
				return ;
			}

			switch ( $column ) {
				case 'featured':
					echo wp_kses_post( ucfirst( self::$object->get_featured() ) ) ;
					break ;
				case 'clipart_categories':
					$terms = get_the_terms( self::$object->get_id() , 'pdr_clipart_cat' ) ;
					if ( ! $terms ) {
						echo '<span class="na">&ndash;</span>' ;
					} else {
						$termlist = array() ;
						foreach ( $terms as $term ) {
							$termlist[] = '<a href="' . esc_url( admin_url( 'edit.php?pdr_clipart_cat=' . $term->slug . '&post_type=pdr_cliparts' ) ) . ' ">' . esc_html( $term->name ) . '</a>' ;
						}

						echo wp_kses_post( implode( ', ' , $termlist ) ) ;
					}
					break ;
				case 'post_status':
					echo wp_kses_post( ucfirst( self::$object->get_status() ) ) ;
					break ;
				case 'preview':
					echo '<img src="' . wp_kses_post( self::$object->get_thumbnail_url() ) . '" class="pdr-preview-image">' ;
					break ;
				case 'price':
					echo wp_kses_post( wc_price( self::$object->get_price() ) ) ;
					break ;
			}
		}

		/**
		 * Pre-fetch any data for the row each column has access to it.
		 */
		public static function prepare_row_data( $post_id ) {
			if ( empty( self::$object ) || self::$object->get_id() !== $post_id ) {
				self::$object = pdr_get_clipart( $post_id ) ;
			}
		}

	}

	PDR_Clipart_List_Table::init() ;
}
