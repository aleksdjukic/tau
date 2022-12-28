<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('PDR_Webhook')) {

	class PDR_Webhook {

		public function __construct() {
			add_filter('woocommerce_webhook_topic_hooks', array($this, 'add_topic'));
			add_filter('woocommerce_valid_webhook_events', array($this, 'valid_topics'));
			add_filter('woocommerce_webhook_topics', array($this, 'title_topics'));
			add_action('woocommerce_order_status_completed', array($this, 'trigger_webhook'), 10, 2);
			add_action('woocommerce_order_status_processing', array($this, 'trigger_webhook'), 10, 2);
		}

		public function add_topic( $topics) {
			$new_topic = array(
				'order.is_order_contain_pdr' => array(
					'pdr_order_items_contain_pdr',
				),
			);
			return array_merge($topics, $new_topic);
		}

		public function valid_topics( $topics) {
			$new_events = array(
				'is_order_contain_pdr',
			);
			return array_merge($topics, $new_events);
		}

		public function title_topics( $topics) {

			$new_topics = array(
				'order.is_order_contain_pdr' => 'Order Contain Product Designer',
			);

			return array_merge($topics, $new_topics);
		}

		public function trigger_webhook( $order_id, $order) {
			$order = wc_get_order($order_id);
			$items = $order->get_items();
			foreach ($items as $item) {
				if (is_a($item, 'WC_Order_Item_Product')) { //as in latest line_items, order
					if ($this->check_pdr_product($item->get_product_id())) {
						/**
						 * Action to perform when order items contain product designer
						 *
						 * @since 1.0
						 */
						do_action('pdr_order_items_contain_pdr', $order_id, $order);
						return;
					}
				}
			}
		}

		public function check_pdr_product( $product_id) {
			return true;
		}

	}

	new PDR_Webhook();
}

