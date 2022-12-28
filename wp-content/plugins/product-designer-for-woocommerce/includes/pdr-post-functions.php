<?php

/**
 * Post functions.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!function_exists('pdr_create_new_product_base')) {

	/**
	 * Create a new product base.
	 *
	 * @return integer/string
	 */
	function pdr_create_new_product_base( $meta_args, $post_args = array()) {

		$object = new PDR_Product_Base();
		$id = $object->create($meta_args, $post_args);

		return $id;
	}

}

if (!function_exists('pdr_get_product_base')) {

	/**
	 * Get the product base object.
	 *
	 * @return object
	 */
	function pdr_get_product_base( $id) {

		$object = new PDR_Product_Base($id);

		return $object;
	}

}

if (!function_exists('pdr_update_product_base')) {

	/**
	 * Update the product base.
	 *
	 * @return object
	 */
	function pdr_update_product_base( $id, $meta_args, $post_args = array()) {

		$object = new PDR_Product_Base($id);
		$object->update($meta_args, $post_args);

		return $object;
	}

}

if (!function_exists('pdr_delete_product_base')) {

	/**
	 * Delete the product base.
	 *
	 * @return bool
	 */
	function pdr_delete_product_base( $id, $force = true) {

		wp_delete_post($id, $force);

		return true;
	}

}

