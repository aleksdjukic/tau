<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('PDR_Custom_PostType')) :

	class PDR_Custom_PostType {

		public function __construct() {
			add_action('init', array($this, 'register_custom_taxonomies'));
			add_action('init', array($this, 'register_post_type'));
			add_filter('user_can_richedit', array($this, 'disable_visual_editor_for_shapes'));
		}

		/*
		 * Register Custom Taxonomies
		 */

		public static function register_custom_taxonomies() {
			if (!is_blog_installed()) {
				return;
			}

			$custom_taxonomies = array(
				'pdr_product_base_cat' => array('PDR_Custom_PostType', 'product_base_category_args'),
				'pdr_clipart_cat' => array('PDR_Custom_PostType', 'clipart_category_args'),
				'pdr_design_template_cat' => array('PDR_Custom_PostType', 'design_template_category_args'),
				'pdr_shapes_cat' => array('PDR_Custom_PostType', 'shapes_category_args'),
			);
			/**
			 * Register/Alter Custom Taxonomies
			 *
			 * @since 1.0
			 */
			$custom_taxonomies = apply_filters('pdr_custom_taxonomies', $custom_taxonomies);

			// Return if no taxonomy to register.
			if (!pdr_check_is_array($custom_taxonomies)) {
				return;
			}

			foreach ($custom_taxonomies as $taxonomy => $args_function) {

				$args = array();
				if ($args_function) {
					$args = call_user_func_array($args_function, $args);
				}

				//Register custom Taxonomy
				register_taxonomy($taxonomy, $args['object_type'], $args['args']);
			}
		}

		public function register_post_type() {
			/**
			 * Create/Alter custom post type
			 *
			 * @since 1.0
			 */
			$list_of_post_types = apply_filters('pdr_register_post_type', array(
				'pdr_product_base' => array(
					'labels' => array(
						'name' => __('Product Base', 'product-designer-for-woocommerce'),
						'singular_name' => __('Product Base', 'product-designer-for-woocommerce'),
					),
					'show_ui' => true,
					'show_in_menu' => 'product-designer-for-woocommerce',
					'supports' => array('title'),
					'taxonomies' => array('pdr_product_base_cat')
				),
				'pdr_design_templates' => array(
					'labels' => array(
						'name' => __('Design Templates', 'product-designer-for-woocommerce'),
						'singular_name' => __('Design Templates', 'product-designer-for-woocommerce'),
					),
					'show_ui' => true,
					'show_in_menu' => 'product-designer-for-woocommerce',
					'supports' => array('title'),
					'taxonomies' => array('pdr_design_template_cat', 'pdr_design_template_tag')
				),
				'pdr_cliparts' => array(
					'labels' => array(
						'name' => __('Cliparts', 'product-designer-for-woocommerce'),
						'singular_name' => __('Cliparts', 'product-designer-for-woocommerce'),
					),
					'show_ui' => true,
					'show_in_menu' => 'product-designer-for-woocommerce',
					'supports' => array('title', 'thumbnail'),
					'taxonomies' => array('pdr_clipart_cat', 'pdr_clipart_tag')
				),
				'pdr_shapes' => array(
					'labels' => array(
						'name' => __('Shapes', 'product-designer-for-woocommerce'),
						'singular_name' => __('Shapes', 'product-designer-for-woocommerce'),
					),
					'show_ui' => true,
					'show_in_menu' => 'product-designer-for-woocommerce',
					'taxonomies' => array('pdr_shapes_cat')
				),
				'pdr_my_designs' => array(
					'labels' => array(
						'name' => __('My Designs', 'product-designer-for-woocommerce'),
						'singular_name' => __('My Designs', 'product-designer-for-woocommerce'),
					),
					'show_ui' => false,
					'show_in_menu' => false,
				),
				'pdr_orders' => array(
					'labels' => array(
						'name' => __('Orders', 'product-designer-for-woocommerce'),
						'singular_name' => __('Orders', 'product-designer-for-woocommerce'),
					),
					'show_ui' => true,
					'show_in_menu' => 'product-designer-for-woocommerce',
					'supports' => array('title'),
				),
			));

			foreach ($list_of_post_types as $each_type => $each_args) {
				register_post_type($each_type, $each_args);
			}
		}

		public function disable_visual_editor_for_shapes( $can) {
			global $post;
			$post_type = get_post_type($post);
			if ('pdr_shapes' == $post_type) {
				return false;
			}
			return $can;
		}

		/**
		 * Prepare product base category arguments.
		 * 
		 * @return array
		 */
		public static function product_base_category_args() {

			$args = array(
				'hierarchical' => true,
				'label' => esc_html__('Categories', 'product-designer-for-woocommerce'),
				'labels' => array(
					'name' => esc_html__('Product Base categories', 'product-designer-for-woocommerce'),
					'singular_name' => esc_html__('Product Base Category', 'product-designer-for-woocommerce'),
					'menu_name' => esc_html_x('Categories', 'Admin menu name', 'product-designer-for-woocommerce'),
					'search_items' => esc_html__('Search categories', 'product-designer-for-woocommerce'),
					'all_items' => esc_html__('All categories', 'product-designer-for-woocommerce'),
					'parent_item' => esc_html__('Parent category', 'product-designer-for-woocommerce'),
					'parent_item_colon' => esc_html__('Parent category:', 'product-designer-for-woocommerce'),
					'edit_item' => esc_html__('Edit category', 'product-designer-for-woocommerce'),
					'update_item' => esc_html__('Update category', 'product-designer-for-woocommerce'),
					'add_new_item' => esc_html__('Add new category', 'product-designer-for-woocommerce'),
					'new_item_name' => esc_html__('New category name', 'product-designer-for-woocommerce'),
					'not_found' => esc_html__('No categories found', 'product-designer-for-woocommerce'),
				),
				'show_ui' => true,
				'query_var' => true,
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'edit_terms' => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts'
				),
				'rewrite' => array(
					'slug' => 'product_base_category',
					'with_front' => false,
					'hierarchical' => true,
				),
			);

			/**
			 * Product Base Category Taxonomy Args
			 *
			 * @since 1.0
			 */
			return apply_filters('pdr_product_base_category_args', array('object_type' => array('pdr_product_base'), 'args' => $args));
		}

		/**
		 * Prepare clipart category arguments.
		 * 
		 * @return array
		 */
		public static function clipart_category_args() {

			$args = array(
				'hierarchical' => true,
				'label' => esc_html__('Categories', 'product-designer-for-woocommerce'),
				'labels' => array(
					'name' => esc_html__('Clipart categories', 'product-designer-for-woocommerce'),
					'singular_name' => esc_html__('Clipart Category', 'product-designer-for-woocommerce'),
					'menu_name' => esc_html_x('Categories', 'Admin menu name', 'product-designer-for-woocommerce'),
					'search_items' => esc_html__('Search categories', 'product-designer-for-woocommerce'),
					'all_items' => esc_html__('All categories', 'product-designer-for-woocommerce'),
					'parent_item' => esc_html__('Parent category', 'product-designer-for-woocommerce'),
					'parent_item_colon' => esc_html__('Parent category:', 'product-designer-for-woocommerce'),
					'edit_item' => esc_html__('Edit category', 'product-designer-for-woocommerce'),
					'update_item' => esc_html__('Update category', 'product-designer-for-woocommerce'),
					'add_new_item' => esc_html__('Add new category', 'product-designer-for-woocommerce'),
					'new_item_name' => esc_html__('New category name', 'product-designer-for-woocommerce'),
					'not_found' => esc_html__('No categories found', 'product-designer-for-woocommerce'),
				),
				'show_ui' => true,
				'query_var' => true,
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'edit_terms' => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts'
				),
				'rewrite' => array(
					'slug' => 'clipart_category',
					'with_front' => false,
					'hierarchical' => true,
				),
			);

			/**
			 * Clipart taxonomy args
			 *
			 * @since 1.0
			 */
			return apply_filters('pdr_clipart_category_args', array('object_type' => array('pdr_cliparts'), 'args' => $args));
		}

		/**
		 * Prepare design template category arguments.
		 * 
		 * @return array
		 */
		public static function design_template_category_args() {

			$args = array(
				'hierarchical' => true,
				'label' => esc_html__('Categories', 'product-designer-for-woocommerce'),
				'labels' => array(
					'name' => esc_html__('Design Template categories', 'product-designer-for-woocommerce'),
					'singular_name' => esc_html__('Design Template Category', 'product-designer-for-woocommerce'),
					'menu_name' => esc_html_x('Categories', 'Admin menu name', 'product-designer-for-woocommerce'),
					'search_items' => esc_html__('Search categories', 'product-designer-for-woocommerce'),
					'all_items' => esc_html__('All categories', 'product-designer-for-woocommerce'),
					'parent_item' => esc_html__('Parent category', 'product-designer-for-woocommerce'),
					'parent_item_colon' => esc_html__('Parent category:', 'product-designer-for-woocommerce'),
					'edit_item' => esc_html__('Edit category', 'product-designer-for-woocommerce'),
					'update_item' => esc_html__('Update category', 'product-designer-for-woocommerce'),
					'add_new_item' => esc_html__('Add new category', 'product-designer-for-woocommerce'),
					'new_item_name' => esc_html__('New category name', 'product-designer-for-woocommerce'),
					'not_found' => esc_html__('No categories found', 'product-designer-for-woocommerce'),
				),
				'show_ui' => true,
				'query_var' => true,
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'edit_terms' => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts'
				),
				'rewrite' => array(
					'slug' => 'design_template_category',
					'with_front' => false,
					'hierarchical' => true,
				),
			);

			/**
			 * Alter Template Category Args
			 *
			 * @since 1.0
			 */
			return apply_filters('pdr_design_template_category_args', array('object_type' => array('pdr_design_templates'), 'args' => $args));
		}

		public static function shapes_category_args() {

			$args = array(
				'hierarchical' => true,
				'label' => esc_html__('Categories', 'product-designer-for-woocommerce'),
				'labels' => array(
					'name' => esc_html__('Shape categories', 'product-designer-for-woocommerce'),
					'singular_name' => esc_html__('Shape Category', 'product-designer-for-woocommerce'),
					'menu_name' => esc_html_x('Categories', 'Admin menu name', 'product-designer-for-woocommerce'),
					'search_items' => esc_html__('Search categories', 'product-designer-for-woocommerce'),
					'all_items' => esc_html__('All categories', 'product-designer-for-woocommerce'),
					'parent_item' => esc_html__('Parent category', 'product-designer-for-woocommerce'),
					'parent_item_colon' => esc_html__('Parent category:', 'product-designer-for-woocommerce'),
					'edit_item' => esc_html__('Edit category', 'product-designer-for-woocommerce'),
					'update_item' => esc_html__('Update category', 'product-designer-for-woocommerce'),
					'add_new_item' => esc_html__('Add new category', 'product-designer-for-woocommerce'),
					'new_item_name' => esc_html__('New category name', 'product-designer-for-woocommerce'),
					'not_found' => esc_html__('No categories found', 'product-designer-for-woocommerce'),
				),
				'show_ui' => true,
				'query_var' => true,
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'edit_terms' => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts'
				),
				'rewrite' => array(
					'slug' => 'shape_category',
					'with_front' => false,
					'hierarchical' => true,
				),
			);
			/**
			 * Alter Shape Category Args
			 *
			 * @since 1.0
			 */
			return apply_filters('pdr_shapes_category_args', array('object_type' => array('pdr_shapes'), 'args' => $args));
		}

	}

	new PDR_Custom_PostType();
	

endif;
