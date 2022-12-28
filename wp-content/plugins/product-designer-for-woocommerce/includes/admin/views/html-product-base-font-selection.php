<?php
/**
 * Product base panels.
 */
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="pdr-product-base-font-selection-wrapper pdr-settings-wrapper">
	<i><?php esc_html_e('This option controls which font families customers can use for text customizing. You can use global settings or else override it and use specific font families for this product base', 'product-designer-for-woocommerce'); ?></i>
	<p class="form-field">
		<label for="pdr_font_selection_type"><?php esc_html_e('Usable Font Families', 'product-designer-for-woocommerce'); ?></label>
		<select name="pdr_font_selection_type" id="pdr_font_selection_type">
			<?php
			foreach ($font_selection_type as $key => $value) {
				$selected = $key == $selected_font_type ? 'selected="selected"' : '';
				?>
				<option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($value); ?></option>
				<?php
			}
			?>
		</select>
	</p>
	<p class="form-field">
		<label><?php esc_html_e('Select Font Families', 'product-designer-for-woocommerce'); ?></label>
		<select id="pdr_font_selection_base" class="pdr_font_selection" name="pdr_font_selection[]" multiple="multiple">
			<?php
			if (is_array($get_fonts) && !empty($get_fonts)) {
				foreach ($get_fonts as $each_font) {
					$selected = in_array($each_font, $fonts) ? 'selected="selected"' : '';
					?>
					<option value="<?php echo esc_html($each_font); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($each_font); ?></option>
					<?php
				}
			}
			?>
		</select>
	</p>
</div>
<?php
