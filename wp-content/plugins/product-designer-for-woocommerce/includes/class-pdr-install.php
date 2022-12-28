<?php

/**
 * Initialize the plugin.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Install' ) ) {

	/**
	 * Class.
	 * */
	class PDR_Install {

		/**
		 *  Class initialization.
		 * */
		public static function init() {
			add_filter( 'plugin_action_links_' . PDR_PLUGIN_SLUG , array( __CLASS__ , 'settings_link' ) ) ;
		}

		/**
		 * Install.
		 * */
		public static function install() {
			self::set_default_values() ; // default values.
			PDR_Pages::create_pages() ; // Create pages.
		}

		/**
		 *  Add the settings link in the plugin row.
		 * 
		 * @return array
		 * */
		public static function settings_link( $links ) {
			$setting_page_link = '<a href="' . pdr_get_settings_page_url() . '">' . esc_html__( 'Settings' , 'lottery-for-woocommerce' ) . '</a>' ;

			array_unshift( $links , $setting_page_link ) ;

			return $links ;
		}

		/**
		 * Set the settings default values.
		 * 
		 * @return void
		 */
		public static function set_default_values() {

			if ( ! class_exists( 'PDR_Settings_Page' ) ) {
				include_once( PDR_PLUGIN_PATH . '/inc/admin/menu/class-pdr-settings-page.php' ) ;
			}

			// Get the setting tabs.
			$settings = PDR_Settings_Page::get_settings_tabs() ;
			foreach ( $settings as $setting ) {
				$settings_sections = $setting->get_setting_options() ;

				if ( ! pdr_check_is_array( $settings_sections ) ) {
					continue ;
				}

				foreach ( $settings_sections as $settings_section ) {

					if ( ! pdr_check_is_array( $settings_section ) ) {
						continue ;
					}

					foreach ( $settings_section as $key => $value ) {
						// Check the option status before add option , it has been already added.
						if ( get_option( $key ) === false ) {
							add_option( $key , $value ) ;
						}
					}
				}
			}
		}

	}

	PDR_Install::init() ;
}
