<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include('pdr-post-functions.php');

if ( ! function_exists( 'pdr_check_is_array' ) ) {

	/**
	 * Check if resource is array.
	 *
	 * @return bool
	 */
	function pdr_check_is_array( $data ) {
		return ( is_array( $data ) && ! empty( $data ) );
	}

}

if ( ! function_exists( 'pdr_get_wp_user_roles' ) ) {

	/**
	 * Get the WordPress User Roles.
	 *
	 * @return array
	 */
	function pdr_get_wp_user_roles() {
		global $wp_roles;
		$user_roles = array();

		if ( ! isset( $wp_roles->roles ) || ! pdr_check_is_array( $wp_roles->roles ) ) {
			return $user_roles;
		}

		foreach ( $wp_roles->roles as $slug => $role ) {
			$user_roles[ $slug ] = $role[ 'name' ];
		}

		return $user_roles;
	}

}

if ( ! function_exists( 'pdr_enqueue_script' ) ) {

	function pdr_enqueue_script( $name, $src, $deps = array(), $version = 1.0, $echo = true ) {

		$script = '<script ';
		$script .= "src='" . esc_url( add_query_arg( array( 'v' => $version ), $src ) ) . "'";
		$script .= "></script>\n";

		if ( ! $echo ) {
			return $script;
		}

		echo do_shortcode( $script );
	}

}

if ( ! function_exists( 'pdr_enqueue_style' ) ) {

	function pdr_enqueue_style( $name, $src, $deps = array(), $version = 1.0, $echo = true ) {

		$style = '<link ';
		$style .= "rel='stylesheet' ";
		$style .= "id='" . esc_attr( $name ) . "-css' ";
		$style .= "href='" . esc_url( add_query_arg( array( 'v' => $version ), $src ) ) . "' ";
		$style .= "media='all'";
		$style .= "/>\n";

		if ( ! $echo ) {
			return $style;
		}

		echo do_shortcode( $style );
	}

}

if ( ! function_exists( 'pdr_localize_script' ) ) {

	/**
	 * Localize the script.
	 * 
	 * @return mixed
	 */
	function pdr_localize_script( $name, $data, $echo = true ) {
		ob_start();
		?>
		<script>
			var <?php echo esc_html( $name ); ?> =<?php echo do_shortcode( json_encode( $data ) ); ?>;
		</script>
		<?php
		$script = ob_get_clean();

		if ( ! $echo ) {
			return $script;
		}

		echo do_shortcode( $script );
	}

}

if ( ! function_exists( 'pdr_add_inline_style' ) ) {

	/**
	 * Add inline style.
	 * 
	 * @return mixed
	 */
	function pdr_add_inline_style( $name, $style, $echo = true ) {
		ob_start();
		?>
		<style type="text/css" id="<?php echo esc_attr( $name ); ?>">
		<?php echo wp_kses_post( $style ); ?>
		</style>
		<?php
		$style = ob_get_clean();

		if ( ! $echo ) {
			return $style;
		}

		echo do_shortcode( $style );
	}

}


if ( ! function_exists( 'pdr_get_google_fonts_in_canvas' ) ) {

	function pdr_get_google_fonts_in_canvas() {
		$array_fonts    = false;
		$cart_id        = isset( $_REQUEST[ 'cart_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_id' ] ) ) : '';
		$cart_item      = isset( $_REQUEST[ 'cart_item' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'cart_item' ] ) ) : '';
		$order_key      = isset( $_REQUEST[ 'order_key' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'order_key' ] ) ) : '';
		$order_id       = isset( $_REQUEST[ 'order_id' ] ) ? absint( $_REQUEST[ 'order_id' ] ) : '';
		$file_api       = new PDR_File( '', $cart_id );
		$object_content = $file_api->retrieve();
		$cart_item      = '' != $cart_item ? WC()->cart->get_cart_item( $cart_item ) : false;
		$my_design      = isset( $_REQUEST[ 'my_design' ] ) ? absint( $_REQUEST[ 'my_design' ] ) : '';
		$form_data      = '';
		//if my designs found load data
		if ( $my_design ) {
			$get_post_content = new PDR_My_Designs( get_current_user_id() );
			$get_post_content = $get_post_content->fetch_design_content( $my_design );
			if ( $get_post_content ) {
				$object_content = wp_slash( $get_post_content );
			}
		}

		if ( isset( $_REQUEST[ 'order_key' ] ) && isset( $_REQUEST[ 'order_id' ] ) ) {
			if ( pdr_fetch_order_masterdata( $order_key, $order_id ) ) {
				$object_content = pdr_fetch_order_masterdata( $order_key, $order_id );
			}
		}
		$object_content      = wp_unslash( $object_content );
		$object_content      = json_decode( $object_content, true, JSON_UNESCAPED_SLASHES );
		$object_content      = '' != $object_content ? $object_content : '';
		$inline_google_fonts = '';
		if ( isset( $object_content[ 'pdr_google_fonts' ] ) && $object_content[ 'pdr_google_fonts' ] ) {
			$array_fonts = json_decode( $object_content[ 'pdr_google_fonts' ] );
		}

		return $array_fonts;
	}

}

