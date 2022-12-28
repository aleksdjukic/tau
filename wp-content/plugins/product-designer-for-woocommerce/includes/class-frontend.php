<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PDR_Frontend' ) ) :

	class PDR_Frontend {

		public function __construct() {
			// Return if the product customize is not enabled.
			if ( '1' != get_option( 'pdr_enable_product_customization' ) || ( '1' == get_option( 'pdr_hide_mobile' ) && wp_is_mobile() ) ) {
				return;
			}
			add_action( 'template_redirect', array( $this, 'load_template' ) );
			add_action( 'pdr_frontend_tab_products', array( $this, 'load_product' ), 10, 2 );
			add_action( 'pdr_frontend_tab_cliparts', array( $this, 'load_cliparts' ), 10, 2 );
			add_action( 'pdr_frontend_tab_images', array( $this, 'load_images' ), 10, 2 );
			add_action( 'pdr_frontend_tab_text', array( $this, 'load_text' ), 10, 2 );
			add_action( 'pdr_frontend_tab_shapes', array( $this, 'load_shapes' ), 10, 2 );
			add_action( 'pdr_frontend_tab_templates', array( $this, 'load_templates' ), 10, 2 );
			add_action( 'pdr_extra_details_products', array( $this, 'extra_details_in_product' ), 10, 2 );

			// Define the product customize button hook.
			self::define_single_product_customize_hook();
			self::define_shop_product_customize_hook();

			// display the edited price from session
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'display_price_of_editor' ) );
			//force display mobile view
			//add_filter('wp_is_mobile',array($this,'force_mobile'));
			add_filter( 'pdr_default_font_families', array( $this, 'merge_custom_fonts' ), 10, 1 );
		}

		public function force_mobile() {
			return true;
		}

		/**
		 * Define the single product customize hook.
		 * 
		 * @return void
		 * */
		public static function define_single_product_customize_hook() {
			// Return if the single product customize is not enabled.
			if ( '1' != get_option( 'pdr_enable_customize_button_single_product_page' ) ) {
				return;
			}

			$customize_hook = self::get_single_product_customize_current_location();
			if ( ! pdr_check_is_array( $customize_hook ) ) {
				return;
			}

			// Hook for the product customizer.
			add_action( $customize_hook[ 'hook' ], array( __CLASS__, 'render_product_customize_button' ), $customize_hook[ 'priority' ] );
		}

		/**
		 * Get the single product customize current location.
		 *
		 * @return array.
		 */
		public static function get_single_product_customize_current_location() {

			$product_customize_location = get_option( 'pdr_customize_button_single_product_page_position' );

			$location_details = array(
				'1' => array(
					'hook'     => 'woocommerce_single_product_summary',
					'priority' => 30
				),
				'2' => array(
					'hook'     => 'woocommerce_before_add_to_cart_button',
					'priority' => 10
				),
				'3' => array(
					'hook'     => 'woocommerce_after_add_to_cart_button',
					'priority' => 10
				)
			);

			$location_detail = isset( $location_details[ $product_customize_location ] ) ? $location_details[ $product_customize_location ] : array();

			return $location_detail;
		}

		/**
		 * Define the shop product customize hook.
		 * 
		 * @return void
		 * */
		public static function define_shop_product_customize_hook() {

			// Return if the single product customize is not enabled.
			if ( '1' != get_option( 'pdr_enable_customize_button_other_pages' ) ) {
				return;
			}

			$customize_hook = self::get_shop_product_customize_current_location();
			if ( ! pdr_check_is_array( $customize_hook ) ) {
				return;
			}

			// Hook for the product customizer.
			add_action( $customize_hook[ 'hook' ], array( __CLASS__, 'render_product_customize_button' ), $customize_hook[ 'priority' ] );
		}

		/**
		 * Get the shop product customize current location.
		 *
		 * @return array.
		 */
		public static function get_shop_product_customize_current_location() {

			$product_customize_location = get_option( 'pdr_customize_button_other_page_position' );
			$location_details           = array(
				'1' => array(
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => 8
				),
				'2' => array(
					'hook'     => 'woocommerce_after_shop_loop_item',
					'priority' => 20
				),
			);

			$location_detail = isset( $location_details[ $product_customize_location ] ) ? $location_details[ $product_customize_location ] : array();

			return $location_detail;
		}

		/**
		 * Display the product customize button.
		 * 
		 * @return void
		 */
		public static function render_product_customize_button() {
			global $product;

			if ( ! is_object( $product ) ) {
				return;
			}

			$product_base_ids         = self::get_product_base_ids_by_product_id( $product->get_id() );
			$product_base_ids_checker = self::get_product_base_ids_by_product_ids( $product->get_id() );
			if ( empty( $product_base_ids ) && empty( $product_base_ids_checker ) ) {
				return;
			}

			// Validate the user restriction.
			if ( ! self::validate_user_restriction() ) {
				return;
			}
			$product_base_ids  = is_array( $product_base_ids_checker ) && ! empty( $product_base_ids_checker ) ? $product_base_ids_checker : $product_base_ids;
			$pdr_id            = reset( $product_base_ids );
			$url               = get_permalink( get_option( 'pdr_product_designer_page_id' ) );
			$design_editor_url = esc_url_raw( add_query_arg( array( 'product_id' => $product->get_id(), 'pdr_id' => $pdr_id ), $url ) );
			/**
			 * Alter additional args for button
			 *
			 * @since 1.0
			 */
			$args              = apply_filters( 'pdr_customize_button_args', array(
				'product_id'        => $product->get_id(),
				'pdr_id'            => $pdr_id,
				'design_editor_url' => $design_editor_url
					) );

			$obj = new PDR_Template( 'pdr-button.php', $args );
			$obj->get_template();
		}

		/**
		 * Get the product base IDs by product ID.
		 * 
		 * @return bool
		 */
		public static function get_product_base_ids_by_product_id( $product_id ) {
			$args = array(
				'post_type'    => 'pdr_product_base',
				'post_status'  => 'publish',
				'fields'       => 'ids',
				'meta_key'     => 'pdr_product_ids',
				'meta_value'   => $product_id,
				'meta_compare' => '=='
			);

			return get_posts( $args );
		}

		/**
		 * Get the product base IDs by product ID.
		 * 
		 * @return bool
		 */
		public static function get_product_base_ids_by_product_ids( $product_id ) {
			$args = array(
				'post_type'   => 'pdr_product_base',
				'post_status' => 'publish',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'key'     => 'pdr_product_ids',
						'value'   => serialize( strval( $product_id ) ),
						'compare' => 'LIKE'
					)
				) );
			return get_posts( $args );
		}

		/**
		 * Validate the user restriction.
		 * 
		 * @return bool
		 */
		public static function validate_user_restriction() {
			$return           = true;
			$user_restriction = get_option( 'pdr_user_restrictions' );
			$user             = wp_get_current_user();

			switch ( $user_restriction ) {

				case '2':
					// Include user restriction.
					$include_users = get_option( 'pdr_include_users' );

					if ( ! in_array( $user->ID, $include_users ) ) {
						$return = false;
					}
					break;
				case '3':
					// Exclude user restriction.
					$exclude_users = get_option( 'pdr_exclude_users' );
					if ( in_array( $user->ID, $exclude_users ) ) {
						$return = false;
					}

					break;
				case '4':
					// Include user roles restriction.
					$return = false;
					if ( pdr_check_is_array( $user->roles ) ) {
						$include_user_roles = get_option( 'pdr_include_user_roles' );
						foreach ( $user->roles as $role ) {
							if ( in_array( $role, $include_user_roles ) ) {
								$return = true;
							}
						}
					}

					break;
				case '5':
					// Exclude user roles restriction.
					if ( pdr_check_is_array( $user->roles ) ) {
						$exclude_user_roles = get_option( 'pdr_exclude_user_roles' );
						foreach ( $user->roles as $role ) {
							if ( in_array( $role, $exclude_user_roles ) ) {
								$return = false;
							}
						}
					}
					break;
			}
			/**
			 * Alter user restriction to validate
			 *
			 * @since 1.0
			 */
			return apply_filters( 'pdr_validate_user_restriction', $return );
		}

		public function merge_custom_fonts( $font_families ) {
			$get_custom_fonts = pdr_get_custom_fonts();
			if ( $get_custom_fonts ) {
				foreach ( $get_custom_fonts as $each_font => $font_path ) {
					$font_families[ $each_font ] = $each_font;
				}
			}
			return $font_families;
		}

		public function load_template() {
			$page_id = get_option( 'pdr_product_designer_page_id' );
			if ( is_page( $page_id ) ) {
				$pdr_id           = isset( $_REQUEST[ 'pdr_id' ] ) && $_REQUEST[ 'pdr_id' ] > 0 ? absint( $_REQUEST[ 'pdr_id' ] ) : '';
				$product_id       = isset( $_REQUEST[ 'product_id' ] ) && $_REQUEST[ 'product_id' ] > 0 ? absint( $_REQUEST[ 'product_id' ] ) : '';
				//$data = $product_id ? WC()->session->get('pdr_session_item_'.$product_id) : '';
				$cart_item        = isset( $_REQUEST[ 'cart_item' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_item' ] ) ) : '';
				//$cart_item = ''==$cart_item?$data:$cart_item;
				$get_product      = wc_get_product( $product_id );
				$get_price        = $get_product ? wc_get_price_to_display( $get_product ) : 0;
				$api              = new PDR_API( $pdr_id );
				$get_default_font = $api->get_default_fonts();
				$get_google_font  = $api->get_google_fonts();
				$args             = array( 'pdr_id' => $pdr_id, 'product_id' => $product_id, 'cart_item' => $cart_item, 'get_price' => $get_price, 'hide_add_to_cart' => get_option( 'pdr_hide_add_to_cart' ), 'show_redirect_single_product_btn' => get_option( 'pdr_show_single_product_redirect' ), 'redirect_to_single_pp_label' => get_option( 'pdr_designer_redirect_single_pp_label', 'Redirect with Changes to Product Page' ), 'default_fonts' => $get_default_font, 'google_fonts' => $get_google_font );
				if ( wp_is_mobile() ) {
					$obj = new PDR_Template( 'frontend-mobile-view.php', $args );
				} else {
					$obj = new PDR_Template( 'frontend-ui.php', $args );
				}
				$obj->get_template();
				die();
			}
		}

		public function load_product( $pdr_id, $product_id ) {
			//fetch product details here
			$get_product          = wc_get_product( $product_id );
			$product_name         = $get_product ? $get_product->get_name() : '';
			$product_description  = $get_product ? $get_product->get_description() : '';
			$price_html           = $get_product ? wc_price( wc_get_price_to_display( $get_product ) ) : false;
			$get_price            = $get_product ? wc_get_price_to_display( $get_product ) : 0;
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$attributes           = get_post_meta( $pdr_id, 'pdr_attributes', true );
			$obj                  = new PDR_Template( 'product.php', array( 'pdr_id' => $pdr_id, 'price_html' => $price_html, 'get_price' => $get_price, 'product_name' => $product_name, 'description' => $product_description, 'get_product' => $get_product, 'attributes' => $attributes, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function load_cliparts( $pdr_id, $product_id ) {
			//default cliparts
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$clip                 = new PDR_API( $pdr_id, $product_id );
			$cliparts             = $clip->get_cliparts();
			$obj                  = new PDR_Template( 'cliparts.php', array( 'product_id' => $product_id, 'pdr_id' => $pdr_id, 'cliparts' => $cliparts, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function load_images( $pdr_id, $product_id ) {
			//$image = new PDR_API( $pdr_id , $product_id ) ;
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$price                = get_option( 'pdr_image_price', 0 );
			$obj                  = new PDR_Template( 'images.php', array( 'product_id' => $product_id, 'pdr_id' => $pdr_id, 'price' => $price, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function load_shapes( $pdr_id, $product_id ) {
			//echo "This is for shapes";
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$api                  = new PDR_API( $pdr_id, $product_id );
			$shapes               = $api->get_shapes();
			$obj                  = new PDR_Template( 'shapes.php', array( 'product_id' => $product_id, 'pdr_id' => $pdr_id, 'shapes' => $shapes, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function load_my_designs( $pdr_id, $product_id ) {
			$user_id = get_current_user_id();
			if ( $user_id > 0 ) {
				$design_api = new PDR_My_Designs( $user_id );
				$designs    = $design_api->fetch_all_designs();
				$obj        = new PDR_Template( 'my-designs.php', array( 'product_id' => $product_id, 'pdr_id' => $pdr_id, 'designs' => $designs ) );
				$obj->get_template();
			}
		}

		public function load_templates( $pdr_id, $product_id ) {
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$api                  = new PDR_API( $pdr_id, $product_id );
			$templates            = $api->get_templates();
			$obj                  = new PDR_Template( 'templates.php', array( 'product_id' => $product_id, 'pdr_id' => $pdr_id, 'design_templates' => $templates, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function load_text( $pdr_id, $product_id ) {
			$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );
			$text_price           = ( '1' == get_option( 'pdr_text_character_fee_enabled' ) ) ? floatval( get_option( 'pdr_text_character_fee' ) ) : 0;
			$text_char_count      = get_option( 'pdr_text_character_count' );
			$text_char_count      = $text_char_count ? $text_char_count : 1;
			$price                = $text_price / $text_char_count;
			$price_html           = esc_html__( 'Price per Character = ', 'product-designer-for-woocommerce' );
			$price_html           = $price_html . wc_price( $price );
			$obj                  = new PDR_Template( 'text.php', array( 'price_html' => $price_html, 'price' => $price, 'hide_price_label' => $hide_price_when_zero ) );
			$obj->get_template();
		}

		public function display_price_of_editor() {
			global $product;
			if ( $product->is_type( 'simple' ) ) {
				$get_session = WC()->session->get( 'pdr_session_item_' . $product->get_id() );
				if ( $get_session && isset( $get_session[ 'pdr_product_designer' ] ) ) {
					$price            = $get_session[ 'pdr_product_designer' ][ 'price' ];
					$get_editor_price = $price - wc_get_price_to_display( $product );
					$display_message  = get_option( 'pdr_designer_product_page_label', '+ Designing Price: {{price}}' );
					$display_message  = str_replace( array( '{{price}}' ), array( wc_price( $get_editor_price ) ), $display_message );
					echo do_shortcode( wc_clean( $display_message ) );
				}
			}
		}

	}

	new PDR_Frontend();
endif;
