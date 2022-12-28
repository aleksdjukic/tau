<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PDR_Cart' ) ) {

	/**
	 * Handles the cart.
	 */
	class PDR_Cart {

		/**
		 * Init PDR_Cart.
		 */
		public static function init() {
			add_action( 'woocommerce_get_item_data', __CLASS__ . '::maybe_get_custom_item_data', 10, 2 );
			add_action( 'woocommerce_before_calculate_totals', __CLASS__ . '::set_price', 1, 1 );
			add_filter( 'woocommerce_cart_item_thumbnail', __CLASS__ . '::alter_cart_item_thumbnail', 10, 3 );
			add_filter( 'woocommerce_cart_item_name', __CLASS__ . '::alter_cart_item_name', 10, 3 );
			//add_action( 'woocommerce_remove_cart_item' , __CLASS__ . '::remove_file_from_server', 10 , 2 ) ;
			add_filter( 'woocommerce_add_cart_item_data', __CLASS__ . '::add_session_data_to_cart', 10, 3 );
			add_filter( 'pdr_alter_price_cart', __CLASS__ . '::alter_price_of_product_designer', 10, 2 );
			add_action( 'woocommerce_cart_loaded_from_session', __CLASS__ . '::maybe_delete_temp_files' );
			add_action( 'woocommerce_cart_emptied', __CLASS__ . '::delete_temp_files' );
		}

		/**
		 * May be get the custom item data in the cart.

		 * @param array $item_data
		 * @param array $cart_item
		 * @return array
		 */
		public static function maybe_get_custom_item_data( $item_data, $cart_item ) {
			if ( ! isset( $cart_item[ 'pdr_product_designer' ] ) ) {
				return $item_data;
			}

			$item_data = pdr_attributes_in_readable_format( $item_data, $cart_item[ 'pdr_product_designer' ][ 'product_base_id' ], $cart_item[ 'pdr_product_designer' ][ 'data' ] );
			return $item_data;
		}

		public static function add_session_data_to_cart( $cart_item, $product_id, $variation_id ) {
			$check_is_session = WC()->session->get( 'pdr_session_item_' . $product_id );
			if ( $check_is_session ) {
				$cart_item = array_merge( $cart_item, $check_is_session );
				WC()->session->__unset( 'pdr_session_item_' . $product_id );
			}
			return $cart_item;
		}

		/**
		 * Alter the product price based on customizer.
		 */
		public static function set_price( $cart_object ) {
			foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {
				if ( ! isset( $value[ 'pdr_product_designer' ] ) ) {
					continue;
				}
				/**
				 * Alter price set to the cart page via below filter 
				 *
				 * @since 1.0
				 */
				$value[ 'data' ]->set_price( apply_filters( 'pdr_alter_price_cart', $value[ 'pdr_product_designer' ][ 'price' ], $value ) );
			}
		}

		public static function alter_price_of_product_designer( $pdr_price, $session_data ) {
			if ( function_exists( 'gtw_convert_price' ) ) {
				if ( isset( $session_data[ 'gtw_gift_wrapper' ] ) && isset( $session_data[ 'data' ] ) ) {
					/**
					 * Alter price as per the gift wrapper compatibility
					 *
					 * @since 1.0
					 */
					$price     = apply_filters( 'gtw_gift_wrapper_product_price', gtw_convert_price( floatval( $session_data[ 'gtw_gift_wrapper' ][ 'price' ] ) ), $session_data[ 'data' ] );
					$pdr_price = $pdr_price + $price;
				}
			}
			return $pdr_price;
		}

		/**
		 * Alter the cart item thumbnail.

		 * @param string $thumbnail
		 * @param array $cart_item
		 * @param string $cart_item_key
		 * @return string
		 */
		public static function alter_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
			if ( ! isset( $cart_item[ 'pdr_product_designer' ] ) ) {
				return $thumbnail;
			}

			$width      = get_option( 'pdr_thumbnail_width', '80' );
			$height     = get_option( 'pdr_thumbnail_height', '80' );
			$bg         = isset( $cart_item[ 'pdr_product_designer' ][ 'bg' ] ) ? $cart_item[ 'pdr_product_designer' ][ 'bg' ] : '';
			$image_url  = isset( $cart_item[ 'pdr_product_designer' ][ 'img_url' ] ) ? $cart_item[ 'pdr_product_designer' ][ 'img_url' ] : ''; //get image file name;
			$temp_files = isset( $cart_item[ 'pdr_product_designer' ][ 'temp_files' ] ) ? $cart_item[ 'pdr_product_designer' ][ 'temp_files' ] : array();

			if ( empty( $image_url ) ) {
				return $thumbnail;
			}

			$thumbnail = '<div class="pdr-cart-thumbnail" data-itemKey="' . esc_attr( $cart_item_key ) . '">';

			if ( ! empty( $temp_files ) ) {
				foreach ( $temp_files as $key => $fname ) {
					$attachment_file = get_attached_file( $key );

					if ( file_exists( $attachment_file ) ) {
						$url       = file_get_contents( $attachment_file );
						$thumbnail .= '<img style="background:' . $bg . '; border-radius: 0px;" src="' . $url . '" width="' . $width . 'px" height="' . $height . 'px"/>';
					}
				}
			} else {
				$file_api   = new PDR_File( '', $image_url );
				$image_data = $file_api->retrieve();
				$image_data = json_decode( wp_unslash( $image_data ), true );

				if ( is_array( $image_data ) ) {
					foreach ( $image_data as $view => $url ) {
						$image     = new \claviska\SimpleImage();
						$image->fromDataUri( $url );
						$image->resize( 80, 80 );
						$url       = $image->toDataUri();
						$thumbnail .= '<img style="background:' . $bg . '; border-radius: 0px;" src="' . $url . '" width="' . $width . 'px" height="' . $height . 'px"/>';
					}
				}
			}

			$thumbnail .= '</div>';
			return $thumbnail;
		}

		/**
		 * Alter the cart item name.
		 * 
		 * @param string $name
		 * @param array $cart_item
		 * @param string $cart_item_key
		 * @return string
		 */
		public static function alter_cart_item_name( $name, $cart_item, $cart_item_key ) {
			if ( ! isset( $cart_item[ 'pdr_product_designer' ] ) ) {
				return $name;
			}

			if ( ! is_cart() ) {
				return $name;
			}

			if ( '2' == get_option( 'pdr_product_customization_info_cart' ) ) {
				return $name;
			}

			$product_id        = ! empty( $cart_item[ 'variation_id' ] ) ? $cart_item[ 'variation_id' ] : $cart_item[ 'product_id' ];
			$url               = get_permalink( get_option( 'pdr_product_designer_page_id' ) );
			$design_editor_url = add_query_arg( array( 'product_id' => $product_id, 'pdr_id' => $cart_item[ 'pdr_product_designer' ][ 'product_base_id' ], 'cart_id' => $cart_item[ 'pdr_product_designer' ][ 'canvas_data' ], 'cart_item' => $cart_item_key ), $url );

			$pdr_name = '<div class="pdr-cart-item-name">';
			$pdr_name .= '<a href="' . esc_url( $design_editor_url ) . '" class="pdr-cart-item-edit-design">' . esc_html__( 'Edit Design', 'product-designer-for-woocommerce' ) . '</a>';
			$pdr_name .= '</div>';
			return $name . $pdr_name;
		}

		/**
		 * Remove File from Server
		 */
		public static function remove_file_from_server( $cart_item_key, $instance ) {
			$cart_item = $instance->cart_contents[ $cart_item_key ];
			if ( isset( $cart_item[ 'pdr_product_designer' ] ) ) {
				$image_data = $cart_item[ 'pdr_product_designer' ][ 'img_url' ]; //get image file name;
				$canvas_obj = $cart_item[ 'pdr_product_designer' ][ 'canvas_data' ]; //get canvas file name;
				$file_api   = new PDR_File( '', $image_data );
				$file_api->delete();
				$file_api   = new PDR_File( '', $canvas_obj );
				$file_api->delete();
			}
		}

		/**
		 * Maybe delete temp files.
		 */
		public static function maybe_delete_temp_files( $cart ) {
			if ( $cart->is_empty() ) {
				self::delete_temp_files();
			}
		}

		/**
		 * Delete temp files.
		 */
		public static function delete_temp_files() {
			$temp_files = WC()->session->get( 'pdr_cart_temp_files', array() );

			if ( ! empty( $temp_files ) ) {
				foreach ( $temp_files as $key => $fname ) {
					wp_delete_attachment( $key, true );
				}

				WC()->session->set( 'pdr_cart_temp_files', array() );
			}
		}

	}

	PDR_Cart::init();
}