if ( ! function_exists( 'pdr_get_product_base_default_rule' ) ) {

	/**
	 * Get the product base default rule.
	 * 
	 * @return array
	 */
	function pdr_get_product_base_default_rule() {

		/**
		 * Initialize Default Rule with specified args
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_base_default_rule', array(
			'title'   => 'Untitled',
			'img_id'  => '',
			'img_url' => '',
			'factor'  => '',
			'x'       => 0,
			'y'       => 0,
			'w'       => 10,
			'h'       => 10,
			'imgx'    => '',
			'imgy'    => '',
			'imgw'    => '',
			'imgh'    => ''
				)
		);
	}

}

if ( ! function_exists( 'pdr_get_product_base_default_attribute' ) ) {

	/**
	 * Get the product base default attribute.
	 * 
	 * @return array
	 */
	function pdr_get_product_base_default_attribute() {
		/**
		 * Alter the default attribute args for product base
		 *
		 * @since 1.0
		 */
		return apply_filters( 'pdr_product_base_default_attribute', array(
			'title'         => 'Untitled',
			'type'          => '',
			'options'       => '',
			'label'         => '',
			'product_color' => array(),
			'price'         => ''
				)
		);
	}

}

if ( ! function_exists( 'is_pdr' ) ) {

	function is_pdr() {
		return ( ( isset( $_REQUEST[ 'pdr_id' ] ) && isset( $_REQUEST[ 'product_id' ] ) ) || ( ( isset( $_REQUEST[ 'order_key' ] ) ) && isset( $_REQUEST[ 'order_id' ] ) ) ) ? true : false;
	}

}

if ( ! function_exists( 'pdr_select2_html' ) ) {

	/**
	 * Return or display Select2 HTML.
	 *
	 * @return mixed
	 */
	function pdr_select2_html( $args, $echo = true ) {
		$args = wp_parse_args(
				$args, array(
			'class'             => '',
			'id'                => '',
			'name'              => '',
			'list_type'         => '',
			'action'            => '',
			'placeholder'       => '',
			'custom_attributes' => array(),
			'multiple'          => true,
			'allow_clear'       => true,
			'selected'          => true,
			'options'           => array(),
				)
		);

		$multiple = $args[ 'multiple' ] ? 'multiple="multiple"' : '';
		$name     = esc_attr( '' !== $args[ 'name' ] ? $args[ 'name' ] : $args[ 'id' ] ) . '[]';
		$options  = array_filter( pdr_check_is_array( $args[ 'options' ] ) ? $args[ 'options' ] : array() );

		$allowed_html = array(
			'select' => array(
				'id'               => array(),
				'class'            => array(),
				'data-placeholder' => array(),
				'data-allow_clear' => array(),
				'data-nonce'       => array(),
				'data-action'      => array(),
				'multiple'         => array(),
				'name'             => array(),
			),
			'option' => array(
				'value'    => array(),
				'selected' => array()
			)
		);

		// Custom attribute handling.
		$custom_attributes = pdr_format_custom_attributes( $args );
		$data_nonce        = ( 'products' == $args[ 'list_type' ] ) ? 'data-nonce="' . wp_create_nonce( 'search-products' ) . '"' : '';

		ob_start();
		?>
		<select <?php echo esc_attr( $multiple ); ?> 
			name="<?php echo esc_attr( $name ); ?>" 
			id="<?php echo esc_attr( $args[ 'id' ] ); ?>" 
			data-action="<?php echo esc_attr( $args[ 'action' ] ); ?>" 
			class="pdr_select2_search <?php echo esc_attr( $args[ 'class' ] ); ?>" 
			data-placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ); ?>" 
			<?php echo wp_kses( implode( ' ', $custom_attributes ), $allowed_html ); ?>
			<?php echo wp_kses( $data_nonce, $allowed_html ); ?>
			<?php echo $args[ 'allow_clear' ] ? 'data-allow_clear="true"' : ''; ?> >
				<?php
				if ( is_array( $args[ 'options' ] ) ) {
					foreach ( $args[ 'options' ] as $option_id ) {
						$option_value = '';
						switch ( $args[ 'list_type' ] ) {
							case 'post':
								$post_name = get_the_title( $option_id );
								if ( $post_name ) {
									$option_value = $post_name . ' (#' . absint( $option_id ) . ')';
								}
								break;
							case 'products':
								$product = wc_get_product( $option_id );
								if ( $product ) {
									$option_value = $product->get_name() . ' (#' . absint( $option_id ) . ')';
								}
								break;
							case 'customers':
								$user = get_user_by( 'id', $option_id );
								if ( $user ) {
									$option_value = $user->display_name . '(#' . absint( $user->ID ) . ' &ndash; ' . $user->user_email . ')';
								}
								break;
						}

						if ( $option_value ) {
							?>
						<option value="<?php echo esc_attr( $option_id ); ?>" <?php echo $args[ 'selected' ] ? 'selected="selected"' : ''; // WPCS: XSS ok. ?>><?php echo esc_html( $option_value ); ?></option>
							<?php
						}
					}
				}
				?>
		</select>
		<?php
		$html = ob_get_clean();

		if ( $echo ) {
			echo wp_kses( $html, $allowed_html );
		}

		return $html;
	}

}

