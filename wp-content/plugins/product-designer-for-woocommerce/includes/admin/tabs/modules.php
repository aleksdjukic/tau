<?php

/**
 * Modules Tab.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (class_exists('PDR_Modules_Tab')) {
	return new PDR_Modules_Tab();
}

/**
 * PDR_Modules_Tab.
 */
class PDR_Modules_Tab extends PDR_Settings_Tab {

	/**
	 * Show buttons.
	 * 
	 * @var bool
	 */
	protected $show_buttons = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id = 'pdr_modules';
		$this->label = esc_html__('Modules', 'product-designer-for-woocommerce');

		add_action($this->get_plugin_slug() . '_settings_content_' . $this->get_id(), array($this, 'display_module_content'), 999);

		parent::__construct();
	}

	/**
	 * Output the settings buttons.
	 * 
	 * @return void
	 */
	public function output_buttons() {
		global $current_section;
		if ('pdr_modules' == $current_section) {
			return;
		}

		PDR_Settings_Page::output_buttons(true);
	}

	/**
	 * Prepare the setting options.
	 * 
	 * @var array
	 */
	protected function prepare_setting_options() {
		/**
		 * Settings Options for Module
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_' . $this->get_id(), array(
			'pdr_modules' => array(
				'pdr_settings_enable_templates_module' => '1',
				'pdr_settings_enable_cliparts_module' => '1',
				'pdr_settings_enable_images_module' => '1',
				'pdr_settings_enable_text_module' => '1',
				'pdr_settings_enable_shapes_module' => '1',
				'pdr_settings_enable_product_selection_module' => '1'
			),
				//          'pdr_templates' => array(
				//              'pdr_template_user_can_save'         => '1' ,
				//              'pdr_template_user_can_download'     => '1' ,
				//              'pdr_template_user_can_upload'       => '1' ,
				//              'pdr_template_user_can_print'        => '1' ,
				//              'pdr_template_auto_center'           => '1' ,
				//              'pdr_template_draggable'             => '1' ,
				//              'pdr_template_rotatable'             => '1' ,
				//              'pdr_template_resizable'             => '1' ,
				//              'pdr_template_unpropotional_scaling' => '1' ,
				//              'pdr_template_always_stay_top'       => '1'
				//          ) ,
				//          'pdr_cliparts'  => array(
				//              'pdr_clipart_min_dimension'         => '' ,
				//              'pdr_clipart_max_dimension'         => '' ,
				//              'pdr_clipart_min_ppi'               => '' ,
				//              'pdr_clipart_max_ppi'               => '' ,
				//              'pdr_clipart_auto_center'           => '1' ,
				//              'pdr_clipart_draggable'             => '1' ,
				//              'pdr_clipart_rotatable'             => '1' ,
				//              'pdr_clipart_resizable'             => '1' ,
				//              'pdr_clipart_unpropotional_scaling' => '1' ,
				//              'pdr_clipart_always_stay_top'       => '1'
				//          ) ,
				//          'pdr_images'    => array(
				//              'pdr_image_save_server'             => '1' ,
				//              'pdr_image_upload_force_user_login' => '1' ,
				//              'pdr_image_allowed_types'           => '' ,
				//              'pdr_image_upload_min_size'         => '' ,
				//              'pdr_image_upload_max_size'         => '' ,
				//              'pdr_image_min_dimension'           => '' ,
				//              'pdr_image_max_dimension'           => '' ,
				//              'pdr_image_min_ppi'                 => '' ,
				//              'pdr_image_max_ppi'                 => '' ,
				//              'pdr_image_auto_center'             => '1' ,
				//              'pdr_image_draggable'               => '1' ,
				//              'pdr_image_rotatable'               => '1' ,
				//              'pdr_image_resizable'               => '1' ,
				//              'pdr_image_unpropotional_scaling'   => '1' ,
				//              'pdr_image_always_stay_top'         => '1'
				//          ) ,
				//          'pdr_text'      => array(
				//              'pdr_text_curving'               => '1' ,
				//              'pdr_text_curve_spacing'         => '' ,
				//              'pdr_text_curve_radius'          => '' ,
				//              'pdr_text_curve_reverse'         => '1' ,
				//              'pdr_text_auto_center'           => '1' ,
				//              'pdr_text_draggable'             => '1' ,
				//              'pdr_text_rotatable'             => '1' ,
				//              'pdr_text_resizable'             => '1' ,
				//              'pdr_text_unpropotional_scaling' => '1' ,
				//              'pdr_text_always_stay_top'       => '1' ,
				//              'pdr_text_default_font_size'     => '' ,
				//              'pdr_text_min_font_size'         => '' ,
				//              'pdr_text_max_font_size'         => '' ,
				//              'pdr_text_min_char'              => '' ,
				//              'pdr_text_max_char'              => '' ,
				//              'pdr_text_max_lines'             => '' ,
				//              'pdr_text_default_alignment'     => '1'
				//          ) ,
				//          'pdr_shapes'    => array(
				//              'pdr_shape_auto_center'           => '1' ,
				//              'pdr_shape_draggable'             => '1' ,
				//              'pdr_shape_rotatable'             => '1' ,
				//              'pdr_shape_resizable'             => '1' ,
				//              'pdr_shape_unpropotional_scaling' => '1' ,
				//              'pdr_shape_always_stay_top'       => '1'
				//          ) ,
		));
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_templates_section_fields() {
		$settings = array();

		// Template Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_template_options',
			'title' => __('Template Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_user_can_save',
			'section' => 'pdr_template_options',
			'title' => __('User can save their designs', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_user_can_download',
			'section' => 'pdr_template_options',
			'title' => __('User can download their design', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_user_can_upload',
			'section' => 'pdr_template_options',
			'title' => __('User can upload their own designs', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_user_can_print',
			'section' => 'pdr_template_options',
			'title' => __('User can print their designs', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_auto_center',
			'section' => 'pdr_template_options',
			'title' => __('Auto Center Template', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_draggable',
			'section' => 'pdr_template_options',
			'title' => __('Template is Draggable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_rotatable',
			'section' => 'pdr_template_options',
			'title' => __('Template is Rotatable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_resizable',
			'section' => 'pdr_template_options',
			'title' => __('Template is Resizable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_unpropotional_scaling',
			'section' => 'pdr_template_options',
			'title' => __('Allow Unpropotional Scaling', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_template_always_stay_top',
			'section' => 'pdr_template_options',
			'title' => __('Always Stay on Top', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		/**
		 * Settings option for module alteration 
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_template_section', $settings);
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_cliparts_section_fields() {
		$settings = array();

		// Cliparts Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_clipart_options',
			'title' => __('Clipart Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_clipart_min_dimension',
			'section' => 'pdr_clipart_options',
			'title' => __('Min Dimensions', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_clipart_max_dimension',
			'section' => 'pdr_clipart_options',
			'title' => __('Max Dimensions', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_clipart_min_ppi',
			'section' => 'pdr_clipart_options',
			'title' => __('Min PPI', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_clipart_max_ppi',
			'section' => 'pdr_clipart_options',
			'title' => __('Max PPI', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_auto_center',
			'section' => 'pdr_clipart_options',
			'title' => __('Auto Center Clipart', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_draggable',
			'section' => 'pdr_clipart_options',
			'title' => __('Clipart is Draggable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_rotatable',
			'section' => 'pdr_clipart_options',
			'title' => __('Clipart is Rotatable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_resizable',
			'section' => 'pdr_clipart_options',
			'title' => __('Clipart is Resizable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_unpropotional_scaling',
			'section' => 'pdr_clipart_options',
			'title' => __('Allow Unpropotional Scaling', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_clipart_always_stay_top',
			'section' => 'pdr_clipart_options',
			'title' => __('Always Stay on Top', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		/**
		 * Clipart section settings
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_clipart_section', $settings);
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_images_section_fields() {
		$settings = array();

		// Images Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_image_options',
			'title' => __('Image Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_save_server',
			'section' => 'pdr_image_options',
			'title' => __('Save Uploaded Images on Server', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_upload_force_user_login',
			'section' => 'pdr_image_options',
			'title' => __('Force User to Login before Image Upload', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_allowed_types',
			'section' => 'pdr_image_options',
			'title' => __('Allowed Image Types', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_upload_min_size',
			'section' => 'pdr_image_options',
			'title' => __('Min Size for Upload', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_upload_max_size',
			'section' => 'pdr_image_options',
			'title' => __('Max Size for Upload', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_min_dimension',
			'section' => 'pdr_image_options',
			'title' => __('Min Dimensions', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_max_dimension',
			'section' => 'pdr_image_options',
			'title' => __('Max Dimensions', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_min_ppi',
			'section' => 'pdr_image_options',
			'title' => __('Min PPI', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_image_max_ppi',
			'section' => 'pdr_image_options',
			'title' => __('Max PPI', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_auto_center',
			'section' => 'pdr_image_options',
			'title' => __('Auto Center Image', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_draggable',
			'section' => 'pdr_image_options',
			'title' => __('Image is Draggable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_rotatable',
			'section' => 'pdr_image_options',
			'title' => __('Image is Rotatable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_resizable',
			'section' => 'pdr_image_options',
			'title' => __('Image is Resizable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_unpropotional_scaling',
			'section' => 'pdr_image_options',
			'title' => __('Allow Unpropotional Scaling', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_image_always_stay_top',
			'section' => 'pdr_image_options',
			'title' => __('Always Stay on Top', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		/**
		 * Image section alteration
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_image_section', $settings);
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_text_section_fields() {
		$settings = array();

		// Text Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_text_options',
			'title' => __('Text Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_curving',
			'section' => 'pdr_text_options',
			'title' => __('Text Curving', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_curve_spacing',
			'section' => 'pdr_text_options',
			'title' => __('Curve  Spacing', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_curve_radius',
			'section' => 'pdr_text_options',
			'title' => __('Curve Radius', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_curve_reverse',
			'section' => 'pdr_text_options',
			'title' => __('Curve Reverse', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_auto_center',
			'section' => 'pdr_text_options',
			'title' => __('Auto Center Image', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_draggable',
			'section' => 'pdr_text_options',
			'title' => __('Image is Draggable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_rotatable',
			'section' => 'pdr_text_options',
			'title' => __('Image is Rotatable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_resizable',
			'section' => 'pdr_text_options',
			'title' => __('Image is Resizable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_unpropotional_scaling',
			'section' => 'pdr_text_options',
			'title' => __('Allow Unpropotional Scaling', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_always_stay_top',
			'section' => 'pdr_text_options',
			'title' => __('Always Stay on Top', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_default_font_size',
			'section' => 'pdr_text_options',
			'title' => __('Default Font Size', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_min_font_size',
			'section' => 'pdr_text_options',
			'title' => __('Minimum Font Size', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_max_font_size',
			'section' => 'pdr_text_options',
			'title' => __('Maximum Font Size', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_min_char',
			'section' => 'pdr_text_options',
			'title' => __('Minimum Characters', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_max_char',
			'section' => 'pdr_text_options',
			'title' => __('Maximum Characters', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'text',
			'id' => 'pdr_text_max_lines',
			'section' => 'pdr_text_options',
			'title' => __('Maximum Lines', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_text_default_alignment',
			'section' => 'pdr_text_options',
			'title' => __('Default Alignment', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		/**
		 * Text Section alteration
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_text_section', $settings);
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_shapes_section_fields() {
		$settings = array();

		// Shapes Section.
		$settings[] = array(
			'type' => 'section',
			'id' => 'pdr_shape_options',
			'title' => __('Shapes Settings', 'product-designer-for-woocommerce'),
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_auto_center',
			'section' => 'pdr_shape_options',
			'title' => __('Auto Center Shape', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_draggable',
			'section' => 'pdr_shape_options',
			'title' => __('Shape is Draggable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_rotatable',
			'section' => 'pdr_shape_options',
			'title' => __('Shape is Rotatable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_resizable',
			'section' => 'pdr_shape_options',
			'title' => __('Shape is Resizable', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_unpropotional_scaling',
			'section' => 'pdr_shape_options',
			'title' => __('Allow Unpropotional Scaling', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);
		$settings[] = array(
			'type' => 'select',
			'id' => 'pdr_shape_always_stay_top',
			'section' => 'pdr_shape_options',
			'title' => __('Always Stay on Top', 'product-designer-for-woocommerce'),
			'options' => array(
				'1' => __('Yes', 'product-designer-for-woocommerce'),
				'2' => __('No', 'product-designer-for-woocommerce')
			)
		);

		/**
		 * Shape Section settings
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_setting_options_shape_section', $settings);
	}

	/**
	 * Display the module content.
	 * 
	 * @return void
	 */
	public function display_module_content() {
		global $current_section;

		if ('pdr_modules' != $current_section) {
			return;
		}

		$modules = array(
			'pdr_templates' => array(
				'name' => __('Templates', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_templates_module',
				'customize' => false
			),
			'pdr_cliparts' => array(
				'name' => __('Cliparts', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_cliparts_module',
				'customize' => false
			),
			'pdr_images' => array(
				'name' => __('Images', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_images_module',
				'customize' => false
			),
			'pdr_text' => array(
				'name' => __('Text', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_text_module',
				'customize' => false
			),
			'pdr_shapes' => array(
				'name' => __('Shapes', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_shapes_module',
				'customize' => false
			),
			'pdr_product_selection' => array(
				'name' => __('Product Selection', 'product-designer-for-woocommerce'),
				'option_name' => 'pdr_settings_enable_product_selection_module',
				'customize' => false
			)
		);

		include_once PDR_PLUGIN_DIR . '/includes/admin/views/html-modules-table.php';
	}

}

return new PDR_Modules_Tab();
