<?php

/**
 * Shape List Table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Shape_List_Table' ) ) {

	/**
	 * PDR_Shape_List_Table Class.
	 * */
	class PDR_Shape_List_Table {

		/**
		 * Post Type.
		 * 
		 * @var String
		 */
		protected static $post_type = 'pdr_shapes' ;

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

			unset( $columns[ 'comments' ] , $columns[ 'date' ] ) ;

			$columns[ 'cb' ]          = '<input type="checkbox" />' ;
			$columns[ 'title' ]       = esc_html__( 'Name' , 'product-designer-for-woocommerce' ) ;
			$columns[ 'image' ]       = esc_html__( 'Preview' , 'product-designer-for-woocommerce' ) ;
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
				'post_status' => array( 'post_status' , true ) ,
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
				case 'image':
					echo do_shortcode( self::$object->get_content() ) ;
					break ;
				case 'post_status':
					echo wp_kses_post( ucfirst( self::$object->get_status() ) ) ;
					break ;
			}
		}

		/**
		 * Pre-fetch any data for the row each column has access to it.
		 */
		public static function prepare_row_data( $post_id ) {
			if ( empty( self::$object ) || self::$object->get_id() !== $post_id ) {
				self::$object = pdr_get_shape( $post_id ) ;
			}
		}

	}

	PDR_Shape_List_Table::init() ;
}
