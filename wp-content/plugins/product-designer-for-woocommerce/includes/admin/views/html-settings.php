<?php
/**
 *  Settings Template.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 * Hook : pdr_before_setting_wrapper_start.
 *
 * @since 1.0
 */
do_action('pdr_before_setting_wrapper_start');
?>
<div class="wrap pdr-settings-wrap woocommerce">
	<form method="POST" action="options.php" enctype="multipart/form-data" id="pdr-settings-form">
		<div class="pdr-settings-form-wrapper">

			<?php
			/**
			 * Current tab wrapper start perform your custom action with below hook
			 *
			 * @since 1.0
			 */
			do_action('pdr_settings_wrapper_' . $current_tab);
			?>

			<div class="pdr-settings-nav-wrap">
				<nav class="nav-tab-wrapper pdr-nav-tab-wrapper">
						<?php foreach ($tabs as $name => $tab_object) : ?>
						<a href="<?php echo esc_url(pdr_get_settings_page_url(array('tab' => $name))); ?>" class="nav-tab pdr-settings-tab<?php echo ( $name == $current_tab ) ? ' nav-tab-active' : ''; ?>">
							<?php echo esc_html($tab_object->get_label()); ?>
						</a>
<?php endforeach; ?>
				</nav>

				<?php
				/**
				 * Perform action upon tab navigation
				 *
				 * @since 1.0
				 */
				do_action('pdr_settings_navigation_' . $current_tab);
				?>
			</div>

			<div class="pdr-setting-notices">
<?php settings_errors(); ?>
			</div>

			<div class="pdr-settings-content">

				<?php
				do_settings_sections(self::$page_name);

				/**
				 * Perform action to the content section
				 *
				 * @since 1.0
				 */
				do_action('pdr_settings_content_' . $current_tab);
				?>
			</div>

		</div>
	</form>
</div>
<?php
/**
 * Hook : pdr_after_setting_wrapper_end.
 *
 * @since 1.0
 */
do_action('pdr_after_setting_wrapper_end');
