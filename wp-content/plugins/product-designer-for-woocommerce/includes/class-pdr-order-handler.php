<?php

/**
 * Handles the Order.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('PDR_Order_Handler')) {

	/**
	 * Class.
	 */
	class PDR_Order_Handler {

		/**
		 * Class Initialization.
		 */
		public static function init() {

			// Update order meta.
			add_action('woocommerce_checkout_create_order_line_item', array(__CLASS__, 'adjust_order_item'), 10, 4);
			// Create the orders.
			add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'create_orders'), 10);
			// Hide the order item meta key.
			add_action('woocommerce_hidden_order_itemmeta', array(__CLASS__, 'hide_order_item_meta_key'), 10, 2);
			// Display the order item custom fields.
			add_action('woocommerce_order_item_meta_start', array(__CLASS__, 'display_custom_fields'), 10, 3);

			//perform action upon delete order either woocommerce/or our pdr order
			add_action('before_delete_post', array(__CLASS__, 'delete_post_order'), 10, 2);
		}

		/**
		 * Adjust the order item meta.
		 * 
		 * @return void
		 */
		public static function adjust_order_item( $item, $cart_item_key, $values, $order) {
			if (!isset($values['pdr_product_designer'])) {
				return;
			}

			$item->add_meta_data('_pdr_product_customized', 'yes');

			foreach ($values['pdr_product_designer'] as $each_data => $each_value) {
				$item->add_meta_data('_pdr_' . $each_data, $each_value);
			}
		}

		/**
		 * Create the orders.
		 * 
		 * @return void
		 * */
		public static function create_orders( $order_id) {
			$order = wc_get_order($order_id);
			if (!is_object($order)) {
				return;
			}

			foreach ($order->get_items() as $key => $value) {
				// Check the product is customized.
				if (!isset($value['pdr_product_customized'])) {
					continue;
				}

				$product_id = !empty($value['variation_id']) ? $value['variation_id'] : $value['product_id'];

				$meta_data = array(
					'pdr_user_id' => $order->get_customer_id(),
					'pdr_wc_order_id' => $order_id,
					'pdr_product_base' => $value['pdr_product_base'],
					'pdr_product_base_id' => $value['pdr_product_base_id'],
					'pdr_quantity' => $value['quantity'],
					'pdr_product_price' => $value['pdr_price'],
					'pdr_user_name' => $order->get_formatted_billing_full_name(),
					'pdr_user_email' => $order->get_billing_email(),
					'pdr_canvas_data' => $value['pdr_canvas_data'],
					'pdr_shapes' => $value['pdr_shapes'],
					'pdr_cliparts' => $value['pdr_cliparts'],
					'pdr_text' => $value['pdr_text'],
					'pdr_data' => $value['pdr_data'],
					'pdr_img_url' => $value['pdr_img_url'],
					'pdr_item_id' => $key,
				);

				$file_api = new PDR_File('', $value['pdr_canvas_data']);
				$data = $file_api->retrieve();
				$post_data = array(
					'post_parent' => $product_id,
					'post_content' => $data,
					'post_status' => 'publish',
				);

				// Create a order.
				$pdr_order_id = pdr_create_new_order($meta_data, $post_data);
				wc_add_order_item_meta($key, '_pdr_order_id', $pdr_order_id);
				//get attributes_data
				$order_data = array();
				$order_data = pdr_attributes_in_readable_format($order_data, $value['pdr_product_base_id'], $value['pdr_data']);
				if (is_array($order_data) && !empty($order_data)) {
					foreach ($order_data as $each_order_key => $each_order_value) {
						wc_add_order_item_meta($key, $each_order_value['key'], $each_order_value['value']);
					}
				}
			}
		}

		/**
		 * Hidden the Custom Order item meta.
		 * 
		 * @return void
		 * */
		public static function hide_order_item_meta_key( $hidden_order_itemmeta) {
			/**
			 * Filter to hide order item meta key add additional keys that you want to hide
			 *
			 * @since 1.0
			 */
			$custom_order_itemmeta = apply_filters('pdr_hide_order_item_meta_key', array(
				'_pdr_product_customized',
				'_pdr_product_id',
				'_pdr_price',
				'_pdr_qty',
				'_pdr_product_price',
				'_pdr_product_base',
				'_pdr_product_base_id',
				'_pdr_img_url',
				'_pdr_canvas_data',
				'_pdr_shapes',
				'_pdr_cliparts',
				'_pdr_text',
				'_pdr_data',
				'_pdr_bg',
				'_pdr_order_id'
			));

			return array_merge($hidden_order_itemmeta, $custom_order_itemmeta);
		}

		/**
		 * Display the order item custom fields.
		 * 
		 * @return void
		 * */
		public static function display_custom_fields( $item_id, $item, $order) {
			// Return if the item is not product customized.
			if (!isset($item['pdr_product_customized'])) {
				return;
			}

			$product_id = !empty($item['variation_id']) ? $item['variation_id'] : $item['product_id'];
			$url = get_permalink(get_option('pdr_product_designer_page_id'));
			$design_editor_url = add_query_arg(array('product_id' => $product_id, 'pdr_id' => $item['pdr_product_base_id'], 'order_key' => $order->get_order_key(), 'order_id' => $item['pdr_order_id']), $url);

			echo '<div class="pdr-item-edit-design-wrapper">';
			echo '<a href="' . esc_url($design_editor_url) . '" class="pdr-item-edit-design">' . esc_html__('Download Design', 'product-designer-for-woocommerce') . '</a>';
			echo '</div>';
		}

		/**
		 * Delete files from server upon delete of product designer order
		 */
		public static function delete_post_order( $pdr_id, $pdr_order) {
			if ('pdr_orders' == $pdr_order->post_type) {
				$image_data = get_post_meta($pdr_id, 'pdr_img_url', true);
				$canvas_obj = get_post_meta($pdr_id, 'pdr_canvas_data', true);
				$file_api = new PDR_File('', $image_data);
				$file_api->delete();
				$file_api = new PDR_File('', $canvas_obj);
				$file_api->delete();
			}
		}

	}

	PDR_Order_Handler::init();
}
