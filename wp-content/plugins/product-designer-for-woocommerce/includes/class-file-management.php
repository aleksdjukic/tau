<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PDR_File' ) ) {

	/**
	 * PDR_File class.
	 */
	class PDR_File {

		/**
		 * Base directory folder name 
		 * 
		 * @var string 
		 */
		protected $base_folder_name = 'pdr';

		/**
		 * Allow to upload files in the base directory.
		 * 
		 * @var string 
		 */
		protected $base_directory;

		/**
		 * Allow to upload files in the separate directory rather in the base.
		 * 
		 * @var string 
		 */
		protected $sub_directory;

		/**
		 * Constructor.
		 */
		public function __construct( $content = '', $filename = '' ) {
			$this->get_base_directory();
			$this->content  = $content;
			$this->filename = $filename;
		}

		/**
		 * Get the base directory of our plugin uploads.
		 * 
		 * @return string
		 */
		protected function get_base_directory() {
			$upload_dir           = wp_upload_dir();
			$this->base_directory = $upload_dir[ 'basedir' ] . '/' . $this->base_folder_name . '/';
			return $this->base_directory;
		}

		/**
		 * Maybe make the directory to upload our files.
		 */
		protected function maybe_make_directory( $sub_dirname = null ) {
			$directory = $this->base_directory . trim( $sub_dirname );

			if ( ! empty( $sub_dirname ) && is_string( $sub_dirname ) ) {
				wp_mkdir_p( $directory );
			}

			return $directory;
		}

		/**
		 * Prepare the file path from the given filename.
		 */
		protected function prepare_file_path( $file_name, $sub_dirname = null ) {
			$file_name = sanitize_file_name( $file_name );
			return $this->maybe_make_directory( $sub_dirname ) . '/' . $file_name;
		}

		/**
		 * Upload the files to server.
		 * 
		 * @param array $files
		 * @param string $sub_dirname
		 * @return array
		 */
		public function upload_files( $files, $sub_dirname = null ) {
			if ( empty( $files ) || ! is_array( $files ) ) {
				return array();
			}

			$uploaded = array();
			if ( isset( $files[ 'name' ] ) ) {
				$file_path = $this->prepare_file_path( $files[ 'name' ], $sub_dirname );

				$this->create( $file_path );

				if ( file_exists( $file_path ) ) {
					$uploaded[ $files[ 'name' ] ] = $file_path;
				}
			} else {
				$files_count = count( $files );

				for ( $i = 0; $i < $files_count; $i ++ ) {
					if ( empty( $files[ $i ] ) ) {
						continue;
					}

					$file_path = $this->prepare_file_path( $files[ $i ][ 'name' ], $sub_dirname );

					$this->create( $file_path );

					if ( file_exists( $file_path ) ) {
						$uploaded[ $files[ $i ][ 'name' ] ] = $file_path;
					}
				}
			}

			return $uploaded;
		}

		/**
		 * Add the files to WP media.
		 * 
		 * @param string $file_path Should be the path to a file in the upload directory.
		 * @param int $reference_id The ID of the post this attachment is for.
		 * @return int Attachment ID
		 */
		public function add_to_library( $file_path, $reference_id = 0 ) {
			// Check the type of file.
			$filetype = wp_check_filetype( basename( $file_path ), null );

			// Get the path to the upload directory.
			$upload_dir = wp_upload_dir();
			$attachment = array(
				'guid'           => $upload_dir[ 'url' ] . '/' . basename( $file_path ),
				'post_mime_type' => $filetype[ 'type' ],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $file_path, $reference_id );

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			if ( $reference_id ) {
				set_post_thumbnail( $reference_id, $attach_id );
			}

			return $attach_id;
		}

		/**
		 * Create the file.
		 * 
		 * @param string $file
		 * @return string
		 */
		public function create( $file = '' ) {
			if ( empty( $file ) ) {
				$file = $this->base_directory . $this->filename . '.txt';
			}

			$chmod_dir = ( 0755 & ~ umask() );

			if ( defined( 'FS_CHMOD_DIR' ) ) {
				$chmod_dir = FS_CHMOD_DIR;
			}

			if ( ! file_exists( $file ) ) {
				$temphandle = @fopen( $file, 'w+' ); // @codingStandardsIgnoreLine.
				@fclose( $temphandle ); // @codingStandardsIgnoreLine.
				@chmod( $file, $chmod_dir ); // @codingStandardsIgnoreLine.
			}

			$resource = @fopen( $file, 'a' ); // @codingStandardsIgnoreLine.
			if ( $resource ) {
				fwrite( $resource, $this->content ); // @codingStandardsIgnoreLine.
			}

			fclose( $resource );
			return $this->filename;
		}

		public function update() {
			$this->delete();
			$this->create();
		}

		public function retrieve() {
			$result   = false;
			$filename = $this->filename;
			if ( '' == $filename ) {
				return;
			}
			$path = $this->base_directory . $filename . '.txt';
			if ( file_exists( $path ) ) {
				$result = file_get_contents( $path );
			}
			//var_dump($result);
			return $result;
		}

		public function delete() {
			$result   = false;
			$filename = $this->filename;
			$path     = $this->base_directory . $filename . '.txt';
			if ( file_exists( $path ) ) {
				$result = unlink( $path );
			}
			return $result;
		}

	}

}
