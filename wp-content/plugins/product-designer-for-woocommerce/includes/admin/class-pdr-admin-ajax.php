<?php

/**
 * Admin Ajax.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'PDR_Admin_Ajax' ) ) {

	/**
	 * Class.
	 */
	class PDR_Admin_Ajax {

		/**
		 *  Class initialization.
		 */
		public static function init() {

			$actions = array(
				'json_search_products_and_variations' => false,
				'json_search_products'                => false,
				'json_search_customers'               => false,
				'json_search_product_bases'           => false,
				'add_product_base_panel'              => false,
				'add_attributes'                      => false,
				'attribute_color_popup'               => false,
				'edit_attribute_color_popup'          => false,
				'enable_modules'                      => false,
				'add_to_cart'                         => true,
				'add_to_session'                      => true,
				'save_design'                         => false, //only applicable for member
				'load_design'                         => false,
				'get_category_products'               => true,
				'get_base_products'                   => true,
				'delete_design'                       => false,
				'get_my_designs'                      => false,
				'get_category_cliparts'               => true,
				'get_category_shapes'                 => true,
				'get_category_templates'              => true,
				'get_canvas_object'                   => true,
				'save_design_from_clientdb'           => true,
			);

			foreach ( $actions as $action => $nopriv ) {
				add_action( 'wp_ajax_pdr_' . $action, array( __CLASS__, $action ) );

				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_pdr_' . $action, array( __CLASS__, $action ) );
				}
			}
		}

		/**
		 * Search for products.
		 * 
		 * @return void
		 */
		public static function json_search_products( $term = '', $include_variations = false ) {
			check_ajax_referer( 'search-products', 'pdr_security' );

			try {

				if ( empty( $term ) && isset( $_GET[ 'term' ] ) ) {
					$term = isset( $_GET[ 'term' ] ) ? wc_clean( wp_unslash( $_GET[ 'term' ] ) ) : '';
				}

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Products found', 'product-designer-for-woocommerce' ) );
				}

				if ( ! empty( $_GET[ 'limit' ] ) ) {
					$limit = absint( $_GET[ 'limit' ] );
				} else {
					/**
					 * JSON search limit alteration
					 *
					 * @since 1.0
					 */
					$limit = absint( apply_filters( 'woocommerce_json_search_limit', 30 ) );
				}

				$data_store = WC_Data_Store::load( 'product' );
				$ids        = $data_store->search_products( $term, '', ( bool ) $include_variations, false, $limit );

				$product_objects = pdr_filter_readable_products( $ids );
				$products        = array();

				foreach ( $product_objects as $product_object ) {

					$products[ $product_object->get_id() ] = rawurldecode( $product_object->get_formatted_name() );
				}
				/**
				 * JSON send search result
				 *
				 * @since 1.0
				 */
				wp_send_json( apply_filters( 'woocommerce_json_search_found_products', $products ) );
			} catch ( Exception $ex ) {
				wp_die();
			}
		}

		/**
		 * Search for product variations.
		 * 
		 * @return void
		 */
		public static function json_search_products_and_variations( $term = '', $include_variations = false ) {
			self::json_search_products( '', true );
		}

		/**
		 * Customers search.
		 * 
		 * @return void
		 */
		public static function json_search_customers() {
			check_ajax_referer( 'pdr-search-nonce', 'pdr_security' );

			try {
				$term = isset( $_GET[ 'term' ] ) ? wc_clean( wp_unslash( $_GET[ 'term' ] ) ) : ''; // @codingStandardsIgnoreLine.

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Customer found', 'product-designer-for-woocommerce' ) );
				}

				$exclude = isset( $_GET[ 'exclude' ] ) ? wc_clean( wp_unslash( $_GET[ 'exclude' ] ) ) : ''; // @codingStandardsIgnoreLine.
				$exclude = ! empty( $exclude ) ? array_map( 'intval', explode( ',', $exclude ) ) : array();

				if ( ! empty( $_GET[ 'limit' ] ) ) {
					$limit = absint( $_GET[ 'limit' ] );
				} else {
					/**
					 * JSON search limit
					 *
					 * @since 1.0
					 */
					$limit = absint( apply_filters( 'woocommerce_json_search_limit', 30 ) );
				}

				$found_customers = array();
				$customers_query = new WP_User_Query(
						array(
					'fields'         => 'all',
					'orderby'        => 'display_name',
					'exclude'        => $exclude,
					'search'         => '*' . $term . '*',
					'number'         => $limit,
					'search_columns' => array( 'ID', 'user_login', 'user_email', 'user_nicename' ),
						)
				);
				$customers       = $customers_query->get_results();

				if ( pdr_check_is_array( $customers ) ) {
					foreach ( $customers as $customer ) {
						$found_customers[ $customer->ID ] = $customer->display_name . ' (#' . $customer->ID . ' &ndash; ' . sanitize_email( $customer->user_email ) . ')';
					}
				}

				wp_send_json( $found_customers );
			} catch ( Exception $ex ) {
				wp_die();
			}
		}

		/**
		 * Search for product bases.
		 * 
		 * @return void
		 */
		public static function json_search_product_bases() {
			check_ajax_referer( 'search-product-bases', 'pdr_security' );

			try {

				if ( empty( $term ) && isset( $_GET[ 'term' ] ) ) {
					$term = isset( $_GET[ 'term' ] ) ? wc_clean( wp_unslash( $_GET[ 'term' ] ) ) : '';
				}

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Product bases found', 'product-designer-for-woocommerce' ) );
				}

				if ( ! empty( $_GET[ 'limit' ] ) ) {
					$limit = absint( $_GET[ 'limit' ] );
				} else {
					$limit = 30;
				}

				global $wpdb;
				$like = '%' . $wpdb->esc_like( $term ) . '%';

				$product_base_objects = array_filter( array_unique( $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT * FROM {$wpdb->posts} as posts
			WHERE posts.post_type='pdr_product_base' AND posts.post_status IN('publish')
                        AND posts.post_title LIKE %s LIMIT %d", $like, $limit ) ) ) );

				$product_bases = array();
				foreach ( $product_base_objects as $product_base_object ) {
					$product_bases[ $product_base_object->ID ] = $product_base_object->post_title . ' #' . $product_base_object->ID;
				}
				/**
				 * JSON product designer search result
				 *
				 * @since 1.0
				 */
				wp_send_json( apply_filters( 'pdr_json_search_found_product_bases', $product_bases ) );
			} catch ( Exception $ex ) {
				wp_die();
			}
		}

		/**
		 * Add the product base panel.
		 * 
		 * @return void
		 */
		public static function add_product_base_panel() {
			check_ajax_referer( 'pdr-panel-nonce', 'pdr_security' );

			try {

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action", 'product-designer-for-woocommerce' ) );
				}

				$key  = uniqid();
				$rule = pdr_get_product_base_default_rule();
				// Get the product base panel tab.
				ob_start();
				include_once ('views/html-product-base-panel-tab.php');
				$tab  = ob_get_contents();
				ob_end_clean();

				// Get the product base panel content.
				ob_start();
				include_once ('views/html-product-base-panel-data.php');
				$content = ob_get_contents();
				ob_end_clean();

				wp_send_json_success( array( 'name' => $tab, 'content' => $content ) );
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

		/**
		 * Add the product base panel.
		 * 
		 * @return void
		 */
		public static function add_attributes() {
			check_ajax_referer( 'pdr-panel-nonce', 'pdr_security' );

			try {

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action", 'product-designer-for-woocommerce' ) );
				}

				$key  = uniqid();
				$rule = pdr_get_product_base_default_attribute();
				// Get the product base panel tab.
				ob_start();
				include_once ('views/html-attributes-panel-tab.php');
				$tab  = ob_get_contents();
				ob_end_clean();

				// Get the product base panel content.
				ob_start();
				include_once ('views/html-attributes-panel-data.php');
				$content = ob_get_contents();
				ob_end_clean();

				wp_send_json_success( array( 'name' => $tab, 'content' => $content ) );
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

		/**
		 * Show the attribute color popup.
		 * 
		 * @return void
		 */
		public static function attribute_color_popup() {
			check_ajax_referer( 'pdr-panel-nonce', 'pdr_security' );

			try {

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action", 'product-designer-for-woocommerce' ) );
				}
				$data        = isset( $_REQUEST[ 'data' ] ) && is_array( $_REQUEST[ 'data' ] ) && ! empty( $_REQUEST[ 'data' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'data' ] ) ) : false;
				$title       = '';
				$color_code  = '';
				$color_price = 0;

				if ( $data ) {
					$title       = isset( $data[ 'colortitle' ] ) ? $data[ 'colortitle' ] : '';
					$color_code  = isset( $data[ 'color' ] ) ? $data[ 'color' ] : '';
					$color_price = isset( $data[ 'price' ] ) ? $data[ 'price' ] : '';
				}
				// Get the attribute color popup.
				ob_start();
				include_once ('views/html-attribute-color-popup.php');
				$content = ob_get_contents();
				ob_end_clean();

				wp_send_json_success( array( 'content' => $content ) );
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

		/**
		 * Handles the enable/disable modules.
		 * 
		 * @return void
		 */
		public static function enable_modules() {
			check_ajax_referer( 'pdr-module-nonce', 'pdr_security' );

			try {

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action", 'product-designer-for-woocommerce' ) );
				}

				$option_name = isset( $_REQUEST[ 'key' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'key' ] ) ) : ''; // @codingStandardsIgnoreLine.
				$action      = isset( $_REQUEST[ 'action_type' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'action_type' ] ) ) : ''; // @codingStandardsIgnoreLine.

				if ( empty( $option_name ) ) {
					throw new exception( esc_html__( 'Cannot complete action', 'product-designer-for-woocommerce' ) );
				}

				if ( 'enable' == $action ) {
					$value  = '1';
					$action = 'disable';
					$label  = esc_html__( 'Disable', 'product-designer-for-woocommerce' );
				} else {
					$value  = '';
					$action = 'enable';
					$label  = esc_html__( 'Enable', 'product-designer-for-woocommerce' );
				}

				update_option( $option_name, $value );

				wp_send_json_success( array( 'action' => $action, 'label' => $label ) );
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

		/**
		 * Add a customize product to the cart.
		 * 
		 * @return void
		 */
		public static function add_to_cart() {
			check_ajax_referer( 'pdr-add-cart-nonce', 'pdr_security' );

			try {
				$product_id = isset( $_REQUEST[ 'product_id' ] ) ? absint( $_REQUEST[ 'product_id' ] ) : ''; // @codingStandardsIgnoreLine.
				if ( empty( $product_id ) ) {
					throw new exception( __( 'Cannot complete action', 'product-designer-for-woocommerce' ) );
				}

				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					throw new exception( __( 'Cannot complete action', 'product-designer-for-woocommerce' ) );
				}

				$bg          = isset( $_REQUEST[ 'bg' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'bg' ] ) ) : '';
				$image_url   = isset( $_REQUEST[ 'image_url' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'image_url' ] ) ) : '';
				$canvas_data = isset( $_REQUEST[ 'canvas_data' ] ) ? wc_clean( $_REQUEST[ 'canvas_data' ] ) : '';
				if ( empty( $canvas_data ) || empty( $image_url ) ) {
					throw new exception( __( 'Cannot complete action, invalid details found', 'product-designer-for-woocommerce' ) );
				}

				$quantity        = isset( $_REQUEST[ 'quantity' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'quantity' ] ) ) : 1;
				$price           = isset( $_REQUEST[ 'price' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'price' ] ) ) : $product->get_price();
				$product_base_id = isset( $_REQUEST[ 'product_base' ] ) ? absint( $_REQUEST[ 'product_base' ] ) : '';

				$shapes        = isset( $_REQUEST[ 'shapes' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'shapes' ] ) ) : '';
				$cliparts      = isset( $_REQUEST[ 'cliparts' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cliparts' ] ) ) : '';
				$text          = isset( $_REQUEST[ 'text' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'text' ] ) ) : '';
				$data          = isset( $_REQUEST[ 'data' ] ) ? wc_clean( $_REQUEST[ 'data' ] ) : '';
				$store_data_in = isset( $_REQUEST[ 'store_data_in' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'store_data_in' ] ) ) : 'server-side';
				$filename      = isset( $_REQUEST[ 'file_name' ] ) && ! empty( $_REQUEST[ 'file_name' ] ) ? wc_clean( $_REQUEST[ 'file_name' ] ) : uniqid();

				//for image
				$filename_image = $filename . '-image';
				$cart_item_data = array(
					'pdr_product_designer' => array(
						'product_id'      => $product_id,
						'price'           => floatval( $price ) / $quantity,
						'product_base_id' => $product_base_id,
						'product_base'    => ( array ) get_post_meta( $product_base_id, 'pdr_rules', true ),
						'bg'              => $bg,
						'img_url'         => $filename_image, // to optimise load it from server
						'canvas_data'     => $filename,
						'shapes'          => $shapes,
						'cliparts'        => $cliparts,
						'text'            => $text,
						'qty'             => $quantity,
						'data'            => $data,
						'temp_files'      => array()
					),
				);

				$cart_item = isset( $_REQUEST[ 'cart_item' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_item' ] ) ) : '';
				if ( $cart_item ) {
					// Remove a product in the cart.
					WC()->cart->remove_cart_item( $cart_item );
				}

				$thumbnail_data = json_decode( $image_url, true );
				if ( is_array( $thumbnail_data ) ) {
					foreach ( $thumbnail_data as $view => $url ) {
						$image = new \claviska\SimpleImage();
						$image->fromDataUri( $url );
						$image->resize( 80, 80 );

						$file     = new PDR_File( $image->toDataUri() );
						$uploaded = $file->upload_files( array( 'name' => uniqid() . '-thumbnail.txt' ), 'temp' );

						if ( ! empty( $uploaded ) ) {
							foreach ( $uploaded as $_filename => $_file_path ) {
								$attach_id = $file->add_to_library( $_file_path );

								if ( $attach_id > 0 ) {
									$cart_item_data[ 'pdr_product_designer' ][ 'temp_files' ][ $attach_id ] = $_filename;
									update_post_meta( $attach_id, 'is_pdr', 1 );
								}
							}
						}
					}
				}

				WC()->session->set( 'pdr_cart_temp_files', $cart_item_data[ 'pdr_product_designer' ][ 'temp_files' ] + WC()->session->get( 'pdr_cart_temp_files', array() ) );

				// Add a product in the cart.
				$add_to_cart = WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), $cart_item_data );
				if ( ! $add_to_cart ) {
					throw new exception( __( 'Sorry, unable to add product to cart, please visit cart page for more details', 'product-designer-for-woocommerce' ) );
				}

				if ( 'server-side' === $store_data_in ) {
					$file_api = new PDR_File( $canvas_data, $filename );
					$file_api->update();

					$file_api = new PDR_File( $image_url, $filename_image );
					$file_api->update();
				}

				wp_send_json_success( array(
					'url'                => wc_get_cart_url(),
					'canvas_filename'    => $filename,
					'image_url_filename' => $filename_image,
					'item_key'           => $add_to_cart,
				) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => esc_html( $e->getMessage() ) ) );
			}
		}

		/**
		 * Add a customize product to the cart.
		 * 
		 * @return void
		 */
		public static function add_to_session() {
			check_ajax_referer( 'pdr-add-cart-nonce', 'pdr_security' );

			try {
				$product_id = isset( $_REQUEST[ 'product_id' ] ) ? absint( $_REQUEST[ 'product_id' ] ) : ''; // @codingStandardsIgnoreLine.
				if ( empty( $product_id ) ) {
					throw new exception( __( 'Cannot complete action', 'product-designer-for-woocommerce' ) );
				}

				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					throw new exception( __( 'Cannot complete action', 'product-designer-for-woocommerce' ) );
				}

				$bg          = isset( $_REQUEST[ 'bg' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'bg' ] ) ) : '';
				$image_url   = isset( $_REQUEST[ 'image_url' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'image_url' ] ) ) : '';
				$canvas_data = isset( $_REQUEST[ 'canvas_data' ] ) ? wc_clean( $_REQUEST[ 'canvas_data' ] ) : '';
				if ( empty( $canvas_data ) || empty( $image_url ) ) {
					throw new exception( __( 'Cannot complete action, invalid details found', 'product-designer-for-woocommerce' ) );
				}

				$quantity        = isset( $_REQUEST[ 'quantity' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'quantity' ] ) ) : 1;
				$price           = isset( $_REQUEST[ 'price' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'price' ] ) ) : $product->get_price();
				$product_base_id = isset( $_REQUEST[ 'product_base' ] ) ? absint( $_REQUEST[ 'product_base' ] ) : '';

				$shapes        = isset( $_REQUEST[ 'shapes' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'shapes' ] ) ) : '';
				$cliparts      = isset( $_REQUEST[ 'cliparts' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cliparts' ] ) ) : '';
				$text          = isset( $_REQUEST[ 'text' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'text' ] ) ) : '';
				$data          = isset( $_REQUEST[ 'data' ] ) ? wc_clean( $_REQUEST[ 'data' ] ) : '';
				$store_data_in = isset( $_REQUEST[ 'store_data_in' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'store_data_in' ] ) ) : 'server-side';
				$filename      = isset( $_REQUEST[ 'file_name' ] ) && ! empty( $_REQUEST[ 'file_name' ] ) ? wc_clean( $_REQUEST[ 'file_name' ] ) : uniqid();

				//for image
				$filename_image = $filename . '-image';
				$cart_item_data = array(
					'pdr_product_designer' => array(
						'product_id'      => $product_id,
						'price'           => floatval( $price ) / $quantity,
						'product_base_id' => $product_base_id,
						'product_base'    => ( array ) get_post_meta( $product_base_id, 'pdr_rules', true ),
						'bg'              => $bg,
						'img_url'         => $filename_image, // to optimise load it from server
						'canvas_data'     => $filename,
						'shapes'          => $shapes,
						'cliparts'        => $cliparts,
						'text'            => $text,
						'qty'             => $quantity,
						'data'            => $data,
					),
				);

				WC()->session->set( 'pdr_session_item_' . $product_id, $cart_item_data );

				$add_to_session = WC()->session->get( 'pdr_session_item_' . $product_id );
				if ( ! $add_to_session ) {
					throw new exception( __( 'Sorry, unable to add product to session storage', 'product-designer-for-woocommerce' ) );
				}

				if ( 'server-side' === $store_data_in ) {
					$file_api = new PDR_File( $canvas_data, $filename );
					$file_api->update();

					$file_api = new PDR_File( $image_url, $filename_image );
					$file_api->update();
				}

				$product_obj = wc_get_product( $product_id );
				wp_send_json_success( array( 'url' => $product_obj->get_permalink(), 'canvas_filename' => $filename, 'image_url_filename' => $filename_image, 'item_key' => $product_id ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => esc_html( $e->getMessage() ) ) );
			}
		}

		// save design
		public static function save_design() {
			$id = 0;
			check_ajax_referer( 'pdr-save-design-nonce', 'pdr_security' );
			try {
				$user_id         = isset( $_POST[ 'user_id' ] ) ? absint( $_POST[ 'user_id' ] ) : '';
				$canvas_data     = isset( $_POST[ 'canvas_data' ] ) ? wc_clean( $_POST[ 'canvas_data' ] ) : '';
				$screenshot      = isset( $_POST[ 'screenshot' ] ) ? wc_clean( wp_unslash( $_POST[ 'screenshot' ] ) ) : '';
				$product_id      = isset( $_POST[ 'product_id' ] ) ? absint( $_POST[ 'product_id' ] ) : '';
				$product_base_id = isset( $_POST[ 'product_base' ] ) ? absint( $_POST[ 'product_base' ] ) : '';

				$design_api = new PDR_My_Designs( $user_id, $canvas_data );
				$id         = $design_api->insert_design();
				update_post_meta( $id, 'pdr_my_design_preview', $screenshot );
				update_post_meta( $id, 'pdr_my_design_product_id', $product_id );
				update_post_meta( $id, 'pdr_my_design_product_base_id', $product_base_id );

				wp_send_json_success( array( 'id' => $id, 'screenshot' => $screenshot ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

		public static function load_design() {
			check_ajax_referer( 'pdr-save-design-nonce', 'pdr_security' );
			try {
				$user_id   = isset( $_POST[ 'user_id' ] ) ? absint( $_POST[ 'user_id' ] ) : '';
				$design_id = isset( $_POST[ 'design_id' ] ) ? absint( $_POST[ 'design_id' ] ) : '';

				$design_api = new PDR_My_Designs( $user_id );
				$design     = $design_api->fetch_design( $design_id );
				wp_send_json_success( array( 'design' => $design ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function delete_design() {
			check_ajax_referer( 'pdr-save-design-nonce', 'pdr_security' );
			try {
				$user_id   = isset( $_POST[ 'user_id' ] ) ? absint( $_POST[ 'user_id' ] ) : '';
				$design_id = isset( $_POST[ 'design_id' ] ) ? absint( $_POST[ 'design_id' ] ) : '';

				$design_api = new PDR_My_Designs( $user_id );
				$design     = $design_api->fetch_design( $design_id );
				if ( $design ) {
					//if found
					wp_delete_post( $design->ID, true );
				}
				wp_send_json_success( array( 'deleted' => true ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_category_products() {
			check_ajax_referer( 'pdr-popup-product-base', 'pdr_security' );
			try {
				$tax_id = isset( $_POST[ 'tax_id' ] ) ? absint( $_POST[ 'tax_id' ] ) : ''; //if 0 it will be all categories

				$term_obj     = new PDR_Taxonomy_API( $tax_id );
				$product_data = $term_obj->get_category_product_base_ids();
				wp_send_json_success( array( 'products' => $product_data ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_base_products() {
			check_ajax_referer( 'pdr-popup-product-base', 'pdr_security' );
			$product_bases = array();
			try {
				//get the product ids
				$baseid = isset( $_POST[ 'baseid' ] ) ? absint( $_POST[ 'baseid' ] ) : false;
				if ( $baseid ) {
					$product_base_obj = pdr_get_product_base( $baseid );
					$get_ids          = ( array ) $product_base_obj->get_product_ids();
					if ( is_array( $get_ids ) && ! empty( $get_ids ) ) {
						foreach ( $get_ids as $product_id ) {
							$prodobj = wc_get_product( $product_id );
							if ( $prodobj ) {
								$url                          = get_permalink( get_option( 'pdr_product_designer_page_id' ) );
								$url                          = esc_url_raw( add_query_arg( array( 'product_id' => $product_id, 'pdr_id' => $baseid ), $url ) );
								$product_bases[ $product_id ] = array( 'title' => $prodobj->get_name(), 'url' => $url, 'preview' => wp_get_attachment_url( $prodobj->get_image_id() ), 'pdr_id' => $baseid );
							}
						}
					}
				}
				wp_send_json_success( array( 'products' => $product_bases ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_category_cliparts() {
			check_ajax_referer( 'pdr-cliparts-nonce', 'pdr_security' );
			try {
				$tax_id = isset( $_POST[ 'tax_id' ] ) ? absint( $_POST[ 'tax_id' ] ) : ''; //if 0 it will be all categories

				$term_obj             = new PDR_Taxonomy_API( $tax_id );
				$cliparts             = $term_obj->get_category_cliparts_ids();
				$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
				ob_start();
				$obj                  = new PDR_Template( 'html-cliparts.php', array( 'cliparts' => $cliparts, 'hide_price_label' => $hide_price_when_zero ) );
				$obj->get_template();
				$clipart_data         = ob_get_clean();
				wp_send_json_success( array( 'cliparts' => $clipart_data ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_category_shapes() {
			check_ajax_referer( 'pdr-shapes-nonce', 'pdr_security' );
			try {
				$tax_id = isset( $_POST[ 'tax_id' ] ) ? absint( $_POST[ 'tax_id' ] ) : ''; //if 0 it will be all categories

				$term_obj             = new PDR_Taxonomy_API( $tax_id );
				$shapes               = $term_obj->get_category_shapes_ids();
				$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
				ob_start();
				$obj                  = new PDR_Template( 'html-shapes.php', array( 'shapes' => $shapes, 'hide_price_label' => $hide_price_when_zero ) );
				$obj->get_template();
				$shapes_data          = ob_get_clean();
				wp_send_json_success( array( 'shapes' => $shapes_data ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_category_templates() {
			check_ajax_referer( 'pdr-templates-nonce', 'pdr_security' );
			try {
				$tax_id = isset( $_POST[ 'tax_id' ] ) ? absint( $_POST[ 'tax_id' ] ) : ''; //if 0 it will be all categories

				$term_obj             = new PDR_Taxonomy_API( $tax_id );
				$design_templates     = $term_obj->get_category_template_ids();
				$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
				ob_start();
				$obj                  = new PDR_Template( 'html-templates.php', array( 'design_templates' => $design_templates, 'hide_price_label' => $hide_price_when_zero ) );
				$obj->get_template();
				$templates_data       = ob_get_clean();
				wp_send_json_success( array( 'templates' => $templates_data ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) );
			}
		}

		public static function get_canvas_object() {
			check_ajax_referer( 'pdr-get-canvas-object-nonce', 'pdr_security' );

			$template_id = isset( $_POST[ 'template_id' ] ) ? absint( wp_unslash( $_POST[ 'template_id' ] ) ) : 0;

			if ( $template_id > 0 ) {
				$template = new PDR_Design_Template( $template_id );
				$canvas   = $template->read_canvas_from_file();
			} else {
				$canvas = '';
			}

			wp_send_json( array( 'canvas' => $canvas ) );
		}

		public static function save_design_from_clientdb() {
			check_ajax_referer( 'pdr-save-design-from-clientdb-nonce', 'pdr_security' );

			$posted         = $_POST;
			$designed_carts = isset( $posted[ 'designedCarts' ] ) ? $posted[ 'designedCarts' ] : array();

			if ( ! empty( $designed_carts ) && ! is_null( WC()->cart ) ) {
				foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
					if ( ! isset( $designed_carts[ $cart_item_key ] ) ) {
						continue;
					}

					$file_api = new PDR_File( wc_clean( $designed_carts[ $cart_item_key ][ 'canvas' ] ), wc_clean( wp_unslash( $designed_carts[ $cart_item_key ][ 'canvasFname' ] ) ) );
					$file_api->update();

					$file_api = new PDR_File( wc_clean( wp_unslash( $designed_carts[ $cart_item_key ][ 'imageUrl' ] ) ), wc_clean( wp_unslash( $designed_carts[ $cart_item_key ][ 'imageUrlFname' ] ) ) );
					$file_api->update();
				}
			}

			wp_send_json_success();
		}

		public static function get_my_designs() {
			check_ajax_referer( 'pdr-my-designs-nonce', 'pdr_security' );
			try {
				$user_id = isset( $_POST[ 'user_id' ] ) ? absint( $_POST[ 'user_id' ] ) : '';

				$design_api = new PDR_My_Designs( $user_id );
				$designs    = $design_api->fetch_all_designs();
				wp_send_json_success( array( 'designs' => $designs ) );
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) );
			}
		}

	}

	PDR_Admin_Ajax::init();
}
