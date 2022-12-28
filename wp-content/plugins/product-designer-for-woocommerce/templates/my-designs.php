<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>

<?php
if ( is_array( $designs ) && ! empty( $designs ) ) {

	foreach ( $designs as $each_design ) {
		$design_id  = $each_design->ID ;
		$date       = get_the_date( '' , $each_design ) ;
		$screenshot = get_post_meta( $design_id , 'pdr_my_design_preview' , true ) ;
		if ( $screenshot ) {
			?>

			<div class="ui pdr-my-design-content image">
				<a class="ui red circular pdr-my-design-delete label" data-delete-id="<?php echo esc_attr( $design_id ) ; ?>">X</a>
				<img class ="pdr-my-design-data" data-id="<?php echo esc_attr( $design_id ) ; ?>" src="<?php echo wp_kses_post( $screenshot ) ; ?>" width="100" height="100" />
			</div>


			<?php
		} else {
			?>
			<div class="ui error message">
				<a class="ui red circular pdr-my-design-delete label" data-delete-id="<?php echo esc_attr( $design_id ) ; ?>">X</a>
				<div class="header">
					<?php echo esc_html( pdr_get_designer_no_designs_message() ) ; ?>
				</div>
			</div>

			<?php
		}
	}
} else {
	?>
	<div class="ui error message">
		<div class="header">
			<?php echo esc_html( pdr_get_designer_no_designs_message() ) ; ?>
		</div>
	</div>
	<?php
}

