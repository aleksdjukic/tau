<?php

/**
 * Localization Tab.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (class_exists('PDR_Localization_Tab')) {
	return new PDR_Localization_Tab();
}

/**
 * PDR_Localization_Tab.
 */
class PDR_Localization_Tab extends PDR_Settings_Tab {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id = 'pdr_localization';
		$this->label = esc_html__('Localization', 'product-designer-for-woocommerce');

		parent::__construct();
	}

	/**
	 * Prepare the setting options.
	 * 
	 * @var array
	 */
	protected function prepare_setting_options() {
		/**
		 * Settings Option for Localization
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_' . $this->get_id(), array(
			'pdr_localization' => array(
				'pdr_designer_file_label' => 'File',
				'pdr_designer_export_file_label' => 'Export',
				'pdr_designer_export_template_label' => 'Export as a Template',
				'pdr_designer_print_label' => 'Print',
				'pdr_designer_my_designs_label' => 'My Designs',
				'pdr_designer_add_to_cart_label' => 'Add to Cart',
				'pdr_designer_update_cart_label' => 'Update Cart',
				'pdr_designer_back_to_shop_label' => 'Back to Shop',
				'pdr_designer_product_menu_label' => 'Product',
				'pdr_designer_product_quantity_label' => 'Quantity',
				'pdr_designer_choose_product_label' => 'Change Product',
				'pdr_designer_clipart_menu_label' => 'Cliparts',
				'pdr_designer_image_menu_label' => 'Images',
				'pdr_designer_text_menu_label' => 'Text',
				'pdr_designer_shape_menu_label' => 'Shapes',
				'pdr_designer_template_menu_label' => 'Templates',
				'pdr_designer_switch_view_label' => 'Switch View',
				'pdr_designer_save_design_label' => 'Save Design',
				'pdr_designer_text_tools_label' => 'Text Tools',
				'pdr_designer_object_tools_label' => 'Object Tools',
				'pdr_designer_text_font_size_label' => 'Font Size',
				'pdr_designer_text_font_family_label' => 'Font Family',
				'pdr_designer_text_select_font_family_label' => 'Select Font Family',
				'pdr_designer_text_google_fonts_label' => 'Google Fonts',
				'pdr_designer_text_font_color_label' => 'Font Color',
				'pdr_designer_text_background_color_label' => 'Font Background Color',
				'pdr_designer_text_add_new_label' => 'Add Text',
				'pdr_designer_text_image_mask_label' => 'Text Mask Image',
				'pdr_designer_qr_generation_label' => 'Generate QR Code',
				'pdr_designer_qr_input_text_label' => 'QR Input Text',
				'pdr_designer_qr_add_qr_label' => 'Add QR Code',
				'pdr_designer_redirect_single_pp_label' => 'Redirect with Changes to Product Page',
				'pdr_designer_product_page_label' => '+ Designing Price: {{price}}'
			)
		));
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_localization_section_fields() {

		$settings = array();
		// General Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_general_options',
			'title' => __('Localization Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_file_label',
			'section' => 'pdr_general_options',
			'title' => __('File Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_export_file_label',
			'section' => 'pdr_general_options',
			'title' => __('Export Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_export_template_label',
			'section' => 'pdr_general_options',
			'title' => __('Export Template Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_print_label',
			'section' => 'pdr_general_options',
			'title' => __('Print Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_my_designs_label',
			'section' => 'pdr_general_options',
			'title' => __('My Designs Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_add_to_cart_label',
			'section' => 'pdr_general_options',
			'title' => __('Add To Cart Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_update_cart_label',
			'section' => 'pdr_general_options',
			'title' => __('Update Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_back_to_shop_label',
			'section' => 'pdr_general_options',
			'title' => __('Back to Shop Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_product_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Product Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_product_quantity_label',
			'section' => 'pdr_general_options',
			'title' => __('Product Quantity Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_choose_product_label',
			'section' => 'pdr_general_options',
			'title' => __('Change Product Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_clipart_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Cliparts Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_image_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Images Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Text Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_shape_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Shapes Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_template_menu_label',
			'section' => 'pdr_general_options',
			'title' => __('Templates Menu Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_switch_view_label',
			'section' => 'pdr_general_options',
			'title' => __('Switch View Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_save_design_label',
			'section' => 'pdr_general_options',
			'title' => __('Save Design Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_tools_label',
			'section' => 'pdr_general_options',
			'title' => __('Text Tools Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_object_tools_label',
			'section' => 'pdr_general_options',
			'title' => __('Object Tools Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_font_size_label',
			'section' => 'pdr_general_options',
			'title' => __('Font Size Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_font_family_label',
			'section' => 'pdr_general_options',
			'title' => __('Font Family Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_select_font_family_label',
			'section' => 'pdr_general_options',
			'title' => __('Select Font Family Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_google_fonts_label',
			'section' => 'pdr_general_options',
			'title' => __('Google Fonts Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_font_color_label',
			'section' => 'pdr_general_options',
			'title' => __('Font Color Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_background_color_label',
			'section' => 'pdr_general_options',
			'title' => __('Background Color Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_add_new_label',
			'section' => 'pdr_general_options',
			'title' => __('Add Text Button Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_text_image_mask_label',
			'section' => 'pdr_general_options',
			'title' => __('Text Mask Image Label', 'product-designer-for-woocommerce'),
		);

		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_qr_generation_label',
			'section' => 'pdr_general_options',
			'title' => __('Generate QR Code Label', 'product-designer-for-woocommerce'),
		);

		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_qr_input_text_label',
			'section' => 'pdr_general_options',
			'title' => __('QR Code Content Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_qr_add_qr_label',
			'section' => 'pdr_general_options',
			'title' => __('Add QR Code Button Label', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_redirect_single_pp_label',
			'section' => 'pdr_general_options',
			'title' => __('Display Redirect with Changes to Product Page Button on Product Designer Page', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_designer_product_page_label',
			'section' => 'pdr_general_options',
			'title' => __('Designing Price in Single Product Page(Option is for Redirect with Changes to Product Page )', 'product-designer-for-woocommerce'),
		);

		/**
		 * Settings Option for Localization
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_localization_section', $settings);
	}

}

return new PDR_Localization_Tab();