if ( ! function_exists( 'pdr_format_custom_attributes' ) ) {

	/**
	 * Format the custom attributes.
	 *
	 * @return array
	 */
	function pdr_format_custom_attributes( $value ) {
		$custom_attributes = array();

		if ( ! empty( $value[ 'custom_attributes' ] ) && is_array( $value[ 'custom_attributes' ] ) ) {
			foreach ( $value[ 'custom_attributes' ] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '=' . esc_attr( $attribute_value ) . '';
			}
		}

		return $custom_attributes;
	}

}


if ( ! function_exists( 'pdr_filter_readable_products' ) ) {

	/**
	 * Filter the readable products.
	 *
	 * @return array
	 */
	function pdr_filter_readable_products( $product_ids ) {

		if ( ! pdr_check_is_array( $product_ids ) ) {
			return array();
		}

		if ( function_exists( 'wc_products_array_filter_readable' ) ) {
			return array_filter( array_map( 'wc_get_product', $product_ids ), 'wc_products_array_filter_readable' );
		} else {
			return array_filter( array_map( 'wc_get_product', $product_ids ), 'pdr_products_array_filter_readable' );
		}
	}

}

if ( ! function_exists( 'pdr_products_array_filter_readable' ) ) {

	/**
	 * Check the product is readable?.
	 *
	 * @return bool
	 */
	function pdr_products_array_filter_readable( $product ) {
		return $product && is_a( $product, 'WC_Product' ) && current_user_can( 'read_product', $product->get_id() );
	}

}

if ( ! function_exists( 'pdr_page_screen_ids' ) ) {

	/**
	 * Get page screen IDs.
	 *
	 * @return array
	 */
	function pdr_page_screen_ids() {
		/**
		 * Add screen ids to the existing list
		 *
		 * @since 1.0
		 */
		return apply_filters(
				'pdr_page_screen_ids', array(
			'toplevel_page_product-designer-for-woocommerce',
			'pdr_product_base',
			'pdr_design_templates',
			'pdr_cliparts',
			'pdr_shapes',
			'pdr_calculation',
			'pdr_orders',
			'product-designer_page_pdr_settings'
				)
		);
	}

}

if ( ! function_exists( 'pdr_get_settings_page_url' ) ) {

	/**
	 * Get the settings page URL.
	 *
	 * @return URL
	 */
	function pdr_get_settings_page_url( $args = array() ) {

		$url = add_query_arg( array( 'page' => 'pdr_settings' ), admin_url( 'admin.php' ) );

		if ( pdr_check_is_array( $args ) ) {
			$url = add_query_arg( $args, $url );
		}

		return $url;
	}

}

