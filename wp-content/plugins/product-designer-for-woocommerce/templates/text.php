<?php
if (!defined('ABSPATH')) {
	exit;
}
$hide_price_is_zero = 0 == $price && 1 == $hide_price_label ? false : true;
?>
<div class="ui form">
	<div class="field">
		<?php if ($hide_price_is_zero) { ?>	
			<a class="ui green ribbon label"><?php echo wp_kses_post($price_html); ?></a>
		<?php } ?>
		<textarea id="pdr-textarea"><?php echo esc_html(pdr_get_designer_text_default_message()); ?></textarea>
	</div>
	<div class="field">
		<button id="pdr-text-add-new" class="positive right floated ui button"><?php echo esc_html(pdr_get_designer_text_add_new_label()); ?></button>
	</div>
</div>
