<?php

/**
 * Plugin Name: Product Designer for WooCommerce
 * Description: Product Designer for WooCommerce allows your users to design the products as per their preference and purchase them in your Shop.
 * Version: 4.3
 * Author: FantasticPlugins
 * Author URI: https://fantasticplugins.com
 * Woo: 6809003:8e8f45e0f8b6af8e9617c615962a0632
 * Text Domain: product-designer-for-woocommerce
 * Domain Path: /languages
 * Tested up to: 6.1.1
 * WC tested up to: 7.1.0
 * WC requires at least: 3.8
 * Copyright: © 2022 FantasticPlugins
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; //Direct Access to the File is Prohibited
}

if ( ! class_exists( 'PDR_For_WooCommerce' ) ) :

	/**
	 * Class for PDR
	 *
	 * @since 1.0
	 */
	class PDR_For_WooCommerce {

		/**
		 * Version.
		 * 
		 * @var string 
		 */
		private $version = '4.3';

		/**
		 * Instance.
		 *
		 * @var object 
		 */
		protected static $_instance = null;

		/**
		 * Load PDR_For_WooCommerce Class in Single Instance.
		 * 
		 * @return object
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Cloning has been forbidden.
		 * 
		 * @return error
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, 'Sorry, you are not allowed to perform this action!!!', esc_html( $this->version ) );
		}

		/**
		 * Unserialize the class data has been forbidden.
		 * 
		 * @return error
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, 'Sorry, you are not allowed to perform this action !!!', esc_html( $this->version ) );
		}

		/**
		 * Constructor of Class
		 */
		public function __construct() {
			$this->define_constants();
			$this->include_files();
			add_action( 'pdr_enqueue_styles', array( $this, 'enqueue_designer_page_styles' ) );
			add_action( 'pdr_enqueue_scripts', array( $this, 'enqueue_designer_page_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'plugins_loaded', array( $this, 'load_translate' ) );
			register_activation_hook( __FILE__, array( $this, 'register_activation_hook' ) );
			add_filter( 'wp_check_filetype_and_ext', array( $this, 'add_font_type_media_support' ), 10, 5 );
			add_filter( 'upload_mimes', array( $this, 'allow_font_type_media_support' ), 10, 1 );
		}

		/**
		 * Include Files
		 */
		public function include_files() {
			include 'vendor/claviska/SimpleImage.php';
			include 'includes/class-pdr-api.php';
			include 'includes/class-pdr-taxonomy-api.php';
			include 'includes/functions.php';
			include 'includes/class-pdr-pages.php';
			include 'includes/class-pdr-install.php';
			include 'includes/pdr-template-functions.php';
			include 'includes/admin/class-pdr-settings-page.php';
			include 'includes/admin/class-admin-menu.php';
			include 'includes/admin/class-dashboard.php';
			include 'includes/admin/class-cpt.php';
			include 'includes/admin/class-pdr-product-settings.php';
			include 'includes/admin/class-pdr-admin-ajax.php';
			include 'includes/admin/class-pdr-meta-boxes.php';

			include 'includes/class-template.php';
			include 'includes/class-frontend.php';
			include 'includes/class-pdr-order-handler.php';
			include 'includes/frontend/class-pdr-cart.php';
			include 'includes/class-google-fonts.php';
			include 'includes/class-rest-api.php';
			include 'includes/class-web-hook.php';

			// Post tables
			include 'includes/admin/post-tables/class-pdr-order-list-table.php';
			include 'includes/admin/post-tables/class-pdr-clipart-list-table.php';
			include 'includes/admin/post-tables/class-pdr-shape-list-table.php';
			include 'includes/admin/post-tables/class-pdr-product-base-list-table.php';
			include 'includes/admin/post-tables/class-pdr-design-template-list-table.php';

			//Abstract.
			include 'includes/abstracts/abstract-pdr-post.php';

			//Entity.
			include 'includes/entity/class-pdr-order.php';
			include 'includes/entity/class-pdr-shape.php';
			include 'includes/entity/class-pdr-product-base.php';
			include 'includes/entity/class-pdr-clipart.php';
			include 'includes/entity/class-pdr-design-template.php';

			//File Management
			include 'includes/class-file-management.php';
			//My Designs
			include 'includes/class-my-designs.php';

			//import functionality
			include 'includes/import/class-import.php';
			//privacy policy
			include 'includes/privacy/class-pdr-privacy.php';
		}

		public function enqueue_designer_page_styles() {
			$get_theme_selection = 'custom' != get_option( 'pdr_theme_selection', 'default' ) && 'default' != get_option( 'pdr_theme_selection', 'default' ) ? '-' . get_option( 'pdr_theme_selection' ) : '';
			pdr_enqueue_style( 'pdr_semantic_css', PDR_PLUGIN_PATH . 'assets/lib/semantic-ui/dist/semantic' . $get_theme_selection . '.min.css', array(), $this->version );
			pdr_enqueue_style( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.css', array(), $this->version );
			pdr_enqueue_style( 'pdr_print_css', PDR_PLUGIN_PATH . 'assets/lib/print/print.min.css', array(), $this->version );
			pdr_enqueue_style( 'pdr_css', PDR_PLUGIN_PATH . 'assets/css/pdr.css', array(), $this->version );
			pdr_enqueue_style( 'pdr_css', PDR_PLUGIN_PATH . 'assets/css/template-color.css', array(), $this->version );
			if ( wp_is_mobile() ) {
				pdr_enqueue_style( 'pdr_mobile_css', PDR_PLUGIN_PATH . 'assets/css/styles.css', array(), $this->version );
			}
			//load google font
			$inline_google_fonts = '';
			$array_fonts         = pdr_get_google_fonts_in_canvas();
			if ( $array_fonts && is_array( $array_fonts ) ) {
				foreach ( $array_fonts as $each_font ) {
					$trim_data           = str_replace( ' ', '', strtolower( $each_font ) );
					$format_font_name    = str_replace( array( ' ' ), array( '+' ), $each_font );
					pdr_enqueue_style( $format_font_name, "https://fonts.googleapis.com/css?family=$format_font_name" );
					$inline_google_fonts .= ".pdr_$trim_data{
    font-family: '" . $each_font . "';
        font-size: 0;
    position: absolute;
    visibility: hidden;
}
";
				}
				pdr_add_inline_style( 'pdr_inline_google', $inline_google_fonts );
			}

			$get_custom_fonts = pdr_get_custom_fonts();
			$load_custom_font = '';
			if ( is_array( $get_custom_fonts ) && ! empty( $get_custom_fonts ) ) {
				foreach ( $get_custom_fonts as $each_font => $path ) {
					$load_custom_font .= "@font-face {  font-family: '$each_font';  src: url($path);}";
				}
			}


			pdr_add_inline_style( 'pdr_inline_custom_font', $load_custom_font );

			if ( 'custom' == get_option( 'pdr_theme_selection', 'default' ) ) {
				$header_bg_color  = get_option( 'pdr_theme_header_bg_color' );
				$footer_bg_color  = get_option( 'pdr_theme_footer_bg_color' );
				$theme_font_color = get_option( 'pdr_theme_font_color' );
				$button_bg_color  = get_option( 'pdr_theme_button_bg_color' );
				$button_color     = get_option( 'pdr_theme_button_font_color' );
				$custom_css       = ".ui.borderless.sticky.stackable.menu {
background: $header_bg_color !important;
}
.ui.footer.segment {
background: $footer_bg_color !important;
}

.item, label, .dropdown, .footer, body {
	color: $theme_font_color !important;
}

.ui.primary.button, .ui.secondary.button, button {
color: $button_color !important;
background: $button_bg_color !important;
}";
				pdr_add_inline_style( 'pdr_custom_theme_css', $custom_css );
			}
			pdr_add_inline_style( 'pdr_inline_css', get_option( 'pdr_custom_css' ) );
		}

		public function enqueue_frontend_scripts() {
			// Alertable
			wp_enqueue_style( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.css', array(), $this->version );
			wp_enqueue_script( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.min.js', array( 'jquery' ), $this->version );

			wp_enqueue_script( 'pdr_clientdb', PDR_PLUGIN_PATH . 'assets/js/client-db.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'pdr_product', PDR_PLUGIN_PATH . '/assets/js/frontend.js', array( 'jquery', 'jquery-blockui', 'jquery_alertable', 'pdr_clientdb' ), $this->version );
			wp_localize_script(
					'pdr_product',
					'pdr_product_params',
					array(
						'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
						'user_id'                         => get_current_user_id(),
						'is_cart_empty'                   => ! is_null( WC()->cart ) && WC()->cart->is_empty() ? true : false,
						'force_guest'                     => get_option( 'pdr_force_user_login' ),
						'force_guest_alert_message'       => pdr_shortcodes( get_option( 'pdr_designer_force_login_message' ) ),
						'save_design_from_clientdb_nonce' => wp_create_nonce( 'pdr-save-design-from-clientdb-nonce' ),
					)
			);

			wp_register_style( 'pdr-inline-style', false, array(), 'product-designer-for-woocommerce' ); // phpcs:ignore
			wp_enqueue_style( 'pdr-inline-style' );
			//Add custom css as inline style.
			wp_add_inline_style( 'pdr-inline-style', get_option( 'pdr_custom_css' ) );
		}

		/**
		 * Frontend Enqueue Script
		 */
		public function enqueue_designer_page_scripts() {
			pdr_enqueue_script( 'pdr_jquery', PDR_PLUGIN_PATH . 'assets/lib/jquery/jquery.min.js', array(), $this->version );
			pdr_enqueue_script( 'pdr_clientdb', PDR_PLUGIN_PATH . 'assets/js/client-db.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_fabricjs', PDR_PLUGIN_PATH . 'assets/lib/fabric/fabric.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_fabric_center', PDR_PLUGIN_PATH . 'assets/lib/fabric/centering_guidelines.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_fabric_align', PDR_PLUGIN_PATH . 'assets/lib/fabric/aligning_guidelines.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_webfont', PDR_PLUGIN_PATH . 'assets/lib/webfontloader/webfont.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_fontloader', PDR_PLUGIN_PATH . 'assets/lib/webfontloader/fontfaceonload.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.min.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_print_js', PDR_PLUGIN_PATH . 'assets/lib/print/print.min.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.1.1/jspdf.umd.min.js', array( 'jquery' ), $this->version );
			//https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js
			pdr_enqueue_script( 'pdr_pdf', PDR_PLUGIN_PATH . 'assets/lib/pdf/pdf.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_dpi', PDR_PLUGIN_PATH . 'assets/lib/dpi/index.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_watermark', PDR_PLUGIN_PATH . 'assets/lib/watermark/watermark.min.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_qr', PDR_PLUGIN_PATH . 'assets/lib/qr/qrcode.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.js', array( 'jquery', 'pdr_fabricjs' ), $this->version );
			pdr_enqueue_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting.js', array( 'jquery' ), $this->version );
			$cart_id    = isset( $_REQUEST[ 'cart_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_id' ] ) ) : '';
			$cart_item  = isset( $_REQUEST[ 'cart_item' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_item' ] ) ) : '';
			$order_key  = isset( $_REQUEST[ 'order_key' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'order_key' ] ) ) : '';
			$order_id   = isset( $_REQUEST[ 'order_id' ] ) ? absint( $_REQUEST[ 'order_id' ] ) : '';
			$product_id = isset( $_REQUEST[ 'product_id' ] ) && $_REQUEST[ 'product_id' ] > 0 ? absint( $_REQUEST[ 'product_id' ] ) : '';
			$data       = $product_id ? WC()->session->get( 'pdr_session_item_' . $product_id ) : '';

			$cart_item_key  = $cart_item;
			$get_session_id = $data && isset( $data[ 'pdr_product_designer' ][ 'canvas_data' ] ) ? $data[ 'pdr_product_designer' ][ 'canvas_data' ] : '';
			$cart_id        = '' == $cart_id ? $get_session_id : $cart_id;
			$file_api       = new PDR_File( '', $cart_id );
			$object_content = $file_api->retrieve();
			$cart_item      = '' != $cart_item ? WC()->cart->get_cart_item( $cart_item ) : false;
			$cart_item      = ! $cart_item ? $data : $cart_item;
			$my_design      = isset( $_REQUEST[ 'my_design' ] ) ? absint( $_REQUEST[ 'my_design' ] ) : '';
			$form_data      = '';
			//if my designs found load data
			if ( $my_design ) {
				$get_post_content = new PDR_My_Designs( get_current_user_id() );
				$get_post_content = $get_post_content->fetch_design_content( $my_design );
				if ( $get_post_content ) {
					$object_content = wp_slash( $get_post_content );
				}
			}

			if ( $cart_item ) {
				$form_data = $cart_item[ 'pdr_product_designer' ][ 'data' ];
			}

			if ( isset( $_REQUEST[ 'order_key' ] ) && isset( $_REQUEST[ 'order_id' ] ) ) {
				if ( pdr_fetch_order_masterdata( $order_key, $order_id ) ) {
					$object_content = pdr_fetch_order_masterdata( $order_key, $order_id );
					$wcorder_id     = get_post_meta( $order_id, 'pdr_wc_order_id', true );
					$form_data      = get_post_meta( $order_id, 'pdr_data', true );
				}
			}
			$object_content = wp_unslash( $object_content );
			$object_content = json_decode( $object_content, true );
			$form_data      = wp_unslash( $form_data );
			$form_data      = json_decode( $form_data, true, JSON_UNESCAPED_SLASHES );
			$object_content = '' != $object_content ? $object_content : '';
			pdr_localize_script(
					'pdr_frontend',
					array(
						'ajaxurl'                       => admin_url( 'admin-ajax.php' ),
						'file_name'                     => $cart_id,
						'add_to_cart_nonce'             => wp_create_nonce( 'pdr-add-cart-nonce' ),
						'save_design_nonce'             => wp_create_nonce( 'pdr-save-design-nonce' ),
						'popup_product_base'            => wp_create_nonce( 'pdr-popup-product-base' ),
						'my_designs_nonce'              => wp_create_nonce( 'pdr-my-designs-nonce' ),
						'fetch_cliparts_nonce'          => wp_create_nonce( 'pdr-cliparts-nonce' ),
						'fetch_templates_nonce'         => wp_create_nonce( 'pdr-templates-nonce' ),
						'fetch_shapes_nonce'            => wp_create_nonce( 'pdr-shapes-nonce' ),
						'get_canvas_object_nonce'       => wp_create_nonce( 'pdr-get-canvas-object-nonce' ),
						'is_mobile'                     => wp_is_mobile(),
						'is_contain_pdr'                => ( isset( $_REQUEST[ 'pdr_id' ] ) && isset( $_REQUEST[ 'product_id' ] ) ) || ( ( isset( $_REQUEST[ 'order_key' ] ) ) && isset( $_REQUEST[ 'order_id' ] ) ) ? true : false,
						'attributes_validation'         => isset( $_REQUEST[ 'pdr_id' ] ) && $_REQUEST[ 'pdr_id' ] > 0 ? pdr_attributes_and_types( intval( $_REQUEST[ 'pdr_id' ] ) ) : '',
						'is_user_logged_in'             => is_user_logged_in() ? true : false,
						'is_order'                      => ( ( isset( $_REQUEST[ 'order_key' ] ) ) && isset( $_REQUEST[ 'order_id' ] ) ) ? true : false,
						'dataurl_to_blob'               => get_option( 'pdr_canvas_blob' ),
						'is_watermark_enabled'          => get_option( 'pdr_canvas_watermark' ),
						'watermark_content'             => get_option( 'pdr_watermark_content' ),
						'watermark_fontsize'            => get_option( 'pdr_watermark_fontsize' ),
						'watermark_fontcolor'           => get_option( 'pdr_watermark_fontcolor' ),
						'user_id'                       => get_current_user_id(),
						'force_guest'                   => get_option( 'pdr_force_user_login' ),
						'force_guest_alert_message'     => pdr_shortcodes( get_option( 'pdr_designer_force_login_message' ) ),
						'qr_input_text_error_msg'       => get_option( 'pdr_designer_qr_input_text_empty', 'QR Input Text cannot be empty' ),
						'currency_symbol'               => get_woocommerce_currency_symbol(),
						'currency_num_decimals'         => wc_get_price_decimals(),
						'currency_decimal_sep'          => esc_attr( wc_get_price_decimal_separator() ),
						'currency_thousand_sep'         => esc_attr( wc_get_price_thousand_separator() ),
						'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
						'pdr_rules'                     => isset( $_REQUEST[ 'pdr_id' ] ) ? pdr_get_product_base_rules( intval( $_REQUEST[ 'pdr_id' ] ) ) : array(),
						'image_segmentation'            => isset( $_REQUEST[ 'pdr_id' ] ) ? ( get_post_meta( intval( $_REQUEST[ 'pdr_id' ] ), 'pdr_image_segmentation', true ) ) : 'overlay',
						'master_data'                   => $object_content,
						'form_data'                     => $form_data,
						'cart_item_key'                 => $cart_item_key,
						'product_added_msg'             => get_option( 'pdr_designer_product_added_msg' ),
						'design_saved_msg'              => get_option( 'pdr_designer_design_saved_msg' ),
						'design_deleted_msg'            => get_option( 'pdr_designer_design_deleted_msg' ),
						'no_design_msg'                 => pdr_get_designer_no_designs_message(),
						'text_char_count'               => get_option( 'pdr_text_character_count' ),
						'text_char_fee'                 => ( '1' == get_option( 'pdr_text_character_fee_enabled' ) ) ? floatval( get_option( 'pdr_text_character_fee' ) ) : 0,
						'image_upload_force_user'       => get_option( 'pdr_image_upload_force_user_login' ),
						'image_min_size'                => floatval( get_option( 'pdr_image_upload_min_size' ) ),
						'image_max_size'                => floatval( get_option( 'pdr_image_upload_max_size' ) ),
						'image_min_dimension'           => array_filter( explode( 'x', ( string ) get_option( 'pdr_image_min_dimension' ) ) ),
						'image_max_dimension'           => array_filter( explode( 'x', ( string ) get_option( 'pdr_image_max_dimension' ) ) ),
						'image_valid_ext'               => array_filter( explode( ',', str_replace( ' ', '', ( string ) get_option( 'pdr_image_allowed_types' ) ) ) ),
						'image_guest_alert_msg'         => pdr_shortcodes( get_option( 'pdr_designer_force_login_message' ) ),
						'image_ext_error_msg'           => __( 'Image Type not supported.', 'product-designer-for-woocommerce' ),
						/* translators: %s: image minimum size */
						'image_min_size_error_msg'      => __( 'Image size should be atleast %s Kb', 'product-designer-for-woocommerce' ),
						/* translators: %s: image maximum size */
						'image_max_size_error_msg'      => __( 'Image size cannot be more than %s Kb', 'product-designer-for-woocommerce' ),
						/* translators: %s: image minimum dimension */
						'image_min_dimension_error_msg' => __( 'Min Dimensions for Image are %s', 'product-designer-for-woocommerce' ),
						/* translators: %s: image maximum dimension */
						'image_max_dimension_error_msg' => __( 'Max Dimensions for Image are %s', 'product-designer-for-woocommerce' ),
						'choose_product_base_caption'   => __( 'Choose Product Base', 'product-designer-for-woocommerce' ),
						'choose_product_caption'        => __( 'Choose Product', 'product-designer-for-woocommerce' ),
						'no_attributes_error'           => __( 'Sorry Product Attributes Data required for add to cart', 'product-designer-for-woocommerce' ),
						'product_added_to_session'      => __( 'Product successfully added to session', 'product-designer-for-woocommerce' ),
					)
			);

			pdr_enqueue_script( 'pdr_js', PDR_PLUGIN_PATH . 'assets/js/pdr-dev.js', array( 'jquery', 'pdr_pdf', 'pdr_fabricjs', 'jquery-blockui', 'accounting', 'jquery_alertable' ), $this->version );
			pdr_enqueue_script( 'pdr_semantic_js', PDR_PLUGIN_PATH . 'assets/lib/semantic-ui/dist/semantic.min.js', array( 'jquery' ), $this->version );
			pdr_enqueue_script( 'pdr_hermite', PDR_PLUGIN_PATH . 'assets/lib/hermite/blitz.min.js', array( 'jquery' ), $this->version );
			//pdr_enqueue_script('pdr_mobile_js', PDR_PLUGIN_PATH . 'assets/js/main.js', array('jquery', 'pdr_pdf', 'pdr_fabricjs', 'jquery-blockui', 'accounting', 'jquery_alertable'), $this->version);
		}

		/**
		 * Admin Enqueue Script
		 */
		public function enqueue_admin_script() {
			// Media 
			wp_enqueue_media();

			// Alertable
			wp_enqueue_script( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.min.js', array( 'jquery' ), $this->version );
			// Jcrop
			wp_enqueue_script( 'Jcrop', PDR_PLUGIN_PATH . 'assets/lib/Jcrop/jcrop.js', array( 'jquery' ), $this->version );

			wp_enqueue_script( 'pdr-jcrop', PDR_PLUGIN_PATH . 'assets/js/jcrop-enhanced.js', array( 'jquery' ), $this->version );

			// Admin
			wp_enqueue_script( 'pdr-admin', PDR_PLUGIN_PATH . '/assets/js/admin.js', array( 'jquery', 'jquery-blockui', 'jquery_alertable' ), $this->version );
			wp_localize_script(
					'pdr-admin',
					'pdr_admin_params',
					array(
						'panel_nonce'              => wp_create_nonce( 'pdr-panel-nonce' ),
						'module_nonce'             => wp_create_nonce( 'pdr-module-nonce' ),
						'design_file_upload_error' => esc_html__( 'Upload file must be in jpeg/png/txt file', 'product-designer-for-woocommerce' )
					)
			);

			// Admin
			wp_enqueue_script( 'pdr-wp-media', PDR_PLUGIN_PATH . '/assets/js/wp-media-enhanced.js', array( 'jquery' ), $this->version );
			wp_localize_script(
					'pdr-wp-media',
					'pdr_wp_media_params',
					array(
						'media_title'       => esc_html__( 'Choose Image', 'product-designer-for-woocommerce' ),
						'media_button_text' => esc_html__( 'Use Image', 'product-designer-for-woocommerce' ),
					)
			);

			//select
			wp_enqueue_script( 'pdr-enhanced', PDR_PLUGIN_PATH . '/assets/js/pdr-enhanced.js', array( 'jquery', 'select2' ), $this->version );
			wp_localize_script(
					'pdr-enhanced',
					'pdr_enhanced_params',
					array(
						'i18n_no_matches'           => esc_html_x( 'No matches found', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_input_too_short_1'    => esc_html_x( 'Please enter 1 or more characters', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_input_too_short_n'    => esc_html_x( 'Please enter %qty% or more characters', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_input_too_long_1'     => esc_html_x( 'Please delete 1 character', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_input_too_long_n'     => esc_html_x( 'Please delete %qty% characters', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_selection_too_long_1' => esc_html_x( 'You can only select 1 item', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_selection_too_long_n' => esc_html_x( 'You can only select %qty% items', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_load_more'            => esc_html_x( 'Loading more results&hellip;', 'enhanced select', 'product-designer-for-woocommerce' ),
						'i18n_searching'            => esc_html_x( 'Searching&hellip;', 'enhanced select', 'product-designer-for-woocommerce' ),
						'search_nonce'              => wp_create_nonce( 'pdr-search-nonce' ),
						'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
					)
			);
		}

		/**
		 * Admin Enqueue Styles
		 */
		public function enqueue_admin_styles() {
			// Jcrop
			wp_enqueue_style( 'Jcrop', PDR_PLUGIN_PATH . 'assets/lib/Jcrop/jcrop.css', array(), $this->version );
			// Admin
			wp_enqueue_style( 'pdr-admin', PDR_PLUGIN_PATH . '/assets/css/admin.css', array(), $this->version );

			// Alertable
			wp_enqueue_style( 'jquery_alertable', PDR_PLUGIN_PATH . 'assets/lib/alertable/jquery.alertable.css', array(), $this->version );
		}

		/**
		 * Define Constants
		 */
		public function define_constants() {
			if ( ! defined( 'PDR_PLUGIN_DIR' ) ) {
				define( 'PDR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'PDR_PLUGIN_PATH' ) ) {
				define( 'PDR_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );
			}
			if ( ! defined( 'PDR_PLUGIN_SLUG' ) ) {
				define( 'PDR_PLUGIN_SLUG', plugin_basename( __FILE__ ) );
			}
			if ( ! defined( 'PDR_PLUGIN_FILE' ) ) {
				define( 'PDR_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Register Activation Hook
		 */
		public function register_activation_hook() {
			$upload_dir = wp_get_upload_dir();
			//referred from woocommerce
			$files      = array(
				array(
					'base'    => $upload_dir[ 'basedir' ] . '/pdr',
					'file'    => 'index.html',
					'content' => '',
				),
				array(
					'base'    => $upload_dir[ 'basedir' ] . '/pdr',
					'file'    => '.htaccess',
					'content' => 'deny from all',
				)
			);

			foreach ( $files as $file ) {
				if ( wp_mkdir_p( $file[ 'base' ] ) && ! file_exists( trailingslashit( $file[ 'base' ] ) . $file[ 'file' ] ) ) {
					$file_handle = @fopen( trailingslashit( $file[ 'base' ] ) . $file[ 'file' ], 'wb' ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
					if ( $file_handle ) {
						fwrite( $file_handle, $file[ 'content' ] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
						fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
					}
				}
			}

			PDR_Install::install();
		}

		public function load_translate() {
			$text_domain   = 'product-designer-for-woocommerce';
			$lang_dir      = untrailingslashit( WP_LANG_DIR );
			/**
			 * Change the plugin locale which helps for translation
			 *
			 * @since 1.0
			 */
			$plugin_locale = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			$exists        = load_textdomain( $text_domain, $lang_dir . '/plugins/' . $text_domain . '-' . $plugin_locale . '.mo' );
			if ( $exists ) {
				return $exists;
			} else {
				load_plugin_textdomain( $text_domain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
			}
		}

		/*
		 * Enable Font File Extensions to be Uploadable in Media Library
		 */

		public function add_font_type_media_support( $data, $file, $filename, $mimes, $real_mime ) {
			if ( ! empty( $data[ 'ext' ] ) && ! empty( $data[ 'type' ] ) ) {
				return $data;
			}

			$wp_file_type = wp_check_filetype( $filename, $mimes );

			$font_types = array( 'ttf' => 'font/ttf', 'otf' => 'font/otf', 'woff' => 'font/woff', 'woff2' => 'font/woff2' );

			if ( in_array( $wp_file_type[ 'ext' ], array_keys( $font_types ) ) ) {
				$data[ 'ext' ]  = $wp_file_type[ 'ext' ];
				$data[ 'type' ] = $font_types[ $wp_file_type[ 'ext' ] ];
			}

			return $data;
		}

		public function allow_font_type_media_support( $mimes ) {
			$font_types = array( 'ttf' => 'font/ttf', 'otf' => 'font/otf', 'woff' => 'font/woff', 'woff2' => 'font/woff2' );

			if ( is_array( $font_types ) && ! empty( $font_types ) ) {
				foreach ( $font_types as $font => $font_type ) {
					$mimes[ $font ] = $font_type;
				}
			}
			return $mimes;
		}

	}

	/* Include once will help to avoid fatal error by load the files when you call init hook */
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	/**
	 * Function to check whether WooCommerce is active or not
	 */
	function pdr_maybe_woocommerce_active() {

		if ( is_multisite() ) {
			// This Condition is for Multi Site WooCommerce Installation
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
				if ( is_admin() ) {
					add_action( 'init', 'pdr_display_warning_message' );
				}
				return false;
			}
		} else {
			// This Condition is for Single Site WooCommerce Installation
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				if ( is_admin() ) {
					add_action( 'init', 'pdr_display_warning_message' );
				}
				return false;
			}
		}
		return true;
	}

	/**
	 * Display Warning message
	 */
	function pdr_display_warning_message() {
		echo "<div class='error'><p> Product Designer for WooCommerce Plugin will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
	}

	// retrun if WooCommerce is not active
	if ( ! pdr_maybe_woocommerce_active() ) {
		return;
	}

	function PDR_For_WooCommerce() {
		return PDR_For_WooCommerce::instance();
	}

	/**
	 * Run the Instance of Class
	 */
	PDR_For_WooCommerce();

endif;