if ( ! function_exists( 'pdr_price' ) ) {

	/**
	 * Get the formatted price.
	 *
	 * @return URL
	 */
	function pdr_price( $price, $args = array(), $echo = false ) {

		if ( $echo ) {
			echo wp_kses_post( wc_price( $price, $args ) );
		}

		return wc_price( $price, $args );
	}

}

if ( ! function_exists( 'pdr_get_page_ids' ) ) {

	/**
	 * Get the page ids.
	 * 
	 * @return array
	 * */
	function pdr_get_page_ids() {
		$page_ids = array();
		$pages    = get_pages();

		if ( ! pdr_check_is_array( $pages ) ) {
			return $page_ids;
		}

		foreach ( $pages as $page ) {

			if ( ! is_object( $page ) ) {
				continue;
			}

			$page_ids[ $page->ID ] = $page->post_title;
		}

		return $page_ids;
	}

}

if ( ! function_exists( 'pdr_attributes_render_html' ) ) {

	function pdr_attributes_render_html( $type, $value = array(), $data = array(), $uniqkey = '' ) {
		ob_start();
		$title                = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';
		$price                = isset( $data[ 'price' ] ) ? $data[ 'price' ] : 0;
		$placeholder_label    = isset( $data[ 'label' ] ) ? $data[ 'label' ] : '';
		$hide_price_when_zero = get_option( 'pdr_hide_price_label_zero' );

		switch ( $type ) {

			case 'product_color':
				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?></label>
						<?php
				}
					//get the saved data
				if ( isset( $data[ 'product_color' ] ) && ! empty( $data[ 'product_color' ] ) ) {
					$product_color = $data[ 'product_color' ];

					foreach ( $product_color as $each_color => $each_color_title ) {
						$product_color_price = isset( $data[ 'product_color_price' ][ $each_color ] ) && ! empty( $data[ 'product_color_price' ][ $each_color ] ) ? $data[ 'product_color_price' ][ $each_color ] : ( 0 < $price ? $price : 0 );
						$price_html          = wp_kses_post( strip_tags( pdr_price( $product_color_price ) ) );
						$tooltip             = 'data-tooltip="' . $price_html . '"';
						$hide_price_is_zero  = 0 == $product_color_price && 1 == $hide_price_when_zero ? false : true;
						$tooltip             = $hide_price_is_zero ? $tooltip : '';
						?>
							<span <?php echo wp_kses_post( $tooltip ); ?>>
								<a class="ui massive empty circular label color-preview" data-price="<?php echo esc_attr( $product_color_price ); ?>" data-color="<?php echo esc_attr( $each_color ); ?>" title="<?php echo esc_attr( $each_color_title ); ?>" style="background-color:<?php echo esc_attr( $each_color ); ?>; border-color:<?php echo esc_attr( $each_color ); ?>;">
								</a>
								<div class="ui hidden vertical divider">
								</div>
							</span>
							<?php
					}
				}
				?>
					<a class="pdr-clear-bg" id="pdr-clear-bg" href="#"><?php esc_attr_e( 'Clear selection', 'product-designer-for-woocommerce' ); ?></a>
					<input type="hidden" name="pdr-color-preview-selection[<?php echo esc_attr( $uniqkey ); ?>]" data-price="<?php echo esc_attr( $price ); ?>" id="pdr-color-preview-selection" value=""/>
				</div>
				<?php
				break;

			case 'select':
				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?> </label>
						<?php
				}
				?>
					<select data-price="<?php echo esc_attr( $price ); ?>" name="pdr-select-dropdown[<?php echo esc_attr( $uniqkey ); ?>]" class="ui search pdr-attributes-select selection dropdown">
						<option value=""><?php echo esc_html( __( 'Select ', 'product-designer-for-woocommerce' ) . $title ); ?></option>
						<?php
						if ( '' != $value ) {
							$explode = explode( ',', $value );
							foreach ( $explode as $each_data ) {
								$split_data         = explode( '|', $each_data );
								$price              = isset( $split_data[ 2 ] ) && '' != $split_data[ 2 ] ? $split_data[ 2 ] : 0;
								$hide_price_is_zero = 0 == $price && 1 == $hide_price_when_zero ? false : true;
								?>
								<option data-price="<?php echo esc_attr( $price ); ?>" value="<?php echo esc_attr( $split_data[ 0 ] ); ?>"><?php echo esc_html( $split_data[ 1 ] ) . ( ( bool ) ( $hide_price_is_zero ) ? ' - ' . wp_kses_post( pdr_price( $price ) ) : '' ); ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
				<?php
				break;
			case 'checkbox':
				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?>
						</label>
						<?php
				}
				if ( '' != $value ) {
					$explode = explode( ',', $value );
					foreach ( $explode as $each_data ) {
						$split_data         = explode( '|', $each_data );
						$price              = isset( $split_data[ 2 ] ) && '' != $split_data[ 2 ] ? $split_data[ 2 ] : 0;
						$hide_price_is_zero = 0 == $price && 1 == $hide_price_when_zero ? false : true;
						?>
							<div class="field">
								<div class="ui pdr-checkbox-field toggle checkbox">
									<input id="pdr-form-checkbox[<?php echo esc_attr( $uniqkey ); ?>][<?php echo esc_attr( $split_data[ 0 ] ); ?>]" value="<?php echo esc_attr( $split_data[ 0 ] ); ?>" data-price="<?php echo esc_attr( $price ); ?>" name="pdr-form-checkbox[<?php echo esc_attr( $uniqkey ); ?>][<?php echo esc_attr( $split_data[ 0 ] ); ?>]" type="checkbox">
									<label><?php echo esc_attr( $split_data[ 1 ] ); ?> 
									<?php if ( $hide_price_is_zero ) { ?>
											<a class="ui green tag label">
												<?php echo wp_kses_post( pdr_price( $price ) ); ?>
											</a>
										<?php } ?>
									</label>
								</div>
							</div>
							<?php
					}
				}
				?>
				</div>
				<?php
				break;
			case 'radio':
				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?>
						</label>
						<?php
				}
				if ( '' !== $value ) {
					$explode = explode( ',', $value );
					$k       = 0;
					foreach ( $explode as $each_data ) {
						$split_data = explode( '|', $each_data );
						$price      = isset( $split_data[ 2 ] ) && '' != $split_data[ 2 ] ? $split_data[ 2 ] : 0;
						if ( isset( $split_data[ 0 ] ) && isset( $split_data[ 1 ] ) ) {
							$hide_price_is_zero = 0 == $price && 1 == $hide_price_when_zero ? false : true;
							?>
								<div class="field">
									<div class="ui pdr-radio-field radio checkbox">
										<input id="pdr-form-radio[<?php echo esc_attr( $uniqkey ); ?>]"  data-uniqid="<?php echo esc_attr( $uniqkey ); ?>" data-price="<?php echo esc_attr( $price ); ?>" value="<?php echo esc_attr( $split_data[ 0 ] ); ?>"  name="pdr-form-radio[<?php echo esc_attr( $uniqkey ); ?>]" type="radio">
										<label><?php echo esc_html( $split_data[ 1 ] ); ?>
										<?php if ( $hide_price_is_zero ) { ?>
												<a class="ui green tag label">
													<?php echo wp_kses_post( pdr_price( $price ) ); ?>
												</a>
											<?php } ?>
										</label>
									</div>
								</div>
								<?php
								$k ++;
						}
					}
				}
				?>
					<input type="hidden" name="pdr-form-radio-data[<?php echo esc_attr( $uniqkey ); ?>]" data-price="<?php echo esc_attr( $price ); ?>"/>
				</div>
				<?php
				break;
			case 'text':
				$hide_price_is_zero = 0 == $price && 1 == $hide_price_when_zero ? false : true;

				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?> 
							<?php if ( $hide_price_is_zero ) { ?>
								<a class="ui green tag label">
									<?php echo wp_kses_post( pdr_price( $price ) ); ?>
								</a>
							<?php } ?>
						</label>
						<?php
				}
				?>
					<div class="ui input">
						<input class="pdr-input-fields" data-price="<?php echo esc_attr( $price ); ?>" name="pdr-form-text[<?php echo esc_attr( $uniqkey ); ?>]" type="text" placeholder="<?php echo esc_attr( $placeholder_label ); ?>"/>
					</div>
				</div>
				<?php
				break;
			case 'textarea':
				$hide_price_is_zero = 0 == $price && 1 == $hide_price_when_zero ? false : true;

				if ( '' != $title ) {
					?>
					<div class="field">
						<label><?php echo esc_html( $title ); ?> 
							<?php
							if ( $hide_price_is_zero ) {
								?>

								<a class="ui green tag label">
									<?php echo wp_kses_post( pdr_price( $price ) ); ?>
								</a>
							<?php } ?>
						</label>
						<?php
				}
				?>
					<textarea class="pdr-input-fields" data-price="<?php echo esc_attr( $price ); ?>" name="pdr-form-textarea[<?php echo esc_attr( $uniqkey ); ?>]" placeholder="<?php echo esc_attr( $placeholder_label ); ?>"></textarea>
				</div>
				<?php
				break;
		}

		return ob_get_clean();
	}

}

