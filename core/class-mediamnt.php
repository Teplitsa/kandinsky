<?php /**
 * Class for Media management
 **/

if ( ! defined( 'WPINC' ) )
	die();

class TST_Media {

	private static $_instance = null;

	private $remote_host = 'http://xn--56-6kchvgg0bfe4n5a.xn--p1ai/wp';

	private $local_host = null;

	private $upload_dir = null;

	/* Construct */
	private function __construct() {
		if ( defined( 'TST_DEVMODE' ) && TST_DEVMODE ) {
			add_action( 'tst_before_display_attachment', array( $this, 'regenerate_attachment' ), 2, 2 );
		}
	}

	public static function get_instance() {
		
		// If the single instance hasn't been set, set it now.
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	/** Helpers **/
	private function _get_upload_dir() {
		if ( ! ( $this->upload_dir ) ) {
			$this->upload_dir = wp_upload_dir();
		}
		
		return $this->upload_dir;
	}

	private function _get_local_host() {
		if ( ! isset( $this->local_host ) ) {
			$this->local_host = str_replace( 'wp-content', '', WP_CONTENT_URL );
		}
		
		return $this->local_host;
	}

	/** Download images from production by request **/
	
	// action for tst_get_thumbnail
	public function localize_thumbnail( $post_id, $size = 'post-thumbnail' ) {
		
		$thumb_id = get_post_thumbnail_id( $post_id );
		
		if ( ! $this->_is_original_file_exists( $thumb_id ) ) { // do we have original file
		                                                        
			// download it first
			$saved = $this->_save_remote_image( $thumb_id );
			
			// regenerate thumbnails
			if ( false !== $saved )
				$this->regenerate_thumbnails( $thumb_id );
		} elseif ( ! $this->_is_thumbnail_registered( $thumb_id, $size ) ) { // do we have propper thumbnail size
		                                                                     
			// regenerate thumbnails
			$this->regenerate_thumbnails( $thumb_id );
		}
	}

	public function localize_attachment( $attachment_id ) {
		if ( ! $this->_is_original_file_exists( $attachment_id ) ) { // do we have original file
		                                                             
			// download it first
			$saved = $this->_save_remote_image( $attachment_id );
			
			// regenerate thumbnails
			if ( false !== $saved )
				$this->regenerate_thumbnails( $attachment_id );
		} else {
			// just regenerate thumbnails
			$this->regenerate_thumbnails( $attachment_id );
		}
	}

	public function regenerate_attachment( $attachment_id, $test_size ) {
		if ( ! $this->_is_thumbnail_registered( $attachment_id, $test_size ) ) {
			$this->regenerate_thumbnails( $attachment_id );
		}
	}

	public function localize_images_in_content( $content ) {
		if ( is_page() && ! is_front_page() )
			return $content; // not on pages
		
		if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
			return $content;
		}
		
		foreach ( $matches[0] as $image ) {
			if ( preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) && ( $attachment_id = absint( $class_id[1] ) ) ) {
				
				if ( ! $this->_is_original_file_exists( $attachment_id ) ) {
					
					$saved = $this->_save_remote_image( $attachment_id ); // download it first
					
					if ( false !== $saved )
						$this->regenerate_thumbnails( $attachment_id ); // regenerate thumbnails
				}
			}
		}
		
