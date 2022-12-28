<?php
/**
 * Settings Tab.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('PDR_Settings_Tab')) {

	/**
	 * PDR_Settings_Tab.
	 */
	abstract class PDR_Settings_Tab {

		/**
		 * Setting tab id.
		 * 
		 * @var string
		 */
		protected $id = '';

		/**
		 * Setting tab label.
		 * 
		 * @var string
		 */
		protected $label = '';

		/**
		 * Plugin slug.
		 * 
		 * @var string
		 */
		protected $plugin_slug = 'pdr';

		/**
		 * Page name.
		 * 
		 * @var string
		 */
		protected $page_name = 'pdr_settings';

		/**
		 * Show buttons.
		 * 
		 * @var bool
		 */
		protected $show_buttons = true;

		/**
		 * Show reset button.
		 * 
		 * @var bool
		 */
		protected $show_reset_button = true;

		/**
		 * Setting options.
		 * 
		 * @var array
		 */
		protected $setting_options;

		public function __construct() {

			add_action($this->get_plugin_slug() . '_settings_navigation_' . $this->get_id(), array($this, 'output_sections'));
			add_action($this->get_plugin_slug() . '_settings_content_' . $this->get_id(), array($this, 'output_buttons'), 999);
			add_action($this->get_plugin_slug() . '_settings_wrapper_' . $this->get_id(), array($this, 'reset'));
		}

		/**
		 * Get the settings page ID.
		 */
		public function get_id() {
			return $this->id;
		}

		/**
		 * Get the settings page label.
		 */
		public function get_label() {
			return $this->label;
		}

		/**
		 * Get the plugin slug.
		 */
		public function get_plugin_slug() {
			return $this->plugin_slug;
		}

		/**
		 * Get the page name.
		 */
		public function get_page_name() {
			return $this->page_name;
		}

		/**
		 * Get the setting options.
		 * 
		 * @return array
		 */
		public function get_setting_options() {
			if (isset($this->setting_options)) {
				return $this->setting_options;
			}

			$this->setting_options = $this->prepare_setting_options();

			return $this->setting_options;
		}

		/**
		 * Prepare the setting options.
		 * 
		 * @return array
		 */
		protected function prepare_setting_options() {
			/**
			 * Sanitize settings option 
			 *
			 * @since 1.0
			 */
			return apply_filters(sanitize_key($this->get_plugin_slug() . '_setting_options_' . $this->get_id()), array());
		}

		/**
		 * Register the settings.
		 * 
		 * @return void
		 */
		public function register_settings() {

			if (!pdr_check_is_array($this->get_setting_options())) {
				return;
			}

			foreach ($this->get_setting_options() as $group_name => $options) {

				if (!pdr_check_is_array($options)) {
					continue;
				}

				foreach ($options as $option_name => $option_value) {
					if ('pdr_messages' == $group_name) {
						$args = array(
							'default' => $option_value,
							'sanitize_callback' => 'wp_kses_post',
						);
					} else {
						$args = array(
							'default' => $option_value
						);
					}
					register_setting($group_name, $option_name, $args);
				}
			}
		}

		/**
		 * Add the setting section and fields.
		 * 
		 * @return void
		 */
		public function add_settings_fields( $section) {
			$settings = array();
			$function = 'add_' . $section . '_section_fields';

			if (method_exists($this, $function)) {
				$settings = $this->$function();
			}

			if (!pdr_check_is_array($settings)) {
				return;
			}

			foreach ($settings as $setting) {
				if (!isset($setting['type'])) {
					continue;
				}

				switch ($setting['type']) {
					case 'section':
						$this->add_settings_section($setting);
						break;
					default:
						$this->add_settings_field($setting);
						break;
				}
			}
		}

		/**
		 * Get the sections.
		 * 
		 * @return array
		 */
		public function get_sections() {
			/**
			 * Alter the list of sections available in settings page
			 *
			 * @since 1.0
			 */
			return apply_filters(sanitize_key($this->get_plugin_slug() . '_get_sections_' . $this->get_id()), array());
		}

		/**
		 * Output the sections.
		 * 
		 * @return void
		 */
		public function output_sections() {
			global $current_section;

			$sections = $this->get_sections();

			if (!pdr_check_is_array($sections) || 1 === count($sections)) {
				return;
			}

			$section = '<ul class="subsubsub ' . $this->get_plugin_slug() . '_sections ' . $this->get_plugin_slug() . '_subtab">';

			foreach ($sections as $id => $label) {
				$section .= '<li>'
						. '<a href="' . esc_url(
								pdr_get_settings_page_url(
										array(
											'tab' => $this->get_id(),
											'section' => sanitize_title($id),
										)
								)
						) . '" '
						. 'class="' . ( $current_section == $id ? 'current' : '' ) . '">' . esc_html($label) . '</a></li> | ';
			}

			$section = rtrim($section, '| ');
			$section .= '</ul><br class="clear">';

			echo wp_kses_post($section);
		}

		/**
		 * Output the settings buttons.
		 * 
		 * @return void
		 */
		public function output_buttons() {

			if (!$this->show_buttons) {
				return;
			}

			PDR_Settings_Page::output_buttons($this->show_reset_button);
		}

		/**
		 * Reset settings.
		 */
		public function reset() {
			global $current_section;

			if (!isset($_POST['pdr_reset']) || empty($_POST['pdr_reset'])) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
				return;
			}

			check_admin_referer('pdr_reset_settings', '_pdr_nonce');

			$settings = $this->get_setting_options();
			if (isset($settings[$current_section]) && pdr_check_is_array($settings[$current_section])) {

				foreach ($settings[$current_section] as $option => $value) {
					update_option($option, $value);
				}
			}

			add_settings_error($current_section, 'settings_updated', esc_html__('Your settings have been reset'), 'success');
			/**
			 * Perform some actions when the settings resetted properly
			 *
			 * @since 1.0
			 */
			do_action($this->get_plugin_slug() . '_setting_after_resetted');
		}

		/**
		 * Add the settings section.
		 * 
		 * @return void
		 */
		public function add_settings_section( $args) {

			add_settings_section(
					$args['id'], $args['title'], array($this, 'section_description'), $this->get_page_name()
			);
		}

		/**
		 * Add the settings field.
		 * 
		 * @return void
		 */
		public function add_settings_field( $args) {
			$default_args = array(
				'id' => '',
				'title' => '',
				'section' => 'default'
			);

			$args = wp_parse_args($args, $default_args);
			$description = isset($args['desc']) ? wc_help_tip($args['desc']) : '';
			$title = isset($args['title']) ? '<label>' . $args['title'] . $description . '</label>' : '';

			add_settings_field(
					$args['id'], $title, array($this, 'output_fields'), $this->get_page_name(), $args['section'], $args
			);
		}

		/**
		 * Get the section description.
		 * 
		 * @return string
		 */
		public function section_description() {
			return '';
		}

		/**
		 * Output the settings fields.
		 * 
		 * @return void
		 */
		public function output_fields( $value) {

			if (!isset($value['type'])) {
				return;
			}

			$value['id'] = isset($value['id']) ? $value['id'] : '';
			$value['css'] = isset($value['css']) ? $value['css'] : '';
			$description = isset($value['desc']) ? $value['desc'] : '';
			$description_no_tt = isset($value['description']) ? $value['description'] : '';
			$value['class'] = isset($value['class']) ? $value['class'] : '';
			$value['default'] = isset($value['default']) ? $value['default'] : '';
			$value['name'] = isset($value['name']) ? $value['name'] : $value['id'];
			$value['placeholder'] = isset($value['placeholder']) ? $value['placeholder'] : '';
			$value['custom_attributes'] = isset($value['custom_attributes']) ? $value['custom_attributes'] : '';

			// Custom attribute handling.
			$custom_attributes = pdr_format_custom_attributes($value);
			$option_value = get_option($value['id']);

			// Switch based on type
			switch ($value['type']) {

				case 'text':
				case 'password':
				case 'datetime':
				case 'date':
				case 'month':
				case 'time':
				case 'week':
				case 'number':
				case 'email':
				case 'url':
				case 'tel':
				case 'color':
					?>
					<input
						name="<?php echo esc_attr($value['name']); ?>"
						id="<?php echo esc_attr($value['id']); ?>"
						type="<?php echo esc_attr($value['type']); ?>"
						style="<?php echo esc_attr($value['css']); ?>"
						value="<?php echo esc_attr($option_value); ?>"
						class="<?php echo esc_attr($value['class']); ?>"
						placeholder="<?php echo esc_attr($value['placeholder']); ?>"
						<?php echo wp_kses_post(implode(' ', $custom_attributes)); ?>
						/>

					<?php
					break;

				//Text area
				case 'textarea':
					?>

					<textarea
						name="<?php echo esc_attr($value['name']); ?>"
						id="<?php echo esc_attr($value['id']); ?>"
						style="<?php echo esc_attr($value['css']); ?>"
						class="<?php echo esc_attr($value['class']); ?>"
						placeholder="<?php echo esc_attr($value['placeholder']); ?>"
						<?php echo wp_kses_post(implode(' ', $custom_attributes)); ?>
						><?php echo esc_html($option_value); ?></textarea>
					<p><?php echo wp_kses_post(htmlspecialchars($description_no_tt)); ?></p>

					<?php
					break;

				// Radio inputs.
				case 'radio':
					?>
					<fieldset>
						<ul>
							<?php
							foreach ($value['options'] as $key => $val) {
								?>
								<li>
									<label><input
											name="<?php echo esc_attr($value['name']); ?>"
											value="<?php echo esc_attr($key); ?>"
											type="radio"
											style="<?php echo esc_attr($value['css']); ?>"
											class="<?php echo esc_attr($value['class']); ?>"
											<?php echo wp_kses_post(implode(' ', $custom_attributes)); // WPCS: XSS ok. ?>
											<?php checked($key, $option_value); ?>
											/> <?php echo esc_html($val); ?></label>
								</li>
								<?php
							}
							?>
						</ul>
					</fieldset>
					<?php
					break;

				// Checkbox input.
				case 'checkbox':
					?>
					<fieldset>
						<label for="<?php echo esc_attr($value['id']); ?>">
							<input
								name="<?php echo esc_attr($value['name']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="checkbox"
								class="<?php echo esc_attr(isset($value['class']) ? $value['class'] : '' ); ?>"
								value="1"
								<?php checked('1', $option_value); ?>
								<?php echo wp_kses_post(implode(' ', $custom_attributes)); ?>
								/>
						</label> 
					</fieldset>
					<?php
					break;

				// Select boxes.
				case 'select':
				case 'multiselect':
					?>

					<select
						name="<?php echo esc_attr($value['name']); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
						id="<?php echo esc_attr($value['id']); ?>"
						style="<?php echo esc_attr($value['css']); ?>"
						data-placeholder="<?php echo isset($value['placeholder']) ? esc_attr($value['placeholder']) : ''; ?>"
						class="<?php echo esc_attr($value['class']); ?>"
						<?php echo wp_kses_post(implode(' ', $custom_attributes)); ?>
						<?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
						>
							<?php
							if (pdr_check_is_array($value['options'])) {
								foreach ($value['options'] as $key => $val) {
									?>
								<option value="<?php echo esc_attr($key); ?>"
									<?php
									if (is_array($option_value)) {
										selected(in_array((string) $key, $option_value, true), true);
									} else {
										selected($option_value, (string) $key);
									}
									?>
										>
									<?php echo esc_html($val); ?></option>
									<?php
								}
							}
							?>
					</select>

					<?php
					break;

				case 'ajaxmultiselect':
					$value['options'] = $option_value;
					pdr_select2_html($value);
					break;

				case 'wpeditor':
					wp_editor(
							$option_value, $value['id'], array(
						'media_buttons' => false,
						'editor_class' => esc_attr($value['class']),
							)
					);
					break;
				case 'wpmedia':
					?>
					<div class="pdr-setting-upload-img-container">
						<input type="hidden" class="pdr-setting-upload-img-url" name="<?php echo esc_attr($value['name']); ?>" value="<?php echo esc_attr($option_value); ?>"/>

						<div class="pdr-setting-uploaded-img">
							<img id="target" src="<?php echo esc_url($option_value); ?>" class="pdr-setting-uploaded-img-preview"/>
						</div>
						<button type="button" class="pdr-setting-upload-img"><?php esc_html_e('Select a Image', 'product-designer-for-woocommerce'); ?></button>
						<button type="button" class="pdr-setting-delete-uploaded-img<?php echo ( '' == $option_value ) ? esc_attr(' pdr_hide') : ''; ?>"><?php esc_html_e('Delete Image', 'product-designer-for-woocommerce'); ?></button>
					</div>
					<?php
					break;
				default:
					/**
					 * Add custom field functionality when no type specified for this function
					 *
					 * @since 1.0
					 */
					do_action($this->get_plugin_slug() . '_setting_custom_field_' . $value['type']);
					break;
			}
		}

	}

}