if ( ! function_exists( 'pdr_fetch_order_masterdata' ) ) {

	function pdr_fetch_order_masterdata( $order_key, $pdr_order_id ) {
		$requested_order_id = wc_get_order_id_by_order_key( $order_key );
		$saved_order_id     = get_post_meta( $pdr_order_id, 'pdr_wc_order_id', true ); //get the pdr wc order id

		if ( $requested_order_id == $saved_order_id ) {
			$get_file_name = get_post_meta( $pdr_order_id, 'pdr_canvas_data', true );
			$file_api      = new PDR_File( '', $get_file_name );
			$post_content  = $file_api->retrieve();
			return $post_content;
		} else {
			wp_die( "Sorry you're not allowed to access the details" );
		}
		return false;
	}

}

if ( ! function_exists( 'pdr_fetch_order_details' ) ) {

	function pdr_fetch_order_details( $order_key, $pdr_order_id ) {
		$is_order   = true;
		//_pdr_order_id
		$order_id   = wc_get_order_id_by_order_key( $order_key );
		//get the pdr order id
		$get_pdr_id = get_post_meta( $pdr_order_id, 'pdr_wc_order_id', true );
		if ( $order_id == $get_pdr_id ) {
			$order    = wc_get_order( $order_id );
			$is_order = true;
		}
		return $is_order;
	}

}

