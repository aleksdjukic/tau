<?php

/**
 * Product Settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'PDR_Product_Settings' ) ) {

	/**
	 * Class.
	 */
	class PDR_Product_Settings {

		/**
		 * Class Initialization.
		 */
		public static function init() {
			// Add custom settings for edit product.
			add_filter( 'woocommerce_product_options_general_product_data' , array( __CLASS__ , 'add_product_custom_settings' ) , 10 ) ;
			// Save the custom settings data.
			add_action( 'woocommerce_process_product_meta' , array( __CLASS__ , 'save_product_settings' ) , 10 ) ;
			// Add the custom settings for edit variation product.
			add_action( 'woocommerce_product_after_variable_attributes' , array( __CLASS__ , 'add_product_variation_custom_settings' ) , 10 , 3 ) ;
			// Save the custom settings for variation.
			add_action( 'woocommerce_save_product_variation' , array( __CLASS__ , 'save_product_variation_settings' ) , 10 , 2 ) ;
		}

		/**
		 * Add the custom settings for edit product.
		 * 
		 * @return void
		 */
		public static function add_product_custom_settings() {
			if ( ! is_admin() ) {
				return ;
			}

			global $post ;

			if ( ! is_object( $post ) ) {
				return ;
			}

			$post_id = $post->ID ;

			include_once 'views/html-edit-product-settings.php' ;
		}

		/**
		 * Add the custom settings for edit variation product.
		 * 
		 * @return void
		 */
		public static function add_product_variation_custom_settings( $loop, $variation_data, $variation ) {
			if ( ! is_admin() ) {
				return ;
			}

			if ( ! is_object( $variation ) ) {
				return ;
			}

			$post_id = $variation->ID ;

			include 'views/html-edit-product-variation-settings.php' ;
		}

		/**
		 * Save the custom settings for product.
		 * 
		 * @return void
		 */
		public static function save_product_settings( $post_id ) {

			$product_base_ids = isset( $_REQUEST[ 'pdr_product_base_ids' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_product_base_ids' ] ) ) : array() ;

			$product_custom_meta = array(
				'pdr_product_base_ids' => $product_base_ids
					) ;

			foreach ( $product_custom_meta as $meta_key => $meta_value ) {
				update_post_meta( $post_id , $meta_key , $meta_value ) ;
			}
		}

		/**
		 * Save the custom settings for product variation.
		 * 
		 * @return void
		 */
		public static function save_product_variation_settings( $variation_id, $i ) {
			$product_base_ids    = isset( $_REQUEST[ 'pdr_product_base_ids' ][ $i ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_product_base_ids' ][ $i ] ) ) : array() ;
			$product_custom_meta = array(
				'pdr_product_base_ids' => $product_base_ids
					) ;

			foreach ( $product_custom_meta as $meta_key => $meta_value ) {
				update_post_meta( $variation_id , $meta_key , $meta_value ) ;
			}
		}

	}

	//  PDR_Product_Settings::init() ;
}