if (!function_exists('pdr_get_product_base_statuses')) {

	/**
	 * Get the product base statuses.
	 *
	 * @return array
	 */
	function pdr_get_product_base_statuses() {
		/**
		 * Alter the product base statuses in product designer page
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_product_base_statuses', array('publish'));
	}

}

if (!function_exists('pdr_create_new_order')) {

	/**
	 * Create a new order.
	 *
	 * @return integer/string
	 */
	function pdr_create_new_order( $meta_args, $post_args = array()) {

		$object = new PDR_Order();
		$id = $object->create($meta_args, $post_args);

		return $id;
	}

}

if (!function_exists('pdr_get_order')) {

	/**
	 * Get the order object.
	 *
	 * @return object
	 */
	function pdr_get_order( $id) {

		$object = new PDR_Order($id);

		return $object;
	}

}

if (!function_exists('pdr_update_order')) {

	/**
	 * Update the order.
	 *
	 * @return object
	 */
	function pdr_update_order( $id, $meta_args, $post_args = array()) {

		$object = new PDR_Order($id);
		$object->update($meta_args, $post_args);

		return $object;
	}

}

if (!function_exists('pdr_delete_order')) {

	/**
	 * Delete the order.
	 *
	 * @return bool
	 */
	function pdr_delete_order( $id, $force = true) {

		wp_delete_post($id, $force);

		return true;
	}

}

if (!function_exists('pdr_get_order_statuses')) {

	/**
	 * Get the order statuses.
	 *
	 * @return array
	 */
	function pdr_get_order_statuses() {
		/**
		 * Alter Order statuses 
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_order_statuses', array('publish'));
	}

}

if (!function_exists('pdr_create_new_clipart')) {

	/**
	 * Create a new clipart.
	 *
	 * @return integer/string
	 */
	function pdr_create_new_clipart( $meta_args, $post_args = array()) {

		$object = new PDR_Clipart();
		$id = $object->create($meta_args, $post_args);

		return $id;
	}

}

if (!function_exists('pdr_get_clipart')) {

	/**
	 * Get the clipart object.
	 *
	 * @return object
	 */
	function pdr_get_clipart( $id) {

		$object = new PDR_Clipart($id);

		return $object;
	}

}

if (!function_exists('pdr_update_clipart')) {

	/**
	 * Update the clipart.
	 *
	 * @return object
	 */
	function pdr_update_clipart( $id, $meta_args, $post_args = array()) {

		$object = new PDR_Clipart($id);
		$object->update($meta_args, $post_args);

		return $object;
	}

}

if (!function_exists('pdr_delete_clipart')) {

	/**
	 * Delete the clipart.
	 *
	 * @return bool
	 */
	function pdr_delete_clipart( $id, $force = true) {

		wp_delete_post($id, $force);

		return true;
	}

}

if (!function_exists('pdr_get_clipart_statuses')) {

	/**
	 * Get the clipart statuses.
	 *
	 * @return array
	 */
	function pdr_get_clipart_statuses() {
		/**
		 * Alter Clipart statuses 
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_clipart_statuses', array('publish'));
	}

}

if (!function_exists('pdr_create_new_shape')) {

	/**
	 * Create a new shape.
	 *
	 * @return integer/string
	 */
	function pdr_create_new_shape( $meta_args, $post_args = array()) {

		$object = new PDR_Shape();
		$id = $object->create($meta_args, $post_args);

		return $id;
	}

}

if (!function_exists('pdr_get_shape')) {

	/**
	 * Get the shape object.
	 *
	 * @return object
	 */
	function pdr_get_shape( $id) {

		$object = new PDR_Shape($id);

		return $object;
	}

}

if (!function_exists('pdr_update_shape')) {

	/**
	 * Update the shape.
	 *
	 * @return object
	 */
	function pdr_update_shape( $id, $meta_args, $post_args = array()) {

		$object = new PDR_Shape($id);
		$object->update($meta_args, $post_args);

		return $object;
	}

}

if (!function_exists('pdr_delete_shape')) {

	/**
	 * Delete the shape.
	 *
	 * @return bool
	 */
	function pdr_delete_shape( $id, $force = true) {

		wp_delete_post($id, $force);

		return true;
	}

}

if (!function_exists('pdr_get_shape_statuses')) {

	/**
	 * Get the shape statuses.
	 *
	 * @return array
	 */
	function pdr_get_shape_statuses() {
		/**
		 * Alter shape statuses
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_shape_statuses', array('publish'));
	}

}


if (!function_exists('pdr_create_new_design_template')) {

	/**
	 * Create a new design template.
	 *
	 * @return integer/string
	 */
	function pdr_create_new_design_template( $meta_args, $post_args = array()) {

		$object = new PDR_Design_Template();
		$id = $object->create($meta_args, $post_args);

		return $id;
	}

}

if (!function_exists('pdr_get_design_template')) {

	/**
	 * Get the design template object.
	 *
	 * @return object
	 */
	function pdr_get_design_template( $id) {

		$object = new PDR_Design_Template($id);

		return $object;
	}

}

if (!function_exists('pdr_update_design_template')) {

	/**
	 * Update the design template.
	 *
	 * @return object
	 */
	function pdr_update_design_template( $id, $meta_args, $post_args = array()) {

		$object = new PDR_Design_Template($id);
		$object->update($meta_args, $post_args);

		return $object;
	}

}

if (!function_exists('pdr_delete_design_template')) {

	/**
	 * Delete the design template.
	 *
	 * @return bool
	 */
	function pdr_delete_design_template( $id, $force = true) {

		wp_delete_post($id, $force);

		return true;
	}

}

if (!function_exists('pdr_get_design_template_statuses')) {

	/**
	 * Get the design template statuses.
	 *
	 * @return array
	 */
	function pdr_get_design_template_statuses() {
		/**
		 * Alter design template statuses
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_design_template_statuses', array('publish'));
	}

}

if (!function_exists('pdr_get_product_base_rules')) {

	/**
	 * Get the product base rules.
	 *
	 * @return array
	 */
	function pdr_get_product_base_rules( $product_base_id) {

		$rules = get_post_meta($product_base_id, 'pdr_rules', true);

		if (!pdr_check_is_array($rules)) {
			return array();
		}
		/**
		 * Alter product base rules
		 *
		 * @since 1.0
		 */
		return apply_filters('pdr_product_base_rules', $rules, $product_base_id);
	}

}