		return $content;
	}
	
	// download image fromt url
	private function _save_remote_image( $att_id ) {
		if ( ! $att_id )
			return false;
			
			// local url
		$local_url = wp_get_attachment_url( $att_id );
		$local_url = str_replace( array( 'https:', 'http:' ), '', $local_url );
		
		// try sideload folder first
		$uploads = $this->_get_upload_dir();
		$base_url = str_replace( array( 'https:', 'http:' ), '', $uploads['baseurl'] );
		$orig_file = wp_basename( wp_get_attachment_url( $att_id ) );
		$side_load_path = $uploads['basedir'] . '/sideload/' . $orig_file;
		
		if ( file_exists( $side_load_path ) ) { // exists - move it
			$path = str_replace( $base_url, $uploads['basedir'], $local_url );
			
			if ( ! file_exists( dirname( $path ) ) ) {
				mkdir( dirname( $path ), 0775, true );
			}
			
			// move
			$r = copy( $side_load_path, $path ); // rename?
		} else { // try remote location
			
			$remote_url = str_replace( untrailingslashit( $this->_get_local_host() ), $this->remote_host, $local_url );
			$image = wp_remote_get( $remote_url, array( 'timeout' => 25, 'sslverify' => false ) );
			
			$r = false;
			if ( ! is_wp_error( $image ) && isset( $image['body'] ) ) {
				if ( isset( $image['headers']['content-type'] ) &&
					 false !== strpos( $image['headers']['content-type'], 'image' ) ) {
					$path = str_replace( $uploads['baseurl'], $uploads['basedir'], $local_url );
					
					if ( ! file_exists( dirname( $path ) ) ) {
						mkdir( dirname( $path ), 0775, true );
					}
					
					$r = file_put_contents( $path, $image['body'] );
				}
			}
		}
		return $r;
	}
	
	// check whether url exist by thumbnail ID and size
	private function _is_original_file_exists( $att_id ) {
		$image_fullpath = get_attached_file( $att_id );
		
		// test for correct type
		if ( file_exists( $image_fullpath ) && class_exists( 'finfo' ) ) {
			$file_info = new finfo( FILEINFO_MIME );
			$mime_type = $file_info->buffer( file_get_contents( $image_fullpath ) ); // e.g. gives "image/jpeg"
			
			if ( false !== strpos( $mime_type, 'image' ) ) {
				return true;
			} else {
				unlink( $image_fullpath );
			}
		}
		
		return false;
	}

	private function _is_thumbnail_registered( $att_id, $size = 'full' ) {
		if ( $size == 'full' )
			return true; // this is original
		
		if ( ! is_array( $imagedata = wp_get_attachment_metadata( $att_id ) ) )
			return false; // no data about thumbnails
		
		if ( isset( $imagedata['sizes'][$size] ) ) {
			// test that we have real file
			$uploads = $this->_get_upload_dir();
			$size = image_get_intermediate_size( $att_id, $size );
			$path = ( $size['path'] ) ? $uploads['basedir'] . '/' . $size['path'] : '';
			
			if ( empty( $path ) || ! file_exists( $path ) )
				return false; // don't have image file of proper size
			
			return true;
		}
		
		return false;
	}
	
	// regenerate thumbnails
	public function regenerate_thumbnails( $att_id ) {
		$upload_dir = $this->_get_upload_dir();
		$image_fullpath = get_attached_file( $att_id );
		
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once ( ABSPATH . 'wp-admin/includes/image.php' );
		}
		
		// Incorrect image path cases - fix them on demand
		if ( false === $image_fullpath || strlen( $image_fullpath ) == 0 ) {
			throw new Exception( 'Empty image path for attachment ID' . $att_id );
		}
		
		if ( ( strrpos( $image_fullpath, $upload_dir['basedir'] ) === false ) ) {
			throw new Exception( 'Image path incomplete for attachment ID' . $att_id );
		}
		
		if ( ! file_exists( $image_fullpath ) || realpath( $image_fullpath ) === false ) {
			throw new Exception( 'Image don\'t exists for attachment ID' . $att_id );
		}
		
		// Results
		$thumb_deleted = array();
		$thumb_error = array();
		$thumb_regenerate = array();
		
		// Hack to find thumbnail
		$file_info = pathinfo( $image_fullpath );
		$file_info['filename'] .= '-';
		
		/**
		 * Try delete all thumbnails
		 */
		$files = array();
		$path = opendir( $file_info['dirname'] );
		
		if ( false !== $path ) {
			while ( false !== ( $thumb = readdir( $path ) ) ) {
				if ( ! ( strrpos( $thumb, $file_info['filename'] ) === false ) ) {
					$files[] = $thumb;
				}
			}
			closedir( $path );
			sort( $files );
		}
		foreach ( $files as $thumb ) {
			$thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
			$thumb_info = pathinfo( $thumb_fullpath );
			$valid_thumb = explode( $file_info['filename'], $thumb_info['filename'] );
			if ( $valid_thumb[0] == "" ) {
				$dimension_thumb = explode( 'x', $valid_thumb[1] );
				if ( count( $dimension_thumb ) == 2 ) {
					if ( is_numeric( $dimension_thumb[0] ) && is_numeric( $dimension_thumb[1] ) ) {
						unlink( $thumb_fullpath );
						if ( ! file_exists( $thumb_fullpath ) ) {
							$thumb_deleted[] = sprintf( "%sx%s", $dimension_thumb[0], $dimension_thumb[1] );
						} else {
							$thumb_error[] = sprintf( "%sx%s", $dimension_thumb[0], $dimension_thumb[1] );
						}
					}
				}
			}
		}
		
		/**
		 * Regenerate all thumbnails
		 */
		$metadata = wp_generate_attachment_metadata( $att_id, $image_fullpath );
		if ( is_wp_error( $metadata ) ) {
			throw new Exception( $metadata->get_error_message() );
		}
		if ( empty( $metadata ) ) {
			throw new Exception( 'Unknown failure reason.' );
		}
		wp_update_attachment_metadata( $att_id, $metadata );
		
		/**
		 * Verify results (deleted, errors, success)
		 */
		$files = array();
		$path = opendir( $file_info['dirname'] );
		if ( false !== $path ) {
			while ( false !== ( $thumb = readdir( $path ) ) ) {
				if ( ! ( strrpos( $thumb, $file_info['filename'] ) === false ) ) {
					$files[] = $thumb;
				}
			}
			closedir( $path );
			sort( $files );
		}
		foreach ( $files as $thumb ) {
			$thumb_fullpath = $file_info['dirname'] . DIRECTORY_SEPARATOR . $thumb;
			$thumb_info = pathinfo( $thumb_fullpath );
			$valid_thumb = explode( $file_info['filename'], $thumb_info['filename'] );
			if ( $valid_thumb[0] == "" ) {
				$dimension_thumb = explode( 'x', $valid_thumb[1] );
				if ( count( $dimension_thumb ) == 2 ) {
					if ( is_numeric( $dimension_thumb[0] ) && is_numeric( $dimension_thumb[1] ) ) {
						$thumb_regenerate[] = sprintf( "%sx%s", $dimension_thumb[0], $dimension_thumb[1] );
					}
				}
			}
		}
		
		// Remove success if has in error list
		foreach ( $thumb_regenerate as $key => $regenerate ) {
			if ( in_array( $regenerate, $thumb_error ) )
				unset( $thumb_regenerate[$key] );
		}
		
		// Remove deleted if has in success list
		foreach ( $thumb_deleted as $key => $deleted ) {
			if ( in_array( $deleted, $thumb_regenerate ) )
				unset( $thumb_deleted[$key] );
		}
	}

	function register_uploaded_file( $path ) {
		$filename = basename( $path );
		$uploads = $this->_get_upload_dir();
		
		$a_url = $uploads['url'] . '/' . $filename;
		
		$attachment_id = attachment_url_to_postid( $a_url );
		if ( $attachment_id ) {
			return $attachment_id; // already registered
		}
		
		$attachment_id = false;
		$wp_filetype = wp_check_filetype( $filename, null );
		
		$attachment_title = preg_replace( '/\.[^.]+$/', '', $filename );
		$attachment = array( 
			'post_mime_type' => $wp_filetype['type'], 
			'post_parent' => 0, 
			'post_title' => $attachment_title, 
			'post_name' => 'datt-' . sanitize_title( $attachment_title ), 
			'post_content' => '', 
			'post_status' => 'inherit' );
		
		$attachment_file = ltrim( $uploads['subdir'] . '/' . $filename, '/' );
		$attachment_id = wp_insert_attachment( $attachment, $attachment_file, 0 );
		
		if ( ! is_wp_error( $attachment_id ) ) {
			require_once ( ABSPATH . 'wp-admin/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $attachment_file );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			
			$this->regenerate_thumbnails( $attachment_id );
		}
		
		return $attachment_id;
	}

	function upload_img_from_path( $path ) {
		if ( ! $path || ! file_exists( $path ) )
			return false;

		$attachment_id = false;

		$file = file_get_contents( $path );

		if ( $file ) {
			$filename = basename( $path );
			$upload_file = wp_upload_bits( $filename, null, $file );
			
			if ( ! $upload_file['error'] ) {
				$wp_filetype = wp_check_filetype( $filename, null );
				
				$attachment_title = preg_replace( '/\.[^.]+$/', '', $filename );
				$attachment = array( 
					'post_mime_type' => $wp_filetype['type'], 
					'post_parent' => 0, 
					'post_title' => $attachment_title, 
					'post_name' => 'datt-' . sanitize_title( $attachment_title ), 
					'post_content' => '', 
					'post_status' => 'inherit' );
				
				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );
				
				if ( ! is_wp_error( $attachment_id ) ) {
					require_once ( ABSPATH . 'wp-admin/includes/image.php' );
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );
				}
			}
		}
		
		return $attachment_id;
	}

	function upload_file_from_path( $path ) {
		$attachment_id = 0;
		
		if ( ! file_exists( $path ) ) {
			return $attachment_id;
		}
		
		$filename = basename( $path );
		$filename_no_ext = pathinfo( $path, PATHINFO_FILENAME );
		
		$mime_type = mime_content_type( $path );
		
		$tmp_dir = get_temp_dir();
		if ( ! is_dir( $tmp_dir ) ) {
			mkdir( $tmp_dir, 0777, true );
		}
		$tmp_path = $tmp_dir . 'knd-' . $filename;
		copy( $path, $tmp_path );
		
		$fake_FILE = array( 
			'name' => $filename, 
			'type' => $mime_type, 
			'tmp_name' => $tmp_path, 
			'size' => filesize( $tmp_path ) );
		
		$_FILES['file'] = $fake_FILE;
		
		$result = wp_handle_upload( $_FILES['file'], array( 'test_form' => false, 'action' => 'local' ) );
		
		unset( $_FILES[basename( $tmp_path )] );
		
		if ( empty( $result['error'] ) ) {
			$args = array( 
				'post_title' => $filename_no_ext, 
				'post_name' => 'datt-' . sanitize_title( $filename_no_ext ), 
				'post_content' => '', 
				'post_status' => 'inherit', 
				'post_mime_type' => $result['type'] );
			$attachment_id = wp_insert_attachment( $args, $result['file'] );
			
			if ( is_wp_error( $attachment_id ) ) {
				$attachment_id = false;
			} else {
				$attach_data = wp_generate_attachment_metadata( $attachment_id, $result['file'] );
				wp_update_attachment_metadata( $attachment_id, $attach_data );
			}
		}
		
		return $attachment_id;
	}
} // class

TST_Media::get_instance();

// upload file from local folder
function tst_upload_img_from_path( $path ) {
	$mnt = TST_Media::get_instance();
	return $mnt->upload_img_from_path( $path );
}

function knd_upload_file_from_path( $path ) {
	$mnt = TST_Media::get_instance();
	return $mnt->upload_file_from_path( $path );
}

function tst_register_uploaded_file( $path ) {
	$mnt = TST_Media::get_instance();
	return $mnt->register_uploaded_file( $path );
}
