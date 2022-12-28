<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_Import' ) ) {

	class PDR_Import {

		public function __construct() {
			$this->taxonomy = 'pdr_product_base_cat' ;
			register_activation_hook( PDR_PLUGIN_FILE , array( $this , 'import_bases' ) ) ;
			add_action( 'pdr_import_background_process' , array( $this , 'perform_import_functionality' ) ) ;
		}

		public function import_bases() {
			$path       = PDR_PLUGIN_DIR ;
			$path       = $path . 'assets/img/bases/' ;
			$files      = array_filter( array_diff( scandir( $path ) , array( '.' , '..' ) ) ) ;
			$i          = 0 ;
			$final_data = array() ;
			if ( is_array( $files ) && ! empty( $files ) ) {
				foreach ( $files as $each_file ) {
					$key                  = str_replace( array( '-front-view.png' , '-back-view.png' , '.png' ) , array() , $each_file ) ;
					$final_data[ $key ][] = $each_file ;
				}
			}

			$chunk_data = array_filter(array_chunk( $final_data , '1' , true )) ;
					   
			if ( is_array( $chunk_data ) && ! empty( $chunk_data ) ) {
				foreach ( $chunk_data as $each_key => $each_data ) {
					as_schedule_single_action( time() , 'pdr_import_background_process' , array( 'bases' => $each_data ) ) ;
				}
			}
		}

		public function get_base_product_data() {
						/**
						 * Import image details and its data
						 *
						 * @since 1.0
						 */
			$group_data = apply_filters( 'pdr_import_bases_details' , array(
				'cap'                    => array(
					'title'      => 'Cap' ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'cap-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'cap-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'coffee-mug'             => array(
					'title'      => 'Coffee Mug' ,
					'categories' => array( 'cupsandmugs' ) ,
					'clip_area'  => array(
						'coffee-mug-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'coffee-mug-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'drawstring-bag'         => array(
					'title'      => 'Drawstring Bag' ,
					'categories' => array( 'bags' ) ,
					'clip_area'  => array(
						'drawstring-bag-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
					) ) ,
				'fabric-bag'             => array(
					'title'      => 'Fabric Bag' ,
					'categories' => array( 'bags' ) ,
					'clip_area'  => array(
						'fabric-bag-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'fabric-bag-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'laptop-skin'            => array(
					'title'      => 'Laptop Skin' ,
					'categories' => array( 'computeraccessories' ) ,
					'clip_area'  => array(
						'laptop-skin-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
					) ) ,
				'large-coffee-mug'       => array(
					'title'      => 'Large Coffee Mug' ,
					'categories' => array( 'cupsandmugs' ) ,
					'clip_area'  => array(
						'large-coffee-mug-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'large-coffee-mug-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'men-hoodie'             => array(
					'title'      => "Men's Hoodie" ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'men-hoodie-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'men-hoodie-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'men-long-sleeve-tshirt' => array(
					'title'      => "Men's Long Sleeve Tshirt" ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'men-long-sleeve-tshirt-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'men-long-sleeve-tshirt-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'men-tshirt'             => array(
					'title'      => "Men's Tshirt" ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'men-tshirt-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'men-tshirt-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'paper-coffee-cup'       => array(
					'title'      => 'Paper Coffee Cup' ,
					'categories' => array( 'cupsandmugs' ) ,
					'clip_area'  => array(
						'paper-coffee-cup-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'paper-coffee-cup-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'pillow'                 => array(
					'title'      => 'Pillow' ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'pillow-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'pillow-back-view.png' => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
					) ) ,
				'pin-badge'              => array(
					'title'      => 'Pin Badge' ,
					'categories' => array( 'badges' ) ,
					'clip_area'  => array(
						'pin-badge-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
					) ) ,
				'poster'                 => array(
					'title'      => 'Poster' ,
					'categories' => array( 'posters' ) ,
					'clip_area'  => array(
						'poster-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
					) ) ,
				'sneakers'               => array(
					'title'      => 'Sneakers' ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'sneakers-front-view.png' => array( 'title' => 'Right Side View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'sneakers-back-view.png'  => array( 'title' => 'Left Side View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'sports-shoe'            => array(
					'title'      => 'Sports Shoe' ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'sports-shoe-front-view.png' => array( 'title' => 'Right Side View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'sports-shoe-back-view.png'  => array( 'title' => 'Left Side View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) ) ,
				'women-tshirt'           => array(
					'title'      => "Women's Tshirt" ,
					'categories' => array( 'apparel' ) ,
					'clip_area'  => array(
						'women-tshirt-front-view.png' => array( 'title' => 'Front View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' ) ,
						'women-tshirt-back-view.png'  => array( 'title' => 'Back View' , 'x' => '' , 'y' => '' , 'w' => '' , 'h' => '' , 'factor' => '' , 'imgx' => '' , 'imgy' => '' , 'imgw' => '' , 'imgh' => '' )
					) )
					) ) ;
			return $group_data ;
		}

		public function create_category_data() {
						/**
						 * Create/Alter Category Data
						 *
						 * @since 1.0
						 */
			$category_data = apply_filters( 'pdr_import_category_data' , array(
				'apparel'             => 'Apparel' ,
				'cupsandmugs'         => 'Cups and Mugs' ,
				'bags'                => 'Bags' ,
				'computeraccessories' => 'Computer Accessories' ,
				'badges'              => 'Badges' ,
				'posters'             => 'Posters' ,
					) ) ;
			return $category_data ;
		}

		public function perform_import_functionality( $bases ) {
			if ( is_array( $bases ) && ! empty( $bases ) ) {
				foreach ( $bases as $key => $each_base ) {
					$check_is_already_inserted = ( array ) get_option( 'pdr_is_already_imported' ) ;
					if ( ! in_array( $key , $check_is_already_inserted ) ) {
						$title         = $this->get_base_product_data()[ $key ][ 'title' ] ;
						$categories    = $this->get_base_product_data()[ $key ][ 'categories' ] ; //slug name
						$term_id       = $this->get_category( $categories[ 0 ] ) ? $this->get_category( $categories[ 0 ] ) : 0 ;
						$base_id       = $this->insert_product_base( $title , array( $term_id ) ) ;
						//each data will be array
						//rsort
						rsort( $each_base ) ;
						$all_view_data = array() ;
						if ( is_array( $each_base ) && ! empty( $each_base ) ) {
							foreach ( $each_base as $index_key => $additional_base ) {
								$attachment_id         = $this->insert_image( $additional_base ) ; //import image
								$uniqkey               = 0 == $index_key ? 'general' : uniqid() ;
								$get_current_view_data = $this->get_base_product_data()[ $key ][ 'clip_area' ] ;
								if ( isset( $get_current_view_data[ $additional_base ] ) ) {
									$loop_view_details = $get_current_view_data[ $additional_base ] ;
									if ( is_array( $loop_view_details ) && ! empty( $loop_view_details ) ) {
										foreach ( $loop_view_details as $rule_key => $rule_details ) {
											$all_view_data[ $uniqkey ][ $rule_key ] = $rule_details ;
										}
										$all_view_data[ $uniqkey ][ 'img_id' ]  = $attachment_id ;
										$all_view_data[ $uniqkey ][ 'img_url' ] = wp_get_attachment_url( $attachment_id ) ;
										
										//set default width and height to crop selection area
										$all_view_data[ $uniqkey ][ 'w' ]  = '80' ;
										$all_view_data[ $uniqkey ][ 'h' ]  = '80' ;
										
										//attachment width and height
										$image_meta_data                        = wp_get_attachment_metadata( $attachment_id ) ;
										if ( $image_meta_data ) {
											$all_view_data[ $uniqkey ][ 'imgw' ] = $image_meta_data[ 'width' ] ;
											$all_view_data[ $uniqkey ][ 'imgh' ] = $image_meta_data[ 'height' ] ;
										}
									}
								}
								update_post_meta( $base_id , 'pdr_rules' , $all_view_data ) ;
							}
						}
						$check_is_already_inserted[] = $key ;
						update_option( 'pdr_is_already_imported' , $check_is_already_inserted ) ;
					}
				}
			}
		}

		//insert image
		public function insert_image( $file ) {
			$path          = PDR_PLUGIN_DIR ;
			$path          = $path . 'assets/img/bases/' ;
			$file          = $path . $file ;
			$filename      = basename( $file ) ;
			$attachment_id = 0 ;
			$upload_file   = wp_upload_bits( $filename , null , file_get_contents( $file ) ) ;
			if ( ! $upload_file[ 'error' ] ) {
				$wp_filetype   = wp_check_filetype( $filename , null ) ;
				$attachment    = array(
					'post_mime_type' => $wp_filetype[ 'type' ] ,
					'post_title'     => preg_replace( '/\.[^.]+$/' , '' , $filename ) ,
					'post_content'   => '' ,
					'post_status'    => 'inherit'
						) ;
				$attachment_id = wp_insert_attachment( $attachment , $upload_file[ 'file' ] ) ;
				if ( ! is_wp_error( $attachment_id ) ) {
					require_once(ABSPATH . 'wp-admin/includes/image.php') ;
					$attachment_data = wp_generate_attachment_metadata( $attachment_id , $upload_file[ 'file' ] ) ;
					wp_update_attachment_metadata( $attachment_id , $attachment_data ) ;
				}
			}
			return $attachment_id ;
		}

		public function get_category( $slug ) {
			$term_id = false ;
			$term    = get_term_by( 'slug' , $slug , $this->taxonomy ) ;
			if ( $term ) {
				$term_id = $term->term_id ;
			} else {
				//create category with slug
				$term_id = $this->create_category( $slug ) ;
			}
			return $term_id ;
		}

		public function create_category( $slug ) {
			$get_data             = false ;
			$create_category_data = $this->create_category_data() ;
			if ( isset( $create_category_data[ $slug ] ) ) {
				$get_data = wp_insert_term(
						$create_category_data[ $slug ] ,
						$this->taxonomy ,
						array(
							'slug' => $slug ,
						) ) ;
				$get_data = $get_data[ 'term_id' ] ;
			}
			return $get_data ;
		}

		//insert product base
		public function insert_product_base( $title, $categories ) {
			$product_base_args = array(
				'post_title'  => wp_strip_all_tags( $title ) ,
				'post_status' => 'pending' ,
				'post_type'   => 'pdr_product_base' ,
					) ;
			$product_base_id   = wp_insert_post( $product_base_args ) ;
			wp_set_object_terms( $product_base_id , $categories , 'pdr_product_base_cat' ) ;
			return $product_base_id ;
		}

	}

	new PDR_Import() ;
}
