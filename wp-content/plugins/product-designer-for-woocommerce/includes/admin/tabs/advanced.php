<?php

/**
 * Advanced Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'PDR_Advanced_Tab' ) ) {
	return new PDR_Advanced_Tab() ;
}

/**
 * PDR_Advanced_Tab.
 */
class PDR_Advanced_Tab extends PDR_Settings_Tab {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'pdr_advanced' ;
		$this->label = esc_html__( 'Advanced' , 'product-designer-for-woocommerce' ) ;

		parent::__construct() ;
	}

	/**
	 * Prepare the setting options.
	 * 
	 * @var array
	 */
	protected function prepare_setting_options() {
				/**
				 * Advanced Settings Options
				 *
				 * @since 1.0
				 */
		return apply_filters( 'pdr_setting_options_' . $this->get_id() , array(
			'pdr_advanced' => array(
				'pdr_enable_smart_guide'     => '1' ,
				'pdr_enable_touch_scrolling' => '1' ,
				'pdr_fit_image_canvas'       => '1' ,
			)
				) ) ;
	}

	/**
	 * Add the setting section and fields.
	 * 
	 * @return void
	 */
	public function add_pdr_advanced_section_fields() {
		$settings = array() ;

		// Advanced section.
		$settings[] = array(
			'type'  => 'section' ,
			'id'    => 'pdr_advanced_options' ,
			'title' => __( 'General Settings' , 'product-designer-for-woocommerce' ) ,
				) ;
		$settings[] = array(
			'type'    => 'select' ,
			'id'      => 'pdr_enable_smart_guide' ,
			'section' => 'pdr_advanced_options' ,
			'title'   => __( 'Smart Guide' , 'product-designer-for-woocommerce' ) ,
			'options' => array(
				'1' => __( 'Yes' , 'product-designer-for-woocommerce' ) ,
				'2' => __( 'No' , 'product-designer-for-woocommerce' )
			)
				) ;
		$settings[] = array(
			'type'    => 'select' ,
			'id'      => 'pdr_enable_touch_scrolling' ,
			'section' => 'pdr_advanced_options' ,
			'title'   => __( 'Touch Scrolling Support' , 'product-designer-for-woocommerce' ) ,
			'options' => array(
				'1' => __( 'Yes' , 'product-designer-for-woocommerce' ) ,
				'2' => __( 'No' , 'product-designer-for-woocommerce' )
			)
				) ;
		$settings[] = array(
			'type'    => 'select' ,
			'id'      => 'pdr_fit_image_canvas' ,
			'section' => 'pdr_advanced_options' ,
			'title'   => __( 'Fit Image to Canvas' , 'product-designer-for-woocommerce' ) ,
			'options' => array(
				'1' => __( 'Yes' , 'product-designer-for-woocommerce' ) ,
				'2' => __( 'No' , 'product-designer-for-woocommerce' )
			)
				) ;
				/**
				 * Settings Option for Advanced Tab
				 *
				 * @since 1.0
				 */
		return apply_filters( 'pdr_setting_options_advanced_section' , $settings ) ;
	}

}

return new PDR_Advanced_Tab() ;
