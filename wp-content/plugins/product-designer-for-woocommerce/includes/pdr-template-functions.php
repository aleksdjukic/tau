<?php

/**
 * Template functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'pdr_get_site_title' ) ) {

	/**
	 *  Get the product designer site title.
	 * 
	 * @return string
	 */
	function pdr_get_site_title() {
		/**
		 * Filter to alter site title appear in title tag
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_site_title', get_option( 'pdr_site_title' ) );
	}

}

if ( ! function_exists( 'pdr_get_back_to_shop_url' ) ) {

	/**
	 *  Get the product designer back to shop URL.
	 * 
	 * @return string
	 */
	function pdr_get_back_to_shop_url() {
		$url = get_option( 'pdr_back_to_shop_url' );
		if ( empty( $url ) ) {
			$url = site_url();
		}
		/**
		 * Alter the back to shop url appear in frontend with your own url
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_back_to_shop_url', $url );
	}

}

if ( ! function_exists( 'pdr_get_logo_url' ) ) {

	/**
	 *  Get the product designer logo URL.
	 * 
	 * @return string
	 */
	function pdr_get_logo_url() {
		/**
		 * Logo link can be altered using this filter
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_logo_url', get_option( 'pdr_logo_url' ) );
	}

}

if ( ! function_exists( 'pdr_get_favicon_url' ) ) {

	/**
	 *  Get the product designer favicon URL.
	 * 
	 * @return string
	 */
	function pdr_get_favicon_url() {
		/**
		 * Alter favicon url
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_favicon_url', get_option( 'pdr_favicon_url' ) );
	}

}

if ( ! function_exists( 'pdr_get_footer_content' ) ) {

	/**
	 * Get the footer content for designer page
	 * 
	 * @return string
	 */
	function pdr_get_footer_content() {
		/**
		 * Filter to alter footer content
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_footer_content', get_option( 'pdr_footer_content' ) );
	}

}


if ( ! function_exists( 'pdr_get_customize_product_button_name' ) ) {

	/**
	 *  Get the customize product button name.
	 * 
	 * @return string
	 */
	function pdr_get_customize_product_button_name() {
		/**
		 * Filter to alter customize product button name
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_customize_product_button_name', get_option( 'pdr_customize_button_caption' ) );
	}

}

if ( ! function_exists( 'pdr_get_customize_product_button_classes' ) ) {

	/**
	 *  Get the customize product button classes.
	 * 
	 * @return string
	 */
	function pdr_get_customize_product_button_classes() {
		$classes = array(
			'btn',
			'btn-primary',
			'pdr_customize_button',
			'button'
		);

		$extra_classes = get_option( 'pdr_customize_button_classes' );
		if ( $extra_classes ) {
			$classes = array_merge( $classes, explode( ',', $extra_classes ) );
		}
		/**
		 * Alter button classes with this following filter
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_customize_product_button_classes', implode( ' ', $classes ) );
	}

}

if ( ! function_exists( 'pdr_get_customize_product_menu_items' ) ) {

	/**
	 *  Get the customize product menu items.
	 * 
	 * @return string
	 */
	function pdr_get_customize_product_menu_items() {
		$pdr_menus = array(
			'pdr_products'
		);

		$menu_key = array(
			'pdr_settings_enable_cliparts_module'  => 'pdr_cliparts',
			'pdr_settings_enable_images_module'    => 'pdr_images',
			'pdr_settings_enable_text_module'      => 'pdr_text',
			'pdr_settings_enable_shapes_module'    => 'pdr_shapes',
			'pdr_settings_enable_templates_module' => 'pdr_templates',
		);

		foreach ( $menu_key as $key => $name ) {

			if ( '1' != get_option( $key ) ) {
				continue;
			}

			$pdr_menus[] = $name;
		}

		/**
		 * Filter to add/remove menu available in frontend product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_customize_product_menu_items', $pdr_menus );
	}

}

if ( ! function_exists( 'pdr_show_product_selection_button' ) ) {

	/**
	 * Show the product selection button.
	 * 
	 * @return bool
	 */
	function pdr_show_product_selection_button() {
		/**
		 * Filter to alter product selection button
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_show_product_selection_button', '1' == get_option( 'pdr_settings_enable_product_selection_module' ) );
	}

}

if ( ! function_exists( 'pdr_show_product_description' ) ) {

	/**
	 * Show the product description.
	 * 
	 * @return bool
	 */
	function pdr_show_product_description() {
		/**
		 * Show product description based on this option
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_show_product_description', '1' != get_option( 'pdr_hide_product_description' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_file_label' ) ) {

	/**
	 * Get the product designer file label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_file_label() {
		/**
		 * Alter File Name Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_file_label', get_option( 'pdr_designer_file_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_export_file_label' ) ) {

	/**
	 * Get the product designer export file label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_export_file_label() {
		/**
		 * Alter Export File Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_export_file_label', get_option( 'pdr_designer_export_file_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_export_template_label' ) ) {

	/**
	 * Get the product designer export template label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_export_template_label() {
		/**
		 * Alter Export Template Label for Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_export_template_label', get_option( 'pdr_designer_export_template_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_print_label' ) ) {

	/**
	 * Get the product designer print label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_print_label() {

		/**
		 * Alter Print Label for Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_print_label', get_option( 'pdr_designer_print_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_my_designs_label' ) ) {

	/**
	 * Get the product designer my designs label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_my_designs_label() {
		/**
		 * Alter My Designs Label for Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_my_designs_label', get_option( 'pdr_designer_my_designs_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_add_to_cart_label' ) ) {

	/**
	 * Get the product designer add to cart label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_add_to_cart_label() {

		/**
		 * Alter Add to Cart Label for Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_add_to_cart_label', get_option( 'pdr_designer_add_to_cart_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_update_cart_label' ) ) {

	/**
	 * Get the product designer update cart label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_update_cart_label() {
		/**
		 * Alter Update Cart label for product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_update_cart_label', get_option( 'pdr_designer_update_cart_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_back_to_shop_label' ) ) {

	/**
	 * Get the product designer back to shop label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_back_to_shop_label() {
		/**
		 * Alter Back to Shop Label for Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_back_to_shop_label', get_option( 'pdr_designer_back_to_shop_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_product_menu_label' ) ) {

	/**
	 * Get the product designer product menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_product_menu_label() {
		/**
		 * Alter the product menu label using the below filter
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_product_menu_label', get_option( 'pdr_designer_product_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_product_quantity_label' ) ) {

	/**
	 * Get the product designer product quantity label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_product_quantity_label() {
		/**
		 * Alter the product quantity label 
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_product_quantity_label', get_option( 'pdr_designer_product_quantity_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_choose_product_label' ) ) {

	/**
	 * Get the product designer choose product label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_choose_product_label() {
		/**
		 * Alter the choose product label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_choose_product_label', get_option( 'pdr_designer_choose_product_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_clipart_menu_label' ) ) {

	/**
	 * Get the product designer clipart menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_clipart_menu_label() {
		/**
		 * Alter the clipart menu label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_clipart_menu_label', get_option( 'pdr_designer_clipart_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_image_menu_label' ) ) {

	/**
	 * Get the product designer image menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_image_menu_label() {
		/**
		 * Alter the image menu label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_image_menu_label', get_option( 'pdr_designer_image_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_menu_label' ) ) {

	/**
	 * Get the product designer text menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_menu_label() {
		/**
		 * Alter the text menu label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_menu_label', get_option( 'pdr_designer_text_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_shape_menu_label' ) ) {

	/**
	 * Get the product designer shape menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_shape_menu_label() {
		/**
		 * Alter the shape menu label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_shape_menu_label', get_option( 'pdr_designer_shape_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_template_menu_label' ) ) {

	/**
	 * Get the product designer template menu label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_template_menu_label() {
		/**
		 * Alter the template menu in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_template_menu_label', get_option( 'pdr_designer_template_menu_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_switch_view_label' ) ) {

	/**
	 * Get the product designer switch view label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_switch_view_label() {
		/**
		 * Alter the Switch view label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_switch_view_label', get_option( 'pdr_designer_switch_view_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_save_design_label' ) ) {

	/**
	 * Get the product designer save design label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_save_design_label() {
		/**
		 * Alter save design label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_save_design_label', get_option( 'pdr_designer_save_design_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_tools_label' ) ) {

	/**
	 * Get the product designer text tools label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_tools_label() {
		/**
		 * Alter text tools label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_tools_label', get_option( 'pdr_designer_text_tools_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_object_tools_label' ) ) {

	/**
	 * Get the product designer object tools label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_object_tools_label() {
		/**
		 * Alter Object tools label in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_object_tools_label', get_option( 'pdr_designer_object_tools_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_font_size_label' ) ) {

	/**
	 * Get the product designer text font size label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_font_size_label() {
		/**
		 * Alter Font Size Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_font_size_label', get_option( 'pdr_designer_text_font_size_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_font_family_label' ) ) {

	/**
	 * Get the product designer text font family label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_font_family_label() {
		/**
		 * Alter Font Family Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_font_family_label', get_option( 'pdr_designer_text_font_family_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_select_font_family_label' ) ) {

	/**
	 * Get the product designer text select font family label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_select_font_family_label() {
		/**
		 * Alter Select Font Family Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_select_font_family_label', get_option( 'pdr_designer_text_select_font_family_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_google_fonts_label' ) ) {

	/**
	 * Get the product designer text google fonts label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_google_fonts_label() {
		/**
		 * Alter Google Fonts Label in Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_google_fonts_label', get_option( 'pdr_designer_text_google_fonts_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_font_color_label' ) ) {

	/**
	 * Get the product designer text font color label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_font_color_label() {
		/**
		 * Alter Text Font Color Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_font_color_label', get_option( 'pdr_designer_text_font_color_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_background_color_label' ) ) {

	/**
	 * Get the product designer text background color label
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_background_color_label() {
		/**
		 * Alter Background Color Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_background_color_label', get_option( 'pdr_designer_text_background_color_label', 'Font Background Color' ) );
	}

}


if ( ! function_exists( 'pdr_get_designer_text_mask_label' ) ) {

	/**
	 * Get the product designer text mask label
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_mask_label() {
		/**
		 * Alter Text Mask Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_mask_label', get_option( 'pdr_designer_text_image_mask_label', 'Text Mask Image' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_add_new_label' ) ) {

	/**
	 * Get the product designer text add new label.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_add_new_label() {
		/**
		 * Alter Add New Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_add_new_label', get_option( 'pdr_designer_text_add_new_label' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_text_default_message' ) ) {

	/**
	 * Get the product designer text default message.
	 * 
	 * @return string
	 */
	function pdr_get_designer_text_default_message() {
		/**
		 * Alter Default text message of product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_text_default_message', get_option( 'pdr_designer_text_default_msg' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_no_designs_message' ) ) {

	/**
	 * Get the product designer no designs message.
	 * 
	 * @return string
	 */
	function pdr_get_designer_no_designs_message() {
		/**
		 * Alter no designs message in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_no_designs_message', get_option( 'pdr_designer_no_designs_msg' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_no_product_base_message' ) ) {

	/**
	 * Get the product designer no product base message.
	 * 
	 * @return string
	 */
	function pdr_get_designer_no_product_base_message() {
		/**
		 * Alter no product base message in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_designer_no_product_base_message', get_option( 'pdr_designer_no_product_base_msg' ) );
	}

}


if ( ! function_exists( 'pdr_get_designer_qr_generate_label' ) ) {

	/**
	 * Get the Label of Generate QR Label
	 * 
	 * @return string
	 */
	function pdr_get_designer_qr_generate_label() {
		/**
		 * Alter QR generation label in product designer page
		 *
		 * @since 1.1
		 */
		return apply_filters( 'pdr_designer_qr_generation_label', get_option( 'pdr_designer_qr_generation_label', 'Generate QR Code' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_qr_input_text' ) ) {

	/**
	 * Get the Input Text QR Label
	 * 
	 * @return string
	 */
	function pdr_get_designer_qr_input_text() {
		/**
		 * Alter QR input text label in product designer page
		 *
		 * @since 1.1
		 */
		return apply_filters( 'pdr_designer_qr_input_text_label', get_option( 'pdr_designer_qr_input_text_label', 'QR Input Text' ) );
	}

}

if ( ! function_exists( 'pdr_get_designer_qr_button_label' ) ) {

	/**
	 * Get the Action Button QR Label
	 * 
	 * @return string
	 */
	function pdr_get_designer_qr_button_label() {
		/**
		 * Alter add QR Label in Product Designer Page
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_designer_qr_add_qr_label', get_option( 'pdr_designer_qr_add_qr_label', 'Add QR Code' ) );
	}

}

if ( ! function_exists( 'pdr_get_image_filters' ) ) {

	/**
	 * Get the image filters.
	 * 
	 * @return array
	 */
	function pdr_get_image_filters() {
		return array(
			'Original'    => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/original.jpg',
				'label' => __( 'Original', 'product-designer-for-woocommerce' )
			),
			'Invert'      => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/invert.jpg',
				'label' => __( 'Invert', 'product-designer-for-woocommerce' )
			),
			'Kodachrome'  => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/kodachrome.jpg',
				'label' => __( 'Kodachrome', 'product-designer-for-woocommerce' )
			),
			'BlackWhite'  => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/blackwhite.jpg',
				'label' => __( 'BlackWhite', 'product-designer-for-woocommerce' )
			),
			'Technicolor' => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/technicolor.jpg',
				'label' => __( 'Technicolor', 'product-designer-for-woocommerce' )
			),
			'Brownie'     => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/brownie.jpg',
				'label' => __( 'Brownie', 'product-designer-for-woocommerce' )
			),
			'Vintage'     => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/vintage.jpg',
				'label' => __( 'Vintage', 'product-designer-for-woocommerce' )
			),
			'Sepia'       => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/sepia.jpg',
				'label' => __( 'Sepia', 'product-designer-for-woocommerce' )
			),
			'Sharpen'     => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/sharpen.jpg',
				'label' => __( 'Sharpen', 'product-designer-for-woocommerce' )
			),
			'Emboss'      => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/emboss.jpg',
				'label' => __( 'Emboss', 'product-designer-for-woocommerce' )
			),
			'Polaroid'    => array(
				'url'   => PDR_PLUGIN_PATH . 'assets/img/filters/polaroid.jpg',
				'label' => __( 'Polaroid', 'product-designer-for-woocommerce' )
			),
		);
	}

}

if ( ! function_exists( 'pdr_get_advanced_image_filters' ) ) {

	/**
	 * Get the advanced image filters.
	 * 
	 * @return array
	 */
	function pdr_get_advanced_image_filters() {
		return array(
			'Brightness' => array(
				'index' => 1,
				'label' => __( 'Brightness', 'product-designer-for-woocommerce' )
			),
			'Contrast'   => array(
				'index' => 2,
				'label' => __( 'Contrast', 'product-designer-for-woocommerce' )
			),
			'Saturation' => array(
				'index' => 3,
				'label' => __( 'Saturation', 'product-designer-for-woocommerce' )
			),
		);
	}

}
