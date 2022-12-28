<?php

/**
 * General Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'PDR_General_Tab' ) ) {
	return new PDR_General_Tab();
}

/**
 * PDR_General_Tab.
 */
class PDR_General_Tab extends PDR_Settings_Tab {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'pdr_general';
		$this->label = esc_html__( 'General', 'product-designer-for-woocommerce' );

		parent::__construct();
	}

	/**
	 * Prepare the setting options.
	 * 
	 * @var array
	 */
	protected function prepare_setting_options() {
		/**
		 * Prepare settings options as a dev filter
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_setting_options_' . $this->get_id(), array(
			'pdr_general' => array(
				'pdr_enable_product_customization'                  => '',
				'pdr_product_designer_page_id'                      => get_option( 'pdr_product_designer_page_id' ),
				'pdr_logo_url'                                      => '',
				'pdr_site_title'                                    => 'Product Designer',
				'pdr_back_to_shop_url'                              => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
				'pdr_favicon_url'                                   => '',
				'pdr_footer_content'                                => 'Copyright &copy; All Rights Reserved &reg;',
				'pdr_hide_product_description'                      => '',
				'pdr_hide_mobile'                                   => '',
				'pdr_hide_tablet'                                   => '',
				'pdr_canvas_blob'                                   => '',
				'pdr_hide_price_label_zero'                         => '',
				'pdr_customfont_path'                               => '',
				'pdr_canvas_watermark'                              => '',
				'pdr_watermark_content'                             => 'Copyright',
				'pdr_watermark_fontsize'                            => '48',
				'pdr_watermark_fontcolor'                           => '#ffffff',
				'pdr_theme_selection'                               => 'default',
				'pdr_theme_header_bg_color'                         => '#ffffff',
				'pdr_theme_body_bg_color'                           => '#ffffff',
				'pdr_theme_footer_bg_color'                         => '#ffffff',
				'pdr_theme_button_bg_color'                         => '#2185d0',
				'pdr_theme_font_color'                              => '#000000',
				'pdr_theme_button_font_color'                       => '#ffffff',
				'pdr_user_restrictions'                             => '1',
				'pdr_include_users'                                 => array(),
				'pdr_exclude_users'                                 => array(),
				'pdr_include_user_roles'                            => array(),
				'pdr_include_user_roles'                            => array(),
				'pdr_force_user_login'                              => '',
				'pdr_customize_button_caption'                      => 'Customize Product',
				'pdr_enable_customize_button_single_product_page'   => '1',
				'pdr_customize_button_single_product_page_position' => '3',
				'pdr_enable_customize_button_varition'              => '',
				'pdr_product_add_to_cart'                           => '',
				'pdr_enable_customize_button_other_pages'           => '',
				'pdr_customize_button_other_page_position'          => '2',
				'pdr_product_customization_info_cart'               => '1',
				'pdr_product_customization_info_myaccount'          => '1',
				'pdr_thumbnail_width'                               => '',
				'pdr_thumbnail_height'                              => '',
				'pdr_customize_button_classes'                      => '',
				'pdr_custom_css'                                    => '',
				'pdr_google_fonts_api_key'                          => '',
				'pdr_font_selection_type'                           => '',
				'pdr_font_selection'                                => '',
				'pdr_image_upload_force_user_login'                 => '2',
				'pdr_image_allowed_types'                           => '',
				'pdr_image_upload_min_size'                         => '',
				'pdr_image_upload_max_size'                         => '',
				'pdr_image_min_dimension'                           => '',
				'pdr_image_max_dimension'                           => '',
				'pdr_image_price'                                   => '',
				'pdr_image_min_ppi'                                 => '',
				'pdr_image_max_ppi'                                 => '',
				'pdr_text_character_fee_enabled'                    => '2',
				'pdr_text_character_count'                          => '',
				'pdr_text_character_fee'                            => '',
				'pdr_hide_add_to_cart'                              => '',
				'pdr_show_single_product_redirect'                  => '',
			)
				) );
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_general_section_fields() {
		$settings   = array();
		$user_roles = pdr_get_wp_user_roles();
		$api        = new PDR_API();
		// General Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_general_options',
			'title' => __( 'General Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_enable_product_customization',
			'section' => 'pdr_general_options',
			'title'   => __( 'Enable Product Customization', 'product-designer-for-woocommerce' ),
			'desc'    => __( 'When enabled, users can customize their products before purchase', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'title'   => __( 'Product Designer Page', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_product_designer_page_id',
			'type'    => 'select',
			'class'   => 'pdr_select2',
			'section' => 'pdr_general_options',
			'desc'    => __( 'The page in which the Designer will be displayed', 'product-designer-for-woocommerce' ),
			'options' => pdr_get_page_ids(),
		);
		$settings[] = array(
			'type'    => 'wpmedia',
			'id'      => 'pdr_logo_url',
			'section' => 'pdr_general_options',
			'title'   => __( 'Product Designer Page Logo', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_site_title',
			'section' => 'pdr_general_options',
			'title'   => __( 'Product Designer Page Title', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_back_to_shop_url',
			'section' => 'pdr_general_options',
			'title'   => __( 'Shop URL', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'wpmedia',
			'id'      => 'pdr_favicon_url',
			'section' => 'pdr_general_options',
			'title'   => __( 'Product Designer Page Favicon', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'textarea',
			'id'      => 'pdr_footer_content',
			'section' => 'pdr_general_options',
			'title'   => __( 'Footer Content for Designer Page', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_hide_product_description',
			'section' => 'pdr_general_options',
			'title'   => __( 'Hide Product Descripton on Product Designer Page', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_hide_mobile',
			'section' => 'pdr_general_options',
			'desc'    => __( 'When enabled, users cannot customize the products when they access your site on mobile devices.', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Hide Product Customization on Mobile', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_canvas_blob',
			'section' => 'pdr_general_options',
			'desc'    => __( 'When enabled, canvas data url will be converted to blob(binary large object) which improves performance', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Convert Data URL to BLOB', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_hide_price_label_zero',
			'section' => 'pdr_general_options',
			'desc'    => __( 'When enabled, price tag displayed near product name and attribute values will be hidden when the price is set as 0', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Hide Price Tag in Designer Page when Price is set as 0', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_hide_add_to_cart',
			'section' => 'pdr_general_options',
			'desc'    => __( 'When enabled, Add to Cart button will be hidden in Product Designer Editor Page', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Hide Add to Cart button in Designer Page', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_show_single_product_redirect',
			'section' => 'pdr_general_options',
			'desc'    => __( 'When enabled, it will display Redirect with Changes to Product Page Button in Product Designer Editor Page', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Display Redirect with Changes to Product Page Button in Designer Page', 'product-designer-for-woocommerce' ),
		);

		//            $settings[] = array(
		//                'type'    => 'checkbox' ,
		//                'id'      => 'pdr_hide_tablet' ,
		//                'section' => 'pdr_general_options' ,
		//                'title'   => __( 'Hide on Tablets' , 'product-designer-for-woocommerce' ) ,
		//                    ) ;
		//                    
		//Watermark Settings
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_watermark_settings',
			'title' => __( 'Watermark Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_canvas_watermark',
			'section' => 'pdr_watermark_settings',
			'desc'    => __( 'When enabled, watermark will be applied on print design images when user download/print the design', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Enable Watermark on Print Designs before Purchase', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'textarea',
			'id'      => 'pdr_watermark_content',
			'section' => 'pdr_watermark_settings',
			'title'   => __( 'Watermark Text', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'    => 'number',
			'id'      => 'pdr_watermark_fontsize',
			'section' => 'pdr_watermark_settings',
			'title'   => __( 'Watermark Font Size', 'product-designer-for-woocommerce' ),
			'desc'    => __( 'px', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_watermark_fontcolor',
			'section' => 'pdr_watermark_settings',
			'title'   => __( 'Watermark Font Color', 'product-designer-for-woocommerce' ),
		);

		//Theme Settings
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_theme_settings',
			'title' => __( 'Product Designer Page Theme Settings', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'title'   => __( 'Select Theme', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_theme_selection',
			'type'    => 'select',
			'section' => 'pdr_theme_settings',
			'options' => array(
				'default'  => __( 'Default', 'product-designer-for-woocommerce' ),
				'amazon'   => __( 'Amazon', 'product-designer-for-woocommerce' ),
				'basic'    => __( 'Basic', 'product-designer-for-woocommerce' ),
				'chubby'   => __( 'Chubby', 'product-designer-for-woocommerce' ),
				'github'   => __( 'Github', 'product-designer-for-woocommerce' ),
				'material' => __( 'Material', 'product-designer-for-woocommerce' ),
				'custom'   => __( 'Custom', 'product-designer-for-woocommerce' ),
			),
		);
		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_theme_header_bg_color',
			'section' => 'pdr_theme_settings',
			'title'   => __( 'Header Background Color', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_theme_footer_bg_color',
			'section' => 'pdr_theme_settings',
			'title'   => __( 'Footer Background Color', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_theme_button_bg_color',
			'section' => 'pdr_theme_settings',
			'title'   => __( 'Button Background Color', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_theme_button_font_color',
			'section' => 'pdr_theme_settings',
			'title'   => __( 'Button Font Color', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'color',
			'id'      => 'pdr_theme_font_color',
			'section' => 'pdr_theme_settings',
			'title'   => __( 'Font Color', 'product-designer-for-woocommerce' ),
		);

		// User Restriction Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_user_restriction_options',
			'title' => __( 'User Restrictions', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'title'   => __( 'Product Can be Customized By', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_user_restrictions',
			'type'    => 'select',
			'desc'    => __( 'The products can be customized by users selected in this option.', 'product-designer-for-woocommerce' ),
			'section' => 'pdr_user_restriction_options',
			'options' => array(
				'1' => __( 'All Users', 'product-designer-for-woocommerce' ),
				'2' => __( 'Include Users', 'product-designer-for-woocommerce' ),
				'3' => __( 'Exclude Users', 'product-designer-for-woocommerce' ),
				'4' => __( 'Include User Roles', 'product-designer-for-woocommerce' ),
				'5' => __( 'Exclude User Roles', 'product-designer-for-woocommerce' )
			),
		);
		$settings[] = array(
			'title'     => __( 'Include Users', 'product-designer-for-woocommerce' ),
			'id'        => 'pdr_include_users',
			'type'      => 'ajaxmultiselect',
			'action'    => 'pdr_json_search_customers',
			'list_type' => 'customers',
			'class'     => 'pdr_user_restrictions',
			'section'   => 'pdr_user_restriction_options',
		);
		$settings[] = array(
			'title'     => __( 'Exclude Users', 'product-designer-for-woocommerce' ),
			'id'        => 'pdr_exclude_users',
			'type'      => 'ajaxmultiselect',
			'action'    => 'pdr_json_search_customers',
			'list_type' => 'customers',
			'class'     => 'pdr_user_restrictions',
			'section'   => 'pdr_user_restriction_options',
		);
		$settings[] = array(
			'title'   => __( 'Include User Roles', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_include_user_roles',
			'type'    => 'multiselect',
			'class'   => 'pdr_select2 pdr_user_restrictions',
			'section' => 'pdr_user_restriction_options',
			'options' => $user_roles
		);
		$settings[] = array(
			'title'   => __( 'Exclude User Roles', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_exclude_user_roles',
			'type'    => 'multiselect',
			'class'   => 'pdr_select2 pdr_user_restrictions',
			'section' => 'pdr_user_restriction_options',
			'options' => $user_roles
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_force_user_login',
			'section' => 'pdr_user_restriction_options',
			'desc'    => __( 'When enabled, guest users will be forced to login to the site when try to customize the product', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Force Guest Users to Login Before Customization', 'product-designer-for-woocommerce' ),
		);

		// Display Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_display_options',
			'title' => __( 'Display Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_customize_button_caption',
			'section' => 'pdr_display_options',
			'title'   => __( 'Customize Button Caption', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_enable_customize_button_single_product_page',
			'section' => 'pdr_display_options',
			'title'   => __( 'Display Customize Button on Single Product Pages', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'title'   => __( 'Single Product Page Customize Button Position', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_customize_button_single_product_page_position',
			'type'    => 'select',
			'section' => 'pdr_display_options',
			'options' => array(
				'1' => __( 'After Short Description', 'product-designer-for-woocommerce' ),
				'2' => __( 'Before Add to Cart Button', 'product-designer-for-woocommerce' ),
				'3' => __( 'After Add to Cart Button', 'product-designer-for-woocommerce' ),
			),
		);
		//      $settings[] = array(
		//          'type'    => 'checkbox' ,
		//          'id'      => 'pdr_enable_customize_button_varition' ,
		//          'section' => 'pdr_display_options' ,
		//          'title'   => __( 'Display Customize Button After Variation Selection' , 'product-designer-for-woocommerce' ) ,
		//              ) ;
		//      $settings[] = array(
		//          'title'   => __( 'Product Added to Cart' , 'product-designer-for-woocommerce' ) ,
		//          'id'      => 'pdr_product_add_to_cart' ,
		//          'type'    => 'select' ,
		//          'section' => 'pdr_display_options' ,
		//          'options' => array(
		//              '1' => __( 'Customized Product' , 'product-designer-for-woocommerce' ) ,
		//              '2' => __( 'Original Product' , 'product-designer-for-woocommerce' ) ,
		//          ) ,
		//              ) ;
		$settings[] = array(
			'type'    => 'checkbox',
			'id'      => 'pdr_enable_customize_button_other_pages',
			'section' => 'pdr_display_options',
			'title'   => __( 'Display Customize Button on Shop and Category Pages', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'title'   => __( 'Shop and Category Page Position', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_customize_button_other_page_position',
			'type'    => 'select',
			'section' => 'pdr_display_options',
			'options' => array(
				'1' => __( 'Before Add to Cart Button', 'product-designer-for-woocommerce' ),
				'2' => __( 'After Add to Cart Button', 'product-designer-for-woocommerce' ),
			),
		);
		$settings[] = array(
			'title'   => __( 'Product Customization Info in Cart Page', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_product_customization_info_cart',
			'type'    => 'select',
			'section' => 'pdr_display_options',
			'options' => array(
				'1' => __( 'Show', 'product-designer-for-woocommerce' ),
				'2' => __( 'Hide', 'product-designer-for-woocommerce' ),
			),
		);
		$settings[] = array(
			'title'   => __( 'Product Customization Info in My Account Page', 'product-designer-for-woocommerce' ),
			'id'      => 'pdr_product_customization_info_myaccount',
			'type'    => 'select',
			'section' => 'pdr_display_options',
			'options' => array(
				'1' => __( 'Show', 'product-designer-for-woocommerce' ),
				'2' => __( 'Hide', 'product-designer-for-woocommerce' ),
			),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_thumbnail_width',
			'section' => 'pdr_display_options',
			'title'   => __( 'Cart Page Thumbnail Width', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_thumbnail_height',
			'section' => 'pdr_display_options',
			'title'   => __( 'Cart Page Thumbnail Height', 'product-designer-for-woocommerce' ),
		);

		// CSS Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_custom_css_options',
			'title' => '',
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_customize_button_classes',
			'section' => 'pdr_custom_css_options',
			'title'   => __( 'Customize Button Additional Class', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'textarea',
			'id'      => 'pdr_custom_css',
			'section' => 'pdr_custom_css_options',
			'title'   => __( 'Custom CSS', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'select',
			'id'      => 'pdr_image_upload_force_user_login',
			'section' => 'pdr_image_options',
			'title'   => __( 'Force Users to Login Before they can Upload Images to Designer', 'product-designer-for-woocommerce' ),
			'options' => array(
				'1' => __( 'Yes', 'product-designer-for-woocommerce' ),
				'2' => __( 'No', 'product-designer-for-woocommerce' )
			)
		);

		// Images Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_image_options',
			'title' => __( 'Image Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'select',
			'id'      => 'pdr_image_upload_force_user_login',
			'section' => 'pdr_image_options',
			'title'   => __( 'Force Users to Login Before they can Upload Images to Designer', 'product-designer-for-woocommerce' ),
			'options' => array(
				'1' => __( 'Yes', 'product-designer-for-woocommerce' ),
				'2' => __( 'No', 'product-designer-for-woocommerce' )
			)
		);
		$settings[] = array(
			'type'        => 'textarea',
			'id'          => 'pdr_image_allowed_types',
			'section'     => 'pdr_image_options',
			'title'       => __( 'Allowed Image Types', 'product-designer-for-woocommerce' ),
			'placeholder' => __( 'For Ex: png, jpeg, jpg, gif, etc', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_image_upload_min_size',
			'section' => 'pdr_image_options',
			'title'   => __( 'Min Size for Upload', 'product-designer-for-woocommerce' ),
			'desc'    => __( 'Please enter the size in kb', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_image_upload_max_size',
			'section' => 'pdr_image_options',
			'title'   => __( 'Max Size for Upload', 'product-designer-for-woocommerce' ),
			'desc'    => __( 'Please enter the size in kb', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'        => 'text',
			'id'          => 'pdr_image_min_dimension',
			'section'     => 'pdr_image_options',
			'title'       => __( 'Min Dimensions', 'product-designer-for-woocommerce' ),
			'placeholder' => __( 'Width x Height - Example(50x50)', 'product-designer-for-woocommerce' )
		);
		$settings[] = array(
			'type'        => 'text',
			'id'          => 'pdr_image_max_dimension',
			'section'     => 'pdr_image_options',
			'title'       => __( 'Max Dimensions', 'product-designer-for-woocommerce' ),
			'placeholder' => __( 'Width x Height - Example (150x150)', 'product-designer-for-woocommerce' )
		);

		$settings[] = array(
			'type'        => 'text',
			'id'          => 'pdr_image_price',
			'section'     => 'pdr_image_options',
			'title'       => __( 'Fee for Image Upload', 'product-designer-for-woocommerce' ),
			'placeholder' => __( 'Fee for Image Upload', 'product-designer-for-woocommerce' ),
		);
		// Text Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_text_options',
			'title' => __( 'Text Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'select',
			'id'      => 'pdr_text_character_fee_enabled',
			'section' => 'pdr_text_options',
			'desc'    => __( 'When set to yes, you can charge a fee from your users when they add text to their design', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Charge a Fee', 'product-designer-for-woocommerce' ),
			'options' => array(
				'1' => __( 'Yes', 'product-designer-for-woocommerce' ),
				'2' => __( 'No', 'product-designer-for-woocommerce' )
			)
		);
		$settings[] = array(
			'type'              => 'number',
			'id'                => 'pdr_text_character_count',
			'section'           => 'pdr_text_options',
			'title'             => __( 'Number of Characters', 'product-designer-for-woocommerce' ),
			'custom_attributes' => array( 'min' => '1' )
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_text_character_fee',
			'section' => 'pdr_text_options',
			'title'   => __( 'Fee', 'product-designer-for-woocommerce' ),
		);

		// Google Fonts Section.
		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_google_fonts_options',
			'title' => __( 'Google Fonts Settings', 'product-designer-for-woocommerce' ),
		);
		$settings[] = array(
			'type'    => 'text',
			'id'      => 'pdr_google_fonts_api_key',
			'section' => 'pdr_google_fonts_options',
			'desc'    => __( 'API key has to be entered in order for your users to use Google Font for their Customization.', 'product-designer-for-woocommerce' ),
			'title'   => __( 'Google Font API Key', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_customfont_settings',
			'title' => __( 'Custom Fonts Settings', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'        => 'textarea',
			'id'          => 'pdr_customfont_path',
			'class'       => 'pdr-text-area',
			'section'     => 'pdr_customfont_settings',
			'placeholder' => __( 'Multiple file paths separated by comma(,)', 'product-designer-for-woocommerce' ),
			'title'       => __( 'Custom Fonts File Path', 'product-designer-for-woocommerce' ),
			'desc'        => __( 'To add custom fonts, go to "Media > Add New" and click "Select Files" button. Select the custom font file(supported file types: otf, ttf, woff, woff2 ) and click "Copy URL to Clipboard" button. Now, paste the copied file path here.
Note: Filename will be displayed as font name for the customers', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'  => 'section',
			'id'    => 'pdr_select_font_options',
			'title' => __( 'Font Family Settings', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'    => 'select',
			'id'      => 'pdr_font_selection_type',
			'section' => 'pdr_select_font_options',
			'title'   => __( 'Usable Font Families', 'product-designer-for-woocommerce' ),
			'options' => array(
				'1' => __( 'All Available Font Families', 'product-designer-for-woocommerce' ),
				'2' => __( 'Selected Font Families', 'product-designer-for-woocommerce' )
			),
			'desc'    => __( 'This option controls which font families customers can use for text customizing. This option can be overridden in each product base', 'product-designer-for-woocommerce' ),
		);

		$settings[] = array(
			'type'        => 'multiselect',
			'id'          => 'pdr_font_selection',
			'section'     => 'pdr_select_font_options',
			'title'       => __( 'Select Font Families', 'product-designer-for-woocommerce' ),
			'options'     => $api->get_fonts(),
			'class'       => 'pdr_select2',
			'placeholder' => __( 'Select Font Families', 'product-designer-for-woocommerce' ),
		);

		/**
		 * General tab overall settings dev filter
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_setting_options_general_section', $settings );
	}

}

return new PDR_General_Tab();