if ( ! function_exists( 'pdr_attributes_in_readable_format' ) ) {

	function pdr_attributes_in_readable_format( $cart_data, $product_base_id, $attribute_data ) {
		$get_rules   = get_post_meta( $product_base_id, 'pdr_attributes', true );
		$options_map = array( 'product_color' => 'product_color', 'select' => 'options', 'checkbox' => 'options', 'radio' => 'options' );
		if ( $attribute_data ) {
			$attribute_data = wp_unslash( $attribute_data );
			$form_data      = json_decode( $attribute_data, true, JSON_UNESCAPED_SLASHES );
			if ( is_array( $form_data ) && ! empty( $form_data ) ) {
				$form_attribute_keys = array_keys( $form_data );
				foreach ( $form_attribute_keys as $each_attribute_key ) {
					$appropriate_data = $form_data[ $each_attribute_key ];
					preg_match_all( '/\[([^\]]*)\]/', $each_attribute_key, $matches );
					$get_key          = is_array( $matches[ 1 ] ) && ! empty( $matches[ 1 ] ) ? $matches[ 1 ][ 0 ] : false;
					//find details associated with main data

					if ( $get_key && $appropriate_data ) {

						if ( $get_rules[ $get_key ] ) {
							if ( isset( $options_map[ $get_rules[ $get_key ][ 'type' ] ] ) ) {
								$options = $get_rules[ $get_key ] [ $options_map[ $get_rules[ $get_key ][ 'type' ] ] ];
								if ( 'product_color' == $get_rules[ $get_key ][ 'type' ] ) {
									$appropriate_data = isset( $options[ $appropriate_data ] ) ? $options[ $appropriate_data ] : $appropriate_data;
								} else {
									$explode = explode( ',', $options );
									foreach ( $explode as $each_explode ) {
										$explode_vertical = explode( '|', $each_explode );
										if ( in_array( $appropriate_data, array( $explode_vertical[ 0 ] ) ) ) {
											$appropriate_data = $explode_vertical[ 1 ];
											break;
										}
									}
								}
							}
							$cart_data[] = array(
								'key'   => $get_rules[ $get_key ][ 'title' ],
								'value' => $appropriate_data,
							);
						}
					}
				}
			}
		}
		return $cart_data;
	}

}

