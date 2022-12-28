<?php
/**
 * Modules table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>

<div class="pdr-modules-wrapper">
	<h2><?php esc_html_e( 'Modules' , 'product-designer-for-woocommerce' ) ; ?></h2>
	<table class="pdr-modules-table widefat">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Module' , 'product-designer-for-woocommerce' ) ; ?></th>
				<th><?php esc_html_e( 'Action' , 'product-designer-for-woocommerce' ) ; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( pdr_check_is_array( $modules ) ) : ?>
				<?php
				foreach ( $modules as $section => $module ) :
					$is_enabled = get_option( $module[ 'option_name' ] ) ;
					//$is_active  = '1' == $is_enabled ? esc_html__( 'Enabled' , 'product-designer-for-woocommerce' ) : esc_html__( 'Disabled' , 'product-designer-for-woocommerce' ) ;
					?>
					<tr>
						<td><?php echo esc_html( $module[ 'name' ] ); ?></td>
						<td>
							<?php
							$enable_label  = ( '1' == $is_enabled ) ? esc_html__( 'Disable' , 'product-designer-for-woocommerce' ) : esc_html__( 'Enable' , 'product-designer-for-woocommerce' ) ;
							$module_action = ( '1' == $is_enabled ) ? 'disbale' : 'enable' ;
							?>
							<button type="button" 
									class="button pdr-modules-enable-btn pdr-modules-btn" 
									data-action="<?php echo esc_attr( $module_action ) ; ?>"  
									data-key="<?php echo esc_attr( $module[ 'option_name' ] ) ; ?>">
					<?php echo esc_html( $enable_label ) ; ?>
							</button>

					<?php if ( $module[ 'customize' ] ) : ?>
								<a href="<?php echo esc_url( pdr_get_settings_page_url( array( 'tab' => 'pdr_modules' , 'section' => $section ) ) ) ; ?>" class="button pdr-modules-btn pdr-modules-customize-btn"><?php esc_html_e( 'Customize' , 'product-designer-for-woocommerce' ) ; ?></a>
							<?php endif ; ?>
						</td>
					</tr>
	<?php endforeach ; ?>
			<?php endif ; ?>
		</tbody>
	</table>
</div>
<?php
