<?php

/**
 * Settings Page.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('PDR_Settings_Page')) :

	/**
	 * PDR_Settings_Page Class.
	 * */
	class PDR_Settings_Page {

		/**
		 * Setting pages.
		 * 
		 * @var array
		 */
		private static $settings = array();

		/**
		 * Page name.
		 * 
		 * @var string
		 */
		private static $page_name = 'pdr_settings';

		/**
		 * Plugin Slug.
		 * 
		 * @var string
		 */
		private static $plugin_slug = 'pdr';

		/**
		 * Include the settings page tabs.
		 * 
		 * @return array
		 */
		public static function get_settings_tabs() {
			if (!empty(self::$settings)) {
				return self::$settings;
			}

			include_once (PDR_PLUGIN_DIR . 'includes/abstracts/abstract-pdr-settings-tab.php');

			/**
			 * Dev filter to add additional settings tab and its functionality
			 *
			 * @since 1.0
			 */
			$tabs = apply_filters('pdr_settings_tabs', array(
				'general',
				'modules',
				//              'advanced' ,
				'messages',
				'localization'
					));

			foreach ($tabs as $tab) {
				$settings[sanitize_key('pdr_' . $tab)] = include 'tabs/' . sanitize_key($tab) . '.php';
			}

			/**
			 * Load settings content to the respective tab
			 *
			 * @since 1.0
			 */
			self::$settings = apply_filters(sanitize_key(self::$plugin_slug . '_get_settings_tabs'), $settings);

			return self::$settings;
		}

		/**
		 * Handles the display of the settings page in admin.
		 * 
		 * @return void
		 */
		public static function output() {
			global $current_section, $current_tab;
			/**
			 * Perform action before settings start
			 *
			 * @since 1.0
			 */
			do_action(sanitize_key(self::$plugin_slug . '_settings_start'));

			$tabs = self::get_settings_tabs();
			/* Include admin html settings */
			include_once( 'views/html-settings.php' );
			/**
			 * Perform action after settings end
			 *
			 * @since 1.0
			 */
			do_action(sanitize_key(self::$plugin_slug . '_settings_end'));
		}

		/**
		 * Handles the display of the settings page buttons.
		 * 
		 * @return void
		 */
		public static function output_buttons( $reset = true) {
			global $current_section;

			/* Include admin html settings */
			include_once( 'views/html-setting-buttons.php' );
		}

	}

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	
	
endif;