if ( ! function_exists( 'pdr_attributes_and_types' ) ) {

	function pdr_attributes_and_types( $product_base_id ) {
		$map_keys   = array( 'product_color' => array( 'name' => 'pdr-color-preview-selection', 'validate_by' => 'empty', 'error' => __( '{{attribute_title}} has to be select before proceed add to cart', 'product-designer-for-woocommerce' ) ),
			'select'        => array( 'name' => 'pdr-select-dropdown', 'validate_by' => 'empty', 'error' => __( '{{attribute_title}} has to be selected', 'product-designer-for-woocommerce' ) ),
			//'checkbox'      => array( 'name' => 'pdr-form-checkbox' , 'validate_by' => 'checked' , 'error' => __( '{{attribute_title}} has to be checked' , 'product-designer-for-woocommerce' ) ) ,
			'radio'         => array( 'name' => 'pdr-form-radio', 'validate_by' => 'checked', 'error' => __( '{{attribute_title}} has to be checked', 'product-designer-for-woocommerce' ) ),
			'text'          => array( 'name' => 'pdr-form-text', 'validate_by' => 'empty', 'error' => __( '{{attribute_title}} has to be entered', 'product-designer-for-woocommerce' ) ),
			'textarea'      => array( 'name' => 'pdr-form-textarea', 'validate_by' => 'empty', 'error' => __( '{{attribute_title}} has be entered', 'product-designer-for-woocommerce' ) ) );
		$fetch_list = array();
		$get_rules  = get_post_meta( $product_base_id, 'pdr_attributes', true );
		if ( is_array( $get_rules ) && ! empty( $get_rules ) ) {
			foreach ( $get_rules as $key => $each_data ) {
				if ( isset( $each_data[ 'type' ] ) && isset( $map_keys[ $each_data[ 'type' ] ] ) ) {
					$error                                                                = $map_keys[ $each_data[ 'type' ] ][ 'error' ];
					$error                                                                = str_replace( array( '{{attribute_title}}' ), array( $each_data[ 'title' ] ), $error );
					// $fetch_list[ $map_keys[ $each_data[ 'type' ] ][ 'name' ] . "[$key]" ] = $map_keys[ $each_data[ 'type' ] ][ 'validate_by' ] ;
					$fetch_list[ $map_keys[ $each_data[ 'type' ] ][ 'name' ] . "[$key]" ] = array( 'identifier' => $map_keys[ $each_data[ 'type' ] ][ 'name' ] . "[$key]", 'rules' => array( array( 'type' => $map_keys[ $each_data[ 'type' ] ][ 'validate_by' ], 'prompt' => $error ) ) );
				}
			}
		}
		return json_encode( $fetch_list );
	}

}

if ( ! function_exists( 'pdr_shortcodes' ) ) {

	function pdr_shortcodes( $message ) {
		$list_of_shortcodes = array( '{{login_url}}' );
		$replace_shortcodes = array( wc_get_page_permalink( 'myaccount' ) );
		return str_replace( $list_of_shortcodes, $replace_shortcodes, $message );
	}

}


if ( ! function_exists( 'pdr_get_attachment_id_by_parent_id' ) ) {

	function pdr_get_attachment_id_by_parent_id( $parent_id ) {
		$posts = get_posts( array(
			'post_type'   => 'attachment',
			'post_status' => 'all',
			'fields'      => 'ids',
			'numberposts' => 1,
			'post_parent' => absint( $parent_id ),
				) );
		return current( $posts );
	}

}

if ( ! function_exists( 'pdr_get_custom_fonts' ) ) {

	function pdr_get_custom_fonts() {
		$get_custom_fonts = get_option( 'pdr_customfont_path' );
		$font_families    = array();
		if ( $get_custom_fonts ) {
			$explode_fonts        = explode( ',', $get_custom_fonts );
			$allowable_font_types = array( 'ttf', 'otf', 'woff', 'woff2' );

			if ( is_array( $explode_fonts ) && ! empty( $explode_fonts ) ) {
				foreach ( $explode_fonts as $each_font ) {
					$extension = pathinfo( $each_font, PATHINFO_EXTENSION );
					if ( in_array( $extension, $allowable_font_types ) ) {
						$fontname                   = pathinfo( $each_font, PATHINFO_FILENAME );
						$font_families[ $fontname ] = $each_font;
					}
				}
			}
		}
		return $font_families;
	}

}
