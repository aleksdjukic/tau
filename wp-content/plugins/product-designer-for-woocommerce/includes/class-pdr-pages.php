<?php

/**
 * Pages.
 * */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('PDR_Pages')) {

	/**
	 * Class.
	 */
	class PDR_Pages {

		/**
		 * Plugin Slug.
		 * 
		 * @var string
		 */
		protected static $plugin_slug = 'pdr';

		/**
		 * Create the pages.
		 * */
		public static function create_pages() {
			/**
			 * Create/alter new pages 
			 *
			 * @since 1.0
			 */
			$pages = apply_filters(
					self::$plugin_slug . '_create_pages', array(
				'designer' => array(
					'name' => esc_html_x('pdr_designer', 'Page slug', 'product-designer-for-woocommerce'),
					'title' => esc_html_x('Product Designer', 'Page title', 'product-designer-for-woocommerce'),
					'content' => '',
					'option' => 'pdr_product_designer_page_id'
					)));

			if (!pdr_check_is_array($pages)) {
				return;
			}

			foreach ($pages as $page_args) {
				self::create($page_args);
			}
		}

		/**
		 * Create a page.
		 * 
		 * @return int.
		 * */
		public static function create( $page_args = array()) {

			$defalut_page_args = array(
				'name' => '',
				'title' => '',
				'content' => '',
				'option' => '',
					);

			$page_args = wp_parse_args($page_args, $defalut_page_args);

			$option_value = get_option($page_args['option']);
			$page_object = get_post($option_value);

			if (!empty($page_args['option']) && $page_object) {
				if ('page' == $page_object->post_type) {
					if (!in_array($page_object->post_status, array('pending', 'trash', 'future', 'auto-draft'))) {
						return $page_object->ID;
					}
				}
			}

			$page_data = array(
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_author' => 1,
				'post_name' => esc_sql($page_args['name']),
				'post_title' => $page_args['title'],
				'post_content' => $page_args['content'],
				'comment_status' => 'closed',
					);

			$page_id = wp_insert_post($page_data);

			if ($page_args['option']) {
				update_option($page_args['option'], $page_id);
			}

			return $page_id;
		}

		/**
		 * Class Initialization.
		 * */
		public static function init() {
			add_filter('display_post_states', array(__CLASS__, 'post_states'), 10, 2);
		}

		/**
		 * Denotes the post states as such in the pages list table.
		 * 
		 * @return array.
		 * */
		public static function post_states( $post_states, $post) {

			if (get_option('pdr_product_designer_page_id') == $post->ID) {
				$post_states[self::$plugin_slug . '_designer_page'] = esc_html__('Product Designer Page', 'product-designer-for-woocommerce');
			}

			return $post_states;
		}

	}

	PDR_Pages::init();
}
