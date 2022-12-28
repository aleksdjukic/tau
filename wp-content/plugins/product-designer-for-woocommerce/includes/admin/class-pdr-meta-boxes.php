<?php

/**
 * Custom Post Meta Boxes.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Meta_Boxes' ) ) :

	/**
	 * PDR_Meta_Boxes Class.
	 * */
	class PDR_Meta_Boxes {

		/**
		 * Product Base already saved.
		 * 
		 * @var bool
		 */
		public static $product_base_saved = false;

		/**
		 *  Clipart already saved.
		 * 
		 * @var bool
		 */
		public static $cliparts_saved = false;

		/**
		 * Shape already saved.
		 * 
		 * @var bool
		 */
		public static $shape_saved = false;

		/**
		 *  Design Template already saved.
		 * 
		 * @var bool
		 */
		public static $design_template_saved = false;

		/**
		 *  Meta box errors.
		 * 
		 * @var array
		 */
		public static $meta_box_errors = array();

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 30 );
			add_action( 'save_post', array( __CLASS__, 'save_product_base_post' ), 10, 2 );
			add_action( 'save_post', array( __CLASS__, 'save_shape_post' ), 10, 2 );
			add_action( 'save_post', array( __CLASS__, 'save_clipart_post' ), 10, 2 );
			add_action( 'save_post', array( __CLASS__, 'save_design_template_post' ), 10, 2 );
			add_action( 'admin_notices', array( __CLASS__, 'output_errors' ) );
			add_action( 'shutdown', array( __CLASS__, 'save_errors' ) );
		}

		/**
		 * Add the error.
		 *
		 * @return void
		 */
		public static function add_error( $text ) {
			self::$meta_box_errors[] = $text;
		}

		/**
		 * Save the errors.
		 *
		 * @return void
		 */
		public static function save_errors() {
			update_option( 'pdr_meta_box_errors', self::$meta_box_errors );
		}

		/**
		 * Output the errors.
		 *
		 * @return void
		 */
		public static function output_errors() {
			$errors = array_filter( ( array ) get_option( 'pdr_meta_box_errors' ) );

			if ( ! pdr_check_is_array( $errors ) ) {
				return;
			}

			echo '<div class="error notice is-dismissible">';

			foreach ( $errors as $error ) {
				echo '<p class="pdr_admin_notice">' . wp_kses_post( $error ) . '</p>';
			}

			echo '</div>';
		}

		/**
		 * Add the custom meta boxes.
		 *
		 * @return void
		 */
		public static function add_meta_boxes() {
			// product selection meta box.
			add_meta_box( 'pdr-product-selection', esc_html__( 'Product Selection & Base Image Segmentation', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_product_selection' ), 'pdr_product_base', 'normal', 'high' );
			// product base data meta box.
			add_meta_box( 'pdr-product-base-data', esc_html__( 'Views', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_product_base_data' ), 'pdr_product_base', 'normal', 'high' );

			add_meta_box( 'pdr-attributes-data', esc_html__( 'Attributes', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_attributes_data' ), 'pdr_product_base', 'normal' );
			// Clipart data meta box.
			add_meta_box( 'pdr-clipart-data', esc_html__( 'Settings', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_clipart_data' ), 'pdr_cliparts', 'normal', 'high' );
			// Shape data meta box.
			add_meta_box( 'pdr-shape-data', esc_html__( 'Settings', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_shape_data' ), 'pdr_shapes', 'normal', 'high' );
			// Design Template data meta box.
			add_meta_box( 'pdr-design-template-data', esc_html__( 'Settings', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_design_template_data' ), 'pdr_design_templates', 'normal', 'high' );

			//Font Selection for Product Base
			add_meta_box( 'pdr-font-selection-data', esc_html__( 'Font Family Settings', 'product-designer-for-woocommerce' ), array( __CLASS__, 'render_font_selection_data' ), 'pdr_product_base', 'side' );
		}

		/**
		 * Render the attributes settings meta box.
		 * 
		 * @return void
		 */
		public static function render_attributes_data( $post ) {
			$product_base_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $product_base_id ) {
				return;
			}
			$rules = get_post_meta( $product_base_id, 'pdr_attributes', true );
			include_once ('views/html-attributes-data-panels.php');
		}

		/**
		 * Render the product base settings meta box.
		 *
		 * @return void
		 */
		public static function render_product_base_data( $post ) {

			$product_base_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $product_base_id ) {
				return;
			}

			$rules = get_post_meta( $product_base_id, 'pdr_rules', true );
			if ( ! pdr_check_is_array( $rules ) ) {
				$rule            = pdr_get_product_base_default_rule();
				$rule[ 'title' ] = 'Front View';
				$rules           = array( 'general' => $rule );
			}

			include_once ('views/html-product-base-data-panels.php');
		}

		/**
		 * Render the product base settings meta box.
		 *
		 * @return void
		 */
		public static function render_product_selection( $post ) {

			$product_base_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $product_base_id ) {
				return;
			}

			$product_ids  = ( array ) get_post_meta( $product_base_id, 'pdr_product_ids', true );
			$description  = get_post_meta( $product_base_id, 'pdr_description', true );
			$segmentation = get_post_meta( $product_base_id, 'pdr_image_segmentation', true );

			include_once ('views/html-product-base-product-selection.php');
		}

		public static function render_font_selection_data( $post ) {
			$product_base_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $product_base_id ) {
				return;
			}
			$fonts               = ( array ) get_post_meta( $product_base_id, 'pdr_font_selection', true );
			$api                 = new PDR_API();
			$get_fonts           = $api->get_fonts();
			$font_selection_type = array( '1' => 'Apply Global Settings', '2' => 'Selected Font Families' );
			$selected_font_type  = get_post_meta( $product_base_id, 'pdr_font_selection_type', true );
			include_once('views/html-product-base-font-selection.php');
		}

		/**
		 * Render the clipart settings meta box.
		 *
		 * @return void
		 */
		public static function render_clipart_data( $post ) {

			$clipart_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $clipart_id ) {
				return;
			}

			$clipart = pdr_get_clipart( $clipart_id );

			include_once ('views/html-clipart-data.php');
		}

		/**
		 * Render the shape settings meta box.
		 *
		 * @return void
		 */
		public static function render_shape_data( $post ) {

			$shape_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $shape_id ) {
				return;
			}

			$shape = pdr_get_shape( $shape_id );

			include_once ('views/html-shape-data.php');
		}

		/**
		 * Render the Design Template settings meta box.
		 *
		 * @return void
		 */
		public static function render_design_template_data( $post ) {

			$design_template_id = ( isset( $post->ID ) ) ? $post->ID : '';

			if ( ! $design_template_id ) {
				return;
			}

			$design_template = pdr_get_design_template( $design_template_id );

			include_once ('views/html-design-template-data.php');
		}

		/**
		 * Save the product base post.
		 *
		 * @return void
		 */
		public static function save_product_base_post( $post_id, $post ) {
			// To avoid loop when saving the post using wp_update_post.
			if ( empty( $post_id ) || empty( $post ) || self::$product_base_saved ) {
				return;
			}

			// Dont' save meta boxes for revisions or autosaves.
			if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}

			// Check the current user has permission to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// Check current post is product base.
			if ( 'pdr_product_base' != $post->post_type && isset( $_REQUEST ) ) {
				return;
			}

			self::$product_base_saved = true;

			$rules           = isset( $_REQUEST[ 'pdr_rules' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_rules' ] ) ) : array();
			$product_ids     = isset( $_REQUEST[ 'pdr_product_ids' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_product_ids' ] ) ) : '';
			$description     = isset( $_REQUEST[ 'pdr_description' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_description' ] ) ) : '';
			$base_attributes = isset( $_REQUEST[ 'pdr_attributes' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_attributes' ] ) ) : '';
			$segmentation    = isset( $_REQUEST[ 'pdr_image_segmentation' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_image_segmentation' ] ) ) : '';
			$font_type       = isset( $_REQUEST[ 'pdr_font_selection_type' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_font_selection_type' ] ) ) : '';
			$selected_fonts  = isset( $_REQUEST[ 'pdr_font_selection' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_font_selection' ] ) ) : '';
			if ( empty( $product_ids ) ) {
				self::add_error( esc_html__( 'Please assign a product for product base', 'product-designer-for-woocommerce' ) );
			}

			$meta_data         = array(
				'pdr_rules'               => $rules,
				'pdr_description'         => $description,
				'pdr_attributes'          => $base_attributes,
				'pdr_image_segmentation'  => $segmentation,
				'pdr_font_selection'      => $selected_fonts,
				'pdr_font_selection_type' => $font_type,
			);
			$check_product_ids = array();

			//$product_ids = pdr_check_is_array( $product_ids ) ? reset( $product_ids ) : '' ;
			if ( pdr_check_is_array( $product_ids ) ) {
				//check is product already exists

				$check_product_ids = self::product_id_already_exists( $product_ids, $post_id );

				if ( $check_product_ids ) {
					self::add_error( esc_html__( 'Product IDs  already linked in another product base', 'product-designer-for-woocommerce' ) );
				} else {
					$meta_data[ 'pdr_product_ids' ] = $product_ids;
				}
			}

			// Update the product base meta data.
			pdr_update_product_base( $post_id, $meta_data );
		}

		/**
		 * Product id already exists.
		 *
		 * @return void
		 */
		public static function product_id_already_exists( $product_ids, $post_id ) {
			if ( empty( $product_ids ) ) {
				return false;
			}

			$args = array(
				'post_type'   => 'pdr_product_base',
				'post_status' => array( 'publish', 'pending_review', 'private' ),
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'key'     => 'pdr_product_ids',
						'value'   => $product_ids,
						'compare' => 'IN'
					)
				),
				'exclude'     => array( $post_id )
			);

			$get_posts = get_posts( $args );
			return get_posts( $args );
		}

		/**
		 * Save the shape post.
		 *
		 * @return void
		 */
		public static function save_shape_post( $post_id, $post ) {
			// To avoid loop when saving the post using wp_update_post.
			if ( empty( $post_id ) || empty( $post ) || self::$shape_saved ) {
				return;
			}

			// Dont' save meta boxes for revisions or autosaves.
			if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}

			// Check the current user has permission to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// Check current post is shape.
			if ( 'pdr_shapes' != $post->post_type && isset( $_REQUEST ) ) {
				return;
			}

			self::$shape_saved = true;

			$price = isset( $_REQUEST[ 'pdr_price' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_price' ] ) ) : '';

			if ( empty( $post->post_content ) ) {
				self::add_error( esc_html__( 'Please add svg image in editor', 'product-designer-for-woocommerce' ) );
			}

			$meta_data = array(
				'pdr_price' => $price,
			);

			// Update the shape meta data.
			pdr_update_shape( $post_id, $meta_data );
		}

		/**
		 * Save the clipart post.
		 *
		 * @return void
		 */
		public static function save_clipart_post( $post_id, $post ) {
			// To avoid loop when saving the post using wp_update_post.
			if ( empty( $post_id ) || empty( $post ) || self::$cliparts_saved ) {
				return;
			}

			// Dont' save meta boxes for revisions or autosaves.
			if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}

			// Check the current user has permission to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// Check current post is clipart.
			if ( 'pdr_cliparts' != $post->post_type && isset( $_REQUEST ) ) {
				return;
			}

			self::$cliparts_saved = true;

			$price    = isset( $_REQUEST[ 'pdr_price' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_price' ] ) ) : '';
			$featured = isset( $_REQUEST[ 'pdr_featured' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_featured' ] ) ) : 'no';

			$thumbnail_id = get_post_thumbnail_id( $post );
			if ( empty( $thumbnail_id ) ) {
				self::add_error( esc_html__( 'Please Upload a feature image', 'product-designer-for-woocommerce' ) );
			}

			$meta_data = array(
				'pdr_price'    => $price,
				'pdr_featured' => $featured,
			);

			// Update the clipart meta data.
			pdr_update_clipart( $post_id, $meta_data );
		}

		/**
		 * Save the design template post.
		 *
		 * @return void
		 */
		public static function save_design_template_post( $post_id, $post ) {
			// To avoid loop when saving the post using wp_update_post.
			if ( empty( $post_id ) || empty( $post ) || self::$design_template_saved ) {
				return;
			}

			// Dont' save meta boxes for revisions or autosaves.
			if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
				return;
			}

			// Check the current user has permission to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// Check current post is design template.
			if ( 'pdr_design_templates' != $post->post_type && isset( $_REQUEST ) ) {
				return;
			}

			self::$design_template_saved = true;

			$price    = isset( $_REQUEST[ 'pdr_price' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_price' ] ) ) : '';
			$featured = isset( $_REQUEST[ 'pdr_featured' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_featured' ] ) ) : 'no';
			$content  = isset( $_REQUEST[ 'pdr_content' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_content' ] ) ) : '';
			$canvas   = isset( $_REQUEST[ 'pdr_canvas' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'pdr_canvas' ] ) ) : '';

			if ( empty( $content ) ) {
				self::add_error( esc_html__( 'Please Upload a file', 'product-designer-for-woocommerce' ) );
			}

			$meta_data = array(
				'pdr_price'    => $price,
				'pdr_featured' => $featured,
			);

			$attach_id = pdr_get_attachment_id_by_parent_id( $post_id );
			if ( $attach_id ) {
				wp_delete_attachment( $attach_id, true );
			}

			if ( ! empty( $canvas ) ) {
				$file       = new PDR_File( $canvas );
				$upload_dir = wp_upload_dir( gmdate( 'Y/m' ), true );
				$uploaded   = $file->upload_files( array( 'name' => uniqid() . '.txt' ), 'templates' . $upload_dir[ 'subdir' ] );

				if ( ! empty( $uploaded ) ) {
					foreach ( $uploaded as $file_path ) {
						$attach_id = $file->add_to_library( $file_path, $post_id );

						if ( $attach_id > 0 ) {
							$meta_data[ 'pdr_canvas_attachment_id' ] = $attach_id;
							update_post_meta( $attach_id, 'is_pdr', 1 );
						}
					}
				}
			}

			// Update the design template meta data.
			pdr_update_design_template( $post_id, $meta_data, array( 'post_content' => $content ) );
		}

	}

	PDR_Meta_Boxes::init();
	
endif;
