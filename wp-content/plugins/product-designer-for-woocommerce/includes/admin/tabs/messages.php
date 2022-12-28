<?php

/**
 * Messages Tab.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (class_exists('PDR_Messages_Tab')) {
	return new PDR_Messages_Tab();
}

/**
 * PDR_Messages_Tab.
 */
class PDR_Messages_Tab extends PDR_Settings_Tab {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id = 'pdr_messages';
		$this->label = esc_html__('Messages', 'product-designer-for-woocommerce');

		parent::__construct();
	}

	/**
	 * Prepare the setting options.
	 * 
	 * @var array
	 */
	protected function prepare_setting_options() {
		/**
		 * Add custom options to the settings page for that corresponding tab
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_' . $this->get_id(), array(
			'pdr_messages' => array(
				'pdr_designer_no_designs_msg' => 'Sorry No Designs found for your account. Please save a design and try it out.',
				'pdr_designer_text_default_msg' => '',
				'pdr_designer_no_product_base_msg' => 'You need to select the product before going to edit it in our designer page',
				'pdr_designer_product_added_msg' => 'Product Added to Cart Successfully',
				'pdr_designer_design_saved_msg' => 'Design Saved Successfully',
				'pdr_designer_design_deleted_msg' => 'Design Deleted Successfully',
				'pdr_designer_force_login_message' => 'Please login to customize the product <a class="btn ui button" href="{{login_url}}">Login</a>',
				'pdr_designer_qr_input_text_empty' => 'Enter your QR Code Content before adding',
			)
				));
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_messages_section_fields() {
		$settings = array();
		// General Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_general_options',
			'title' => __('Message Settings', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_no_designs_msg',
			'section' => 'pdr_general_options',
			'title' => __('No Designs Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_text_default_msg',
			'section' => 'pdr_general_options',
			'title' => __('Text Default Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_no_product_base_msg',
			'section' => 'pdr_general_options',
			'title' => __('No Product Base Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_product_added_msg',
			'section' => 'pdr_general_options',
			'title' => __('Product Added Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_design_saved_msg',
			'section' => 'pdr_general_options',
			'title' => __('Design Saved Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_design_deleted_msg',
			'section' => 'pdr_general_options',
			'title' => __('Design Deleted Message', 'product-designer-for-woocommerce'),
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_force_login_message',
			'section' => 'pdr_general_options',
			'title' => __('Force Login Message', 'product-designer-for-woocommerce'),
			'description' => 'Use the below code to display Login button:
<a class="btn ui button" href="{{login_url}}">Login</a>',
				);
		$settings[] = array(
			'type' => 'textarea',
			'id' => 'pdr_designer_qr_input_text_empty',
			'section' => 'pdr_general_options',
			'title' => __('Empty QR Code Content Error Message', 'product-designer-for-woocommerce'),
				);
		/**
		 * Message Settings Tab settings 
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_messages_section', $settings);
	}

}

return new PDR_Messages_Tab();
