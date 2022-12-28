<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('PDR_Template')) {

	class PDR_Template {

		private $default_dir = 'product-designer-for-woocommerce/';

		public function __construct( $template_name, $args = '', $template_path = '', $default_path = '') {
			$this->template_name = $template_name;
			$this->args = $args;
			$this->template_path = $template_path;
			$this->default_path = $default_path;
		}

		private function locate_template() {
			if (!$this->template_path) {
				$template_path = $this->default_dir;
			}

			// Set plugin template path
			if (!$this->default_path) {
				$default_path = PDR_PLUGIN_DIR . 'templates/'; // Path to the template folder
			}

			// Search template file in theme folder.
			$template = locate_template(array(
				$template_path . $this->template_name,
				$this->template_name
					));

			// Get plugins template file.
			if (!$template) {
				$template = $default_path . $this->template_name;
			}

			/**
			 * Alter loading template using below filter
			 *
			 * @since 1.0
			 */
			return apply_filters('pdr_locate_template', $template, $this->template_name, $template_path, $default_path);
		}

		public function get_template() {

			// Validate the user restriction.
			if (!PDR_Frontend::validate_user_restriction()) {
				wp_die(esc_html__("Sorry you're not allowed to customize products, please contact admin for further details", 'product-designer-for-woocommerce'));
				return;
			}

			if (is_array($this->args) && isset($this->args)) {
				extract($this->args);
			}

			$template_file = $this->locate_template();

			if (!file_exists($template_file)) {
				_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> Unable to find specific template', esc_html($template_file)), '1.0.0');
				return;
			}

			include $template_file;
		}

	}

}

