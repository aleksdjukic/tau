<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">-->
		<title><?php echo esc_html( pdr_get_site_title() ); ?></title>
		<meta name="description" content="<?php esc_html_e( 'Product Designer for WooCommerce', 'product-designer-for-woocommerce' ); ?>">
		<link rel="icon" href="<?php echo esc_url( pdr_get_favicon_url() ); ?>" sizes="32x32">
		<?php
		/**
		 * Perform action in head tag of product designer page
		 *
		 * @since 1.0
		 */
		do_action( 'pdr_enqueue_styles' );
		?>
		<style type="text/css" id="pdr_frontend_google"></style>
	</head>
	<body>

		<div class="ui text loader" id="pdr_pre_loader"><?php esc_attr_e( 'Loading All Views', 'product-designer-for-woocommerce' ); ?></div>

		<div class="ui borderless sticky stackable menu">
			<div class="ui fluid container">
				<a href="#" class="header item">
					<img class="logo" src="<?php echo esc_url( pdr_get_logo_url() ); ?>">
				</a>
				<div class="ui simple dropdown item">
					<i class="file icon"></i> <?php echo esc_html( pdr_get_designer_file_label() ); ?>
					<i class="dropdown icon"></i>
					<div class="menu">
						<a class="item pdr-export-file"><i class="save icon"></i> <?php echo esc_html( pdr_get_designer_export_file_label() ); ?></a>
						<a class="item pdr-export-template"><i class="external icon"></i> <?php echo esc_html( pdr_get_designer_export_template_label() ); ?></a>
					</div>
				</div>

				<a class="item pdr-print">
					<i class="grid print icon"></i> <?php echo esc_html( pdr_get_designer_print_label() ); ?>
				</a>
				<div class="ui pdr-print-data flowing popup transition hidden">
					<div class="ui pdr-print-functionality pointing secondary menu">
						<a class="item active" data-tab="png"><?php esc_html_e( 'PNG', 'product-designer-for-woocommerce' ); ?></a>
						<!--<a class="item" data-tab="pdf"><?php esc_html_e( 'PDF', 'product-designer-for-woocommerce' ); ?></a>-->
					</div>
					<div class="ui tab segment active" data-tab="png">
						<div class="ui form">
							<div class="inline fields">
								<div class="eight wide field">
									<label><?php esc_html_e( 'Paper', 'product-designer-for-woocommerce' ); ?></label>
									<select class="ui pdr-print-paper-size dropdown" name="pdr-print-paper-size">
										<?php
										$range = range( 1, 10 );
										rsort( $range );
										if ( is_array( $range ) && ! empty( $range ) ) {
											foreach ( $range as $each_size ) {
												?>
												<option value="a<?php echo esc_attr( $each_size ); ?>">A<?php echo esc_html( $each_size ); ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="eight wide field">
									<label><?php esc_html_e( 'in', 'product-designer-for-woocommerce' ); ?></label>
									<select class="ui pdr-print-paper-measure dropdown" name="pdr-print-paper-measure">
										<?php
										$paper_size = array( 'mm' => 'mm', 'inch' => 'inch' );
										foreach ( $paper_size as $pkey => $pvalue ) {
											?>
											<option value="<?php echo esc_attr( $pkey ); ?>"><?php echo esc_html( $pvalue ); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="inline fields">
								<div class="eight wide field">
									<label><?php esc_html_e( 'W:', 'product-designer-for-woocommerce' ); ?></label>
									<input type="number" step="0.01"  id="pdr-print-data-width" name="pdr-print-data-width" value=""/>
								</div>
								<div class="eight wide field">
									<label><?php esc_html_e( 'H:', 'product-designer-for-woocommerce' ); ?></label>
									<input type="number" step="0.01" id="pdr-print-data-height" name="pdr-print-data-height" value=""/>
								</div>
							</div>

							<div class="inline fields">
								<div class="sixteen wide field">
									<div class="ui toggle checkbox">
										<input type="checkbox" class="pdr-print-data-include-base" name="pdr-print-data-include-base" value="1"/>
										<label><?php esc_html_e( 'Include Base', 'product-designer-for-woocommerce' ); ?></label>
									</div>
								</div>
							</div>

							<div class="field">
								<button class="ui pdr-download-action primary button">
									<i class="download icon"></i>
									<?php esc_html_e( 'Download', 'product-designer-for-woocommerce' ); ?>
								</button>
								<button class="ui pdr-download-pdf-action primary button">
									<i class="file pdf icon"></i>
									<?php esc_html_e( 'Download as PDF', 'product-designer-for-woocommerce' ); ?>
								</button>
								<button class="ui pdr-print-action secondary right floated button">
									<i class="print icon"></i>
									<?php esc_html_e( 'Print', 'product-designer-for-woocommerce' ); ?>
								</button>
							</div>

						</div>
					</div>

				</div>

				<?php if ( is_user_logged_in() ) { ?>
					<a class="item pdr-my-design">
						<i class="grid database icon"></i> <?php echo esc_html( pdr_get_designer_my_designs_label() ); ?>
					</a>
				<?php } ?>

				<div class="ui pdr-my-designs flowing popup autoprefixer transition hidden">
					<div class="ui four column">
						<?php
						/**
						 * Frontend tab My design action 
						 *
						 * @since 1.0
						 */
						do_action( 'pdr_frontend_tab_my_designs', $pdr_id, $product_id );
						?>
					</div>
				</div>

				<?php
				$product_price = $get_price;
				?>

				<div class="right item">

					<?php
					if ( ! empty( $cart_item ) ) :

						$cart_button_msg = pdr_get_designer_update_cart_label();
					else :
						$cart_button_msg = pdr_get_designer_add_to_cart_label();
					endif;
					?>
					<div class="button">
						<div class="ui labeled button" tabindex="0">
							<div class="ui basic green button">
								<i class="money bill alternate outline icon"></i>
							</div>
							<a class="ui basic left pointing green label">
								<div class="pdr-product-total-price"><?php echo wp_kses_post( pdr_price( $product_price ) ); ?></div>
							</a>
						</div>
						<?php if ( '1' == $show_redirect_single_product_btn ) { ?>
							<div class="ui primary pdr-single-product-button animated fade button" data-action="session">
								<div class="visible content"><i class="backward icon"></i> 
									<?php echo esc_html( $redirect_to_single_pp_label ); ?>
								</div>
								<div class="hidden content">
									<i class="backward icon"></i> 
									<?php echo esc_html( $redirect_to_single_pp_label ); ?>
								</div>
							</div>
						<?php } if ( '1' != $hide_add_to_cart ) { ?>
							<div class="ui primary pdr-cart-button animated fade button" data-action="cart">
								<div class="visible content"><i class="cart icon"></i> 
									<?php echo esc_html( $cart_button_msg ); ?>
								</div>
								<div class="hidden content">
									<i class="cart icon"></i> 
									<?php echo esc_html( $cart_button_msg ); ?>
								</div>
							</div>
						<?php } ?>
						<input type="hidden" id="pdr_original_product_price" value="<?php echo esc_attr( $product_price ); ?>"/>
						<input type="hidden" id="pdr_product_price" value="<?php echo esc_attr( $product_price ); ?>"/>
						<input type="hidden" id="pdr_total_price" value="<?php echo esc_attr( $product_price ); ?>"/>
						<input type="hidden" id="pdr_product_base" value="<?php echo esc_attr( $pdr_id ); ?>"/>
						<input type="hidden" id="pdr_product_id" value="<?php echo esc_attr( $product_id ); ?>"/>
						<input type="hidden" id="pdr_cart_item" value="<?php echo esc_attr( $cart_item ); ?>"/>
						<input type="hidden" id="pdr-file-name" name="pdr-file-name"/>

						<a class="ui animated secondary button" href="<?php echo esc_url( pdr_get_back_to_shop_url() ); ?>"><div class="visible content"><i class="backward icon"></i> <?php echo esc_html( pdr_get_designer_back_to_shop_label() ); ?></div>
							<div class="hidden content">
								<i class="backward icon"></i> 
								<?php echo esc_html( pdr_get_designer_back_to_shop_label() ); ?>
							</div>
						</a>
					</div>

				</div>
			</div>
		</div>
		<div class="ui segment pdr-content-section three column stackable divided grid">
			<div class="row">
				<div class="seven wide column">
					<div class="ui grid">
						<div class="row">
							<div class="five wide column">
								<div class="ui stackable vertical fluid tabular menu">
									<?php
									$pdr_menus = pdr_get_customize_product_menu_items();
									if ( pdr_check_is_array( $pdr_menus ) ) :
										$first_menu = true;
										foreach ( $pdr_menus as $pdr_menu ) :

											$wrapper_classes = array( 'item' );

											switch ( $pdr_menu ) {
												case 'pdr_products':
													$wrapper_classes[] = 'active';
													?>

													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title" ><i class="shopping bag icon"></i> <?php echo esc_html( pdr_get_designer_product_menu_label() ); ?></div>
													</a>
													<?php
													break;
												case 'pdr_cliparts':
													?>

													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title"><i class="images icon"></i> <?php echo esc_html( pdr_get_designer_clipart_menu_label() ); ?></div>
													</a>
													<?php
													break;
												case 'pdr_images':
													?>
													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title"><i class="images icon"></i> <?php echo esc_html( pdr_get_designer_image_menu_label() ); ?></div>
													</a>
													<?php
													break;
												case 'pdr_text':
													?>

													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title"><i class="keyboard icon"></i> <?php echo esc_html( pdr_get_designer_text_menu_label() ); ?></div>
													</a>
													<?php
													break;
												case 'pdr_shapes':
													?>

													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title"><i class="circle outline icon"></i> <?php echo esc_html( pdr_get_designer_shape_menu_label() ); ?></div>
													</a>
													<?php
													break;
												case 'pdr_templates':
													?>

													<a class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-content="<?php echo esc_attr( $pdr_menu ); ?>">
														<div class="title"><i class="object group outline icon"></i> <?php echo esc_html( pdr_get_designer_template_menu_label() ); ?></div>
													</a>
													<?php
													break;

												default:
													/**
													 * Menu Item Tab default action
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_customize_product_menu_item_tab_' . $pdr_menu, $pdr_id, $product_id );
													break;
											}

										endforeach;
									endif;
									?>
								</div>
							</div>

							<div class="eleven wide column">
								<?php
								if ( pdr_check_is_array( $pdr_menus ) ) :

									foreach ( $pdr_menus as $pdr_menu ) :

										switch ( $pdr_menu ) {
											case 'pdr_products':
												?>
												<div class="ui transition pdr_vertical_content pdr_products">
													<?php
													/**
													 * Action to perform on products tab
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_products', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;
											case 'pdr_cliparts':
												?>

												<div class="ui transition pdr_vertical_content pdr_cliparts hidden">
													<?php
													/**
													 * Action to perform on cliparts tab
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_cliparts', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;
											case 'pdr_text':
												?>

												<div class="ui pdr_vertical_content transition pdr_text hidden">
													<?php
													/**
													 * Action to perform on text tab
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_text', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;

											case 'pdr_images':
												?>
												<div class="ui transition pdr_vertical_content pdr_images hidden">
													<?php
													/**
													 * Action to perform on tab images
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_images', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;

											case 'pdr_shapes':
												?>

												<div class="ui pdr_vertical_content transition pdr_shapes hidden">
													<?php
													/**
													 * Action to perform on shapes tab
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_shapes', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;
											case 'pdr_templates':
												?>

												<div class="ui pdr_vertical_content transition pdr_templates hidden">
													<?php
													/**
													 * Action to perform on templates tab
													 *
													 * @since 1.0
													 */
													do_action( 'pdr_frontend_tab_templates', $pdr_id, $product_id );
													?>
												</div>
												<?php
												break;

											default:
												/**
												 * Perform action on default type of menu
												 *
												 * @since 1.0
												 */
												do_action( 'pdr_customize_product_menu_item_content_' . $pdr_menu, $pdr_id, $product_id );
												break;
										}
									endforeach;
								endif;
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="nine wide column pdr-canvas-area">

					<div class="ui grid">
						<div class="sixteen wide column pdr-switch-section">
							<label class="ui label right pointing red"><?php echo esc_html( pdr_get_designer_switch_view_label() ); ?></label>
							<select class="dropdown" id="pdr-switch-view-select" placeholder="switch view">
							</select>
							<input type="hidden" name="pdr-present-view" id="pdr-present-view" value="general"/>
							<div class="pdr-float-right">
								<button class="ui button pdr-qr-generation" data-tooltip="<?php echo esc_html( pdr_get_designer_qr_generate_label() ); ?>"><i class="qrcode icon"></i></button>
								<?php
								if ( is_user_logged_in() ) {
									?>
									<button class="ui primary  button pdr-save-design"><?php echo esc_html( pdr_get_designer_save_design_label() ); ?></button>
									<?php
								}
								?>
							</div>
						</div>

						<div class="sixteen wide column">
							<div class="pdr-text-tools">
																																																		<!--<label class="ui label right pointing red"><?php echo esc_html( pdr_get_designer_text_tools_label() ); ?></label>-->
								<div class="ui icon buttons">
									<button class="ui button pdr-text-edit-tools" data-tooltip="<?php esc_html_e( 'Text Customization Tools', 'product-designer-for-woocommerce' ); ?>"><i class="text edit icon"></i></button>
								</div>
								<div class="ui icon buttons">
									<button class="ui button pdr-text-mask-tools" data-tooltip="<?php esc_html_e( 'Mask the text object with image', 'product-designer-for-woocommerce' ); ?>"><i class="text object group icon"></i></button>
								</div>
								<div class="ui icon buttons">
									<button class="ui button pdr-align-text" data-alignposition="left" data-tooltip="<?php esc_html_e( 'Align Left', 'product-designer-for-woocommerce' ); ?>"><i class="align left icon"></i></button>
									<button class="ui button pdr-align-text" data-alignposition="center" data-tooltip="<?php esc_html_e( 'Align Center', 'product-designer-for-woocommerce' ); ?>"><i class="align center icon"></i></button>
									<button class="ui button pdr-align-text" data-alignposition="right" data-tooltip="<?php esc_html_e( 'Align Right', 'product-designer-for-woocommerce' ); ?>"><i class="align right icon"></i></button>
									<button class="ui button pdr-align-text" data-alignposition="justify" data-tooltip="<?php esc_html_e( 'Align Justify', 'product-designer-for-woocommerce' ); ?>"><i class="align justify icon"></i></button>

									<button class="ui button pdr-position-text" data-alignposition="subscript" data-tooltip="<?php esc_html_e( 'Subscript', 'product-designer-for-woocommerce' ); ?>"><i class="subscript icon"></i></button>
									<button class="ui button pdr-position-text" data-alignposition="superscript" data-tooltip="<?php esc_html_e( 'Superscript', 'product-designer-for-woocommerce' ); ?>"><i class="superscript icon"></i></button>

									<button class="ui button pdr-transform-text" data-alignposition="upper" data-tooltip="<?php esc_html_e( 'Uppercase', 'product-designer-for-woocommerce' ); ?>">A</button>
									<button class="ui button pdr-transform-text" data-alignposition="lower" data-tooltip="<?php esc_html_e( 'Lowercase', 'product-designer-for-woocommerce' ); ?>">a</button>

								</div>
								<div class="ui icon buttons pdr-text-style-wrapper">
									<div class="ui button pdr-text-style pdr-text-style-fc" data-tooltip="<?php esc_html_e( pdr_get_designer_text_font_color_label() ); ?>"><input type='color' name="pdr-color-field" id="pdr-color-field" class="pdr-color-field" value="#fff"/></div>
									<div class="ui button pdr-text-style pdf-text-style-fc" data-tooltip="<?php esc_html_e( pdr_get_designer_text_background_color_label() ); ?>"><input type='color' name="pdr-text-bg-field" id="pdr-text-bg-field" class="pdr-text-bg-field" value="#fff"/></div>
									<button class="ui button pdr-text-style" data-textstyle="bold" data-tooltip="<?php esc_html_e( 'Text Bold', 'product-designer-for-woocommerce' ); ?>"><i class="bold icon"></i></button>
									<button class="ui button pdr-text-style" data-textstyle="underline" data-tooltip="<?php esc_html_e( 'Underline', 'product-designer-for-woocommerce' ); ?>"><i class="underline icon"></i></button>
									<button class="ui button pdr-text-style" data-textstyle="italic" data-tooltip="<?php esc_html_e( 'Italic', 'product-designer-for-woocommerce' ); ?>"><i class="italic icon"></i></button>
									<button class="ui button pdr-text-style" data-textstyle="overline" data-tooltip="<?php esc_html_e( 'Overline', 'product-designer-for-woocommerce' ); ?>"><i class="minus icon"></i></button>
									<button class="ui button pdr-text-style" data-textstyle="linethrough" data-tooltip="<?php esc_html_e( 'Linethrough', 'product-designer-for-woocommerce' ); ?>"><i class="strikethrough icon"></i></button>
								</div>

							</div>
							<div class="pdr-shape-tools">
								<label class="ui label right pointing red"><?php echo esc_html( __( 'Shape Colors', 'product-designer-for-woocommerce' ) ); ?></label>
								<label class="ui label"><?php esc_html_e( 'Fill Color', 'product-designer-for-woocommerce' ); ?></label><input type="color" name="pdr-shape-color-field" id="pdr-shape-color-field" class="pdr-shape-color-field" value="#fff"/>
								<label class="ui label"><?php esc_html_e( 'Stroke Color', 'product-designer-for-woocommerce' ); ?></label><input type="color" name="pdr-shape-stroke-color-field" id="pdr-shape-stroke-color-field" class="pdr-shape-stroke-color-field" value="#fff"/>
							</div>
							<div class="pdr-object-tools">
																																																		<!--<label class="ui label right pointing red"><?php echo esc_html( pdr_get_designer_object_tools_label() ); ?></label>-->
								<div class="ui icon buttons pdr-object-buttons">
									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Send object to Back', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-send-to-back">
										<i class="backward icon"></i>
									</button>
									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Send object to Front', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-send-to-front">
										<i class="forward icon"></i>
									</button>
									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Copy selected object', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-copy-selected">
										<i class="icon clone"></i>
									</button>

									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Delete selected object', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-delete-selected">
										<i class="delete icon"></i>
									</button>

									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Filters', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-filters">
										<i class="magic icon"></i>
									</button>
								</div>
								<div class="ui pdr-object-filters-view flowing popup transition hidden">
									<div class="ui grid">
										<div class="row pdr-object-filters">
											<?php foreach ( pdr_get_image_filters() as $filter => $image ) { ?> 
												<div class="five wide column">
													<img class="ui rounded image pdr-object-filter-<?php echo esc_attr( $filter ); ?>" src="<?php echo esc_url( $image[ 'url' ] ); ?>" data-filter="<?php echo esc_attr( $filter ); ?>"/>
													<label for="<?php echo esc_attr( $filter ); ?>"><?php echo esc_html( $image[ 'label' ] ); ?></label>
												</div>
											<?php } ?>
										</div>
										<?php foreach ( pdr_get_advanced_image_filters() as $filter => $range ) { ?> 
											<div class="row pdr-object-advanced-filters">
												<div class="four wide column"><label for="<?php echo esc_attr( $filter ); ?>"><?php echo esc_html( $range[ 'label' ] ); ?>:</label></div>
												<div class="two wide column"><span class="pdr-object-filter-range-output">0</span></div>
												<div class="eight wide column"><input type="range" class="pdr-object-filter-range pdr-object-<?php echo esc_attr( strtolower( $filter ) ); ?>-range" data-filter="<?php echo esc_attr( $filter ); ?>" data-index="<?php echo esc_attr( $range[ 'index' ] ); ?>" value="0" min="-1" max="1" step="0.001"></div>
												<div class="two wide column"><i class="sync alternate icon pdr-object-filter-range-reset"></i></div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="pdr-paste-object-tools">
								<label class="ui label right pointing red"><?php echo esc_html_e( 'Clipboard', 'product-designer-for-woocommerce' ); ?></label>
								<div class="ui icon buttons pdr-paste-object">
									<button class="ui button" data-position="top center" data-tooltip="<?php esc_html_e( 'Paste copied object', 'product-designer-for-woocommerce' ); ?>" id="pdr-object-paste-selected">
										<i class="icon paste"></i>
									</button>
								</div>
							</div>
							<div class="ui pdr-text-edit-tools-popup flowing popup autoprefixer transition hidden">
								<div class="ui two column divided center aligned grid">
									<div class="column wide">
										<h4 class="ui header"><?php echo esc_html( pdr_get_designer_text_font_size_label() ); ?></h4>
										<div class="ui range"></div>
										<input type="number" name="pdr-number-field" id="pdr-number-field" class="pdr-number-field" value="18"/>px
									</div>
									<div class="column wide">
										<h4 class="ui header"><?php echo esc_html( pdr_get_designer_text_font_family_label() ); ?></h4>
										<?php
										$default_font_family = $default_fonts;
										?>
										<div class="ui pdr-font-families fluid search selection dropdown" style="width:100%;">
											<input type="hidden" name="pdr-font-family-value">
											<i class="dropdown icon"></i>
											<div class="default text"><?php echo esc_html( pdr_get_designer_text_select_font_family_label() ); ?></div>
											<div class="menu">
												<?php
												if ( is_array( $default_font_family ) && ! empty( $default_font_family ) ) {
													foreach ( $default_font_family as $each_family ) {
														$check_is_custom  = is_array(pdr_get_custom_fonts()) && !empty(pdr_get_custom_fonts()) && in_array($each_family, array_keys(pdr_get_custom_fonts()))?'custom':'default';
														?>
														<div class="item" data-remote-font="<?php echo esc_attr($check_is_custom); ?>" data-value="<?php echo esc_attr( $each_family ); ?>"><?php echo esc_html( $each_family ); ?></div>
														<?php
													}
												}
												/**
												 * Load Google Fonts 
												 *
												 * @since 1.0
												 */
												do_action( 'pdr_load_google_fonts' );

												if ( $google_fonts ) {
													?>
													<div class="header googlefonts transition">
														<i class="google font icon"></i>
														<?php echo esc_html( pdr_get_designer_text_google_fonts_label() ); ?>
													</div>
													<?php
													if ( is_array( $google_fonts ) && ! empty( $google_fonts ) ) {
														foreach ( $google_fonts as $gkey => $gobj ) {
															?>
															<div class="item" data-remote-font="google" data-value="<?php echo esc_attr( $gobj ); ?>"><?php echo esc_html( $gobj ); ?></div>
															<?php
														}
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="ui pdr-text-mask-tools-popup flowing popup transition hidden">
								<div class="ui pointing secondary menu">
									<a class="item active" data-tab="text-mask"><?php esc_html_e( 'Text Mask', 'product-designer-for-woocommerce' ); ?></a>
								</div>
								<div class="ui tab segment active" data-tab="text-mask">
									<div class="ui form">
										<div class="inline fields">
											<div class="sixteen wide field">
												<label><?php echo esc_html( pdr_get_designer_text_mask_label() ); ?></label>
												<input type="file" id="pdr-text-mask-image-file">
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="ui pdr-qr-generation-tools-popup flowing popup transition hidden">
								<div class="ui pointing secondary menu">
									<a class="item active" data-tab="qr-code"><?php echo esc_html( pdr_get_designer_qr_generate_label() ); ?></a>
								</div>
								<div class="ui tab segment active" data-tab="qr-code">
									<div class="ui form">
										<div class="inline fields">
											<div class="sixteen wide field">
												<label><?php echo esc_html( pdr_get_designer_qr_input_text() ); ?></label>
												<input type="text" id="pdr-qr-code-text" name="pdr-qr-code-text" value=""/>
											</div>
										</div>

									</div>
									<button class="ui pdr-apply-qr-action primary button">
										<?php echo esc_html( pdr_get_designer_qr_button_label() ); ?>
									</button>

								</div>
							</div>

						</div>
					</div>
					<button class="ui icon button pdr-button-undo" disabled="disabled"><i class="undo icon"></i></button><button class="ui icon button pdr-button-redo" disabled="disabled"><i class="redo icon"></i></button>
					<div class="ui divider"></div>
					<?php if ( is_pdr() ) { ?>
						<div id="pdr-drawing-area" class="pdr-drawing-area">
							<canvas id="c"></canvas>
						</div>
					<?php } else { ?>
						<div class="ui one column stackable center aligned page grid">
							<div class="column twelve wide middle aligned content">
								<?php
								echo esc_html( pdr_get_designer_no_product_base_message() );
								?>
								<button class="ui button pdr-popup-product-base-btn primary"><?php echo esc_html( pdr_get_designer_choose_product_label() ); ?></button>
							</div>
						</div>
					<?php } ?>
					<input type="hidden" id="pdr-zoom-information" value=""/>
					<div class="ui divider"></div>
				</div>
			</div>
		</div>

		<div class="ui pdr-pick-base longer modal">
			<i class="close icon"></i>
			<div class="header">
				<?php esc_html_e( 'Choose Product Base', 'product-designer-for-woocommerce' ); ?>
			</div>

			<div class="scrolling content">
				<div class="ui grid divided">
					<div class="four wide column">

						<div class="ui pdr-popup-product-bases search selection dropdown">
							<i class="dropdown icon"></i>
							<div class="default text"><?php esc_html_e( 'All Categories', 'product-designer-for-woocommerce' ); ?></div>
							<div class="menu">
								<div class="item" data-value="0"> <?php esc_html_e( 'All Categories', 'product-designer-for-woocommerce' ); ?></div>
								<?php
								$custom_taxonomy = 'pdr_product_base_cat';
								$tax_terms       = get_terms( $custom_taxonomy, array( 'hide_empty' => false ) );

								if ( ! empty( $tax_terms ) && is_array( $tax_terms ) ) {
									foreach ( $tax_terms as $tax_term ) {
										?>
										<div class="item" data-value="<?php echo esc_attr( $tax_term->term_id ); ?>"> <?php echo esc_html( $tax_term->name . ' (' . $tax_term->count . ')' ); ?></div>
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
					<div class="twelve wide column">
						<div class="ui four cards pdr-popup-product-details">

						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="ui pdr-my-designs longer modal">
			<i class="close icon"></i>
			<div class="header">
				<?php esc_html_e( 'Your Saved Designs', 'product-designer-for-woocommerce' ); ?>
			</div>
			<div class="scrolling content">
				<div class="ui grid">
					<div class="ui four cards pdr-popup-my-designs">

					</div>
				</div>
			</div>
		</div>


		<div class="ui footer segment">
			<div class="ui center aligned container">
				<?php echo do_shortcode( pdr_get_footer_content() ); ?>
			</div>

		</div>
		<?php
		//load google fonts in dom otherwise fonts appear only on second refresh
		$array_fonts = pdr_get_google_fonts_in_canvas();
		if ( $array_fonts && is_array( $array_fonts ) ) {
			foreach ( $array_fonts as $each_font ) {
				$trim_data = str_replace( ' ', '', strtolower( $each_font ) );
				?>
				<div class="pdr_<?php echo esc_attr( $trim_data ); ?>">.</div>
				<?php
			}
		}
		?>

		<?php
		/**
		 * Enqueue Scripts to the end of html element
		 *
		 * @since 1.0
		 */
		do_action( 'pdr_enqueue_scripts' );
		?>
	</body>
</html>
