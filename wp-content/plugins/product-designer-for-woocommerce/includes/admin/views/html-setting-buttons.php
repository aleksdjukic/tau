<?php
/**
 * Admin Settings Buttons.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<?php
if ( ! isset( $GLOBALS[ 'hide_save_button' ] ) ) :
	settings_fields( $current_section ) ;
	?>
	<input id='submit' name='submit' class='button button-primary pdr_submit_btn' type='submit' value="<?php esc_attr_e( 'Save Changes' , 'product-designer-for-woocommerce' ) ; ?>"/>
	<?php
endif ;
?>
<?php if ( $reset ) : ?>
	</form>
	<form method='post' action='' enctype='multipart/form-data' id="pdr-reset-form">
		<input id='pdr_reset' class='button-secondary pdr-reset-btn' type='submit' value="<?php esc_attr_e( 'Reset' , 'product-designer-for-woocommerce' ) ; ?>"/>
		<input type="hidden" name="pdr_reset" value="reset"/>
		<?php
		wp_nonce_field( 'pdr_reset_settings' , '_pdr_nonce' , false , true ) ;
	endif;
