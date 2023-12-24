<?php

if ( ! defined( 'WPINC' ) )
	die();

class TST_Import {

	private static $convert2pdf_ext = array( 'doc', 'docx' );

	private static $date_from_url = array( 
		'bereginya' => array( 
			array( 'regexp' => '/\/(\d+\/\d+-\d+).\w+$/i', 'pattern' => '%Y/%y-%m' ), 
			array( 'regexp' => '/\/(\d+-\d+).\w+$/i', 'pattern' => '%Y-%m' ) ), 
		
		'report' => array( array( 'regexp' => '/\/(\d{4})[^\/]*.\w+$/i', 'pattern' => '%Y' ) ), 
		'common' => array( 
			array( 'regexp' => '/(\d+-\d+-\d+).\w+$/i', 'pattern' => '%d-%m-%y' ), 
			array( 'regexp' => '/[^\/]*(\d{4})[^\/]*\.\w+$/i', 'pattern' => '%Y' ) ) );

	private static $_instance = null;

	private function __construct() {
	}

	public static function get_instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function get_post_by_old_url( $old_url ) {
		$args = array( 
			'post_type' => array( 'post', 'project', 'event', 'person' ), 
			'meta_query' => array( array( 'key' => 'old_url', 'value' => $old_url ) ), 
			'fields' => 'ids' );
		$posts = get_posts( $args );
		$post_id = count( $posts ) ? $posts[0] : null;
		return $post_id ? get_post( $post_id ) : null;
	}

	public function get_post_by_meta_value( $meta_key, $meta_value ) {
		$args = array( 
			'post_type' => array( 'post', 'project', 'event', 'person' ), 
			'post_parent' => 0, 
			'meta_query' => array( array( 'key' => $meta_key, 'value' => $meta_value ) ), 
			'fields' => 'ids' );
		$posts = get_posts( $args );
		$post_id = count( $posts ) ? $posts[0] : null;
		return $post_id ? get_post( $post_id ) : null;
	}

	public function get_attachment_by_old_url( $old_url ) {
		$args = array( 
			'post_type' => 'attachment', 
			'meta_query' => array( array( 'key' => 'old_url', 'value' => $old_url ) ), 
			'fields' => 'ids', 
			'orderby' => 'ID', 
			'order' => 'DESC' );
		$posts = get_posts( $args );
		$post_id = count( $posts ) ? $posts[0] : null;
		return $post_id ? get_post( $post_id ) : null;
	}

	public function set_attachment_old_page_url( $attachment_id, $old_page_url ) {
		update_post_meta( $attachment_id, 'old_parent_page_url', $old_page_url );
	}

	public function import_file( $url ) {
		$attachment_id = 0;
		
		$file = wp_remote_get( $url, array( 'timeout' => 50, 'sslverify' => false ) );
		
		if ( ! is_wp_error( $file ) && isset( $file['body'] ) ) {
			
			$response_code = $file['response']['code'];
			
			if ( $response_code == '200' && isset( $file['headers']['content-type'] ) ) {
				
				$filename = basename( $url );
				$upload_file = wp_upload_bits( $filename, null, $file['body'] );
				
				if ( ! $upload_file['error'] ) {
					$wp_filetype = wp_check_filetype( $filename, null );
					
					$attachment_title = preg_replace( '/\.[^.]+$/', '', $filename );
					$attachment = array( 
						'post_mime_type' => $wp_filetype['type'], 
						'post_parent' => 0, 
						'post_title' => $attachment_title, 
						'post_name' => 'datt-' . sanitize_title( $attachment_title ), 
						'post_content' => '', 
						'post_status' => 'inherit', 
						'meta_input' => array( 'old_url' => $url ) );
					
					$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );
					
					if ( ! is_wp_error( $attachment_id ) ) {
						require_once ( ABSPATH . 'wp-admin/includes/image.php' );
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
						wp_update_attachment_metadata( $attachment_id, $attachment_data );
					}
				}
			}
		}
		unset( $file );
		
		return $attachment_id;
	}

	public function import_big_file( $url ) {
		
		$tmp_file = download_url($url);
		
		if( is_wp_error($tmp_file) ) {
			throw new Exception( sprintf( esc_html__( "File wasn't uploaded: %s", 'knd' ), $tmp_file->get_error_message() ) );
		}
		
		$filename_no_ext = pathinfo( $url, PATHINFO_FILENAME );
		$extension = pathinfo( $url, PATHINFO_EXTENSION );
		$filedir = dirname( $tmp_file );
		$new_file = $filedir . "/" . $filename_no_ext . '.' . $extension;
		
		if ( ! file_exists( $tmp_file ) ) {
			throw new Exception( sprintf( esc_html__( "%s - temporary download file doesn't exist", 'knd' ), $new_file ) );
		} else 
			if ( file_exists( $new_file ) && ! unlink( $new_file ) ) {
				throw new Exception( 
					sprintf( esc_html__( "%s - a downloaded file already exists and can't be deleted", 'knd' ), $new_file ) );
			} else 
				if ( file_exists( $new_file ) && ! is_writable( $new_file ) && ! chmod( $new_file, '0755' ) ) {
					throw new Exception( 
						sprintf( esc_html__( "%s - a downloaded file isn't writable and can't be made so", 'knd' ), $new_file ) );
				} else 
					if ( ! rename( $tmp_file, $new_file ) ) {
						throw new Exception( 
							sprintf( esc_html__( "Can't rename downloaded file: from %s to %s", 'knd' ), $tmp_file, $new_file ) );
					}
		
		$tmp_file = $new_file;
		
		$attachment_id = knd_upload_file_from_path( $tmp_file );
		
		if ( file_exists( $tmp_file ) ) {
			unlink( $tmp_file );
		}
		
		if ( $attachment_id ) {
			update_post_meta( $attachment_id, 'old_url', $url );
		} else {
			throw new Exception( sprintf( esc_html__( "File wasn't uploaded: %s", 'knd' ), $tmp_file ) );
		}
		
		return $attachment_id;
	}

	public function import_local_file( $path ) {
		$attachment_id = knd_upload_file_from_path( $path );
		
		if ( $attachment_id ) {
			update_post_meta( $attachment_id, 'old_url', $path );
		}
		
		return $attachment_id;
	}

	public function import_file_from_path( $path ) {
		if ( ! $path || ! file_exists( $path ) )
			return false;
		
		$attachment_id = false;
		$file = Knd_Filesystem::get_instance()->get_contents( $path );
		
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
			}
		}
		
		return $attachment_id;
	}

	public function get_ext_from_url( $file_url ) {
		$matches = array();
		$ext = '';
		if ( preg_match( '/.*\.(\w+)$/i', $file_url, $matches ) ) {
			if ( isset( $matches[1] ) ) {
				$ext = $matches[1];
			}
		}
		
		return $ext;
	}

	public function is_must_convert2pdf( $file_url ) {
		$ext = $this->get_ext_from_url( $file_url );
		return in_array( $ext, self::$convert2pdf_ext );
	}

	public function replace_file_type_hints( $text ) {
		foreach ( self::$convert2pdf_ext as $ext ) {
			$text = preg_replace( '/\(' . $ext . '(\)|<)/', '(pdf$1', $text );
			$text = preg_replace( '/(\)|>)' . $ext . '\)/', '$1pdf)', $text );
		}
		return $text;
	}

	public function convert2pdf( $attachment_id, $localpdf = '' ) {
		$wp_upload_dir = wp_upload_dir();
		if ( $localpdf ) {
			$localpdf = $wp_upload_dir['basedir'] . '/localpdf';
		}
		
		$ret_attachment_id = $attachment_id;
		
		$original_file = get_attached_file( $attachment_id );
		$old_url = get_post_meta( $attachment_id, 'old_url', true );
		$old_parent_page_url = get_post_meta( $attachment_id, 'old_parent_page_url', true );
		
		$of_info = pathinfo( $original_file );
		
		$of_base_name = $of_info['basename'];
		$of_dir = $of_info['dirname'];
		$of_dir = str_replace( $wp_upload_dir['basedir'], '', $of_dir );
		
		$new_file_prefix = preg_replace( '/\/$/', '', $of_dir );
		$new_file_prefix = preg_replace( '/^\//', '', $new_file_prefix );
		$new_file_prefix = str_replace( '/', '_', $new_file_prefix );
		$new_file_prefix = str_replace( '\\', '_', $new_file_prefix );
		$new_file_prefix = $new_file_prefix . '_';
		$tmp_dir = get_temp_dir();
		
		$new_file_base_name_no_prefix = preg_replace( '/\.\w+$/', '.pdf', $of_base_name );
		$new_file_base_name = $new_file_prefix . $new_file_base_name_no_prefix;
		$new_file = $tmp_dir . $new_file_base_name;
		$new_file_no_prefix = $tmp_dir . $new_file_base_name_no_prefix;
		
		if ( $localpdf ) {
			$localpdf_file = preg_replace( '/\/$/', '', $localpdf ) . '/' . $new_file_base_name;
			if ( Knd_Filesystem::get_instance()->exists( $localpdf_file ) ) {
				Knd_Filesystem::get_instance()->copy( $localpdf_file, $new_file_no_prefix );
			}
		} else {
//			TST_Convert2PDF::get_instance()->doc2pdf( $original_file, $new_file );
		}
		
		if ( $localpdf && file_exists( $new_file_no_prefix ) ) {
			$new_attachment_id = $this->import_file_from_path( $new_file_no_prefix );
			
			if ( $new_attachment_id ) {
				$ret_attachment_id = $new_attachment_id;
				
				if ( $old_url ) {
					delete_post_meta( $attachment_id, 'old_url' );
					update_post_meta( $new_attachment_id, 'old_url', $old_url );
				}
				
				if ( $old_parent_page_url ) {
					delete_post_meta( $attachment_id, 'old_parent_page_url' );
					update_post_meta( $new_attachment_id, 'old_parent_page_url', $old_parent_page_url );
				}
				
				$attachment_terms = wp_get_post_terms( $attachment_id, 'attachment_tag' );
				foreach ( $attachment_terms as $tag ) {
					wp_set_object_terms( $new_attachment_id, $tag->term_id, 'attachment_tag' );
				}
				wp_delete_object_term_relationships( $attachment_id, 'attachment_tag' );
				unset( $attachment_terms );
			}
			
			unlink( $new_file_no_prefix );
		} elseif ( Knd_Filesystem::get_instance()->exists( $new_file ) ) {
			$this->copy_to_localpdf( $new_file, $new_file_base_name );
			unlink( $new_file );
		}
		
		return $ret_attachment_id;
	}

	public function copy_to_localpdf( $new_file, $new_file_base_name ) {
		$wp_upload_dir = wp_upload_dir();
		$pdf_dirname = $wp_upload_dir['basedir'] . '/localpdf';
		if ( ! file_exists( $pdf_dirname ) ) {
			wp_mkdir_p( $pdf_dirname );
		}
		
		$localpdf_file = $pdf_dirname . '/' . $new_file_base_name;
		if ( ! Knd_Filesystem::get_instance()->exists( $localpdf_file ) ) {
			Knd_Filesystem::get_instance()->copy( $new_file, $localpdf_file );
		}
	}

	public function remove_inline_styles( $content ) {
		$content = preg_replace( '/(style\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content );
		$content = preg_replace( '/(width\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content );
		$content = preg_replace( '/(height\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content );
		return $content;
	}

	public function remove_url_tag( $url, $content ) {
		$content = preg_replace( '/<\s*img[^>]+' . preg_quote( $url, '/' ) . '.*?>/is', '', $content );
		$content = preg_replace( '/<\s*a[^>]+' . preg_quote( $url, '/' ) . '.*?>[^<]*?<\s*\/\s*a\s*>/is', '', $content );
		return $content;
	}

	public function get_file_name( $url, $content ) {

		$matches = array();
		preg_match( '/<a[^>]*' . preg_quote( $url, '/' ) . '.*?>(.*?)<\/a>/i', $content, $matches );
		$title = isset( $matches[1] ) ? $matches[1] : '';
		$title = $this->clean_string( $title );
		
		return $title;
	}

	public function clean_string( $s ) {
		$res = preg_replace( '/\r\n/is', ' ', $s );
		$res = preg_replace( '/\n/is', ' ', $res );
		$res = preg_replace( '/\s+/is', ' ', $res );
		$res = preg_replace( '/\s+/isu', ' ', $res );
		$res = trim( $res );
		$res = strip_tags( $res );
		return $res;
	}

	public function get_date_from_url( $url, $parse_rules ) {
		$file_date = '';
		
		foreach ( $parse_rules as $k => $v ) {
			if ( preg_match( $v['regexp'], $url, $matches ) ) {
				if ( isset( $matches[1] ) ) {
					$date_str = $matches[1];
					$file_time = strptime( $date_str, $v['pattern'] );
					if ( $file_time ) {
						$month = $file_time['tm_mon'] + 1;
						$year = $file_time['tm_year'] + 1900;
						$file_date = sprintf( '%d-%02d-01', $year, $month );
					}
				}
				break;
			}
		}
		
		return $file_date;
	}

	public function get_exact_date_from_url( $url, $parse_rules ) {
		$file_date = '';
		
		foreach ( $parse_rules as $k => $v ) {
			if ( preg_match( $v['regexp'], $url, $matches ) ) {
				if ( isset( $matches[1] ) ) {
					$date_str = $matches[1];
					$file_time = strptime( $date_str, $v['pattern'] );
					if ( $file_time ) {
						$month = $file_time['tm_mon'] + 1;
						$year = $file_time['tm_year'] + 1900;
						$day = $file_time['tm_mday'];
						$file_date = sprintf( '%d-%02d-%02d', $year, $month, $day );
					}
				}
				break;
			}
		}
		
		return $file_date;
	}

	public function set_file_date( $file_id, $url, $tag_slug = '' ) {
		$date_parse_rules = array();
		if ( $tag_slug && isset( self::$date_from_url[$tag_slug] ) ) {
			$date_parse_rules[$tag_slug] = self::$date_from_url[$tag_slug];
		}
		
		if ( ! count( $date_parse_rules ) ) {
			$date_parse_rules = self::$date_from_url;
		}
		
		foreach ( $date_parse_rules as $tag_slug => $parse_rules ) {
			$file_date = TST_Import::get_instance()->get_date_from_url( $url, $parse_rules );
			if ( $file_date ) {
				update_post_meta( $file_id, 'file_date', $file_date );
				break;
			}
		}
	}

	function get_attachment_guid_by_url( $url ) {
		$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
		
		$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
		$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
		
		if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
			return;
		}
		
		return WP_CONTENT_URL . $parsed_url[1];
	}

	function get_attachment_id_by_url( $url ) {
		$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
		
		$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
		$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
		
		if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
			return;
		}
		global $wpdb;
		$attachment = $wpdb->get_col( 
			$wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE %s;", '%' . $parsed_url[1] ) );
		
		return count( $attachment ) ? $attachment[0] : 0;
	}

	public function clean_content_xpath( $content, $section ) {
		if ( ( ! isset( $section["clean_content_xpath"] ) || ! is_array( $section["clean_content_xpath"] ) ) &&
			 isset( $section['xpath']['title'] ) ) {
			$section["clean_content_xpath"] = array();
		}
		
		if ( isset( $section['xpath']['title'] ) ) {
			if ( ! is_array( $section['xpath']['title'] ) ) {
				$section["clean_content_xpath"][] = $section['xpath']['title'];
			}
		}
		
		if ( is_array( $section["clean_content_xpath"] ) ) {
			$dom = new DOMDocument( '1.0', 'UTF-8' );
			
			$dom->loadHTML( 
				'<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $content, 
				LIBXML_NOWARNING | LIBXML_NOERROR );
			
			$nodes2delete = array();
			$xpath = new DomXPath( $dom );
			
			foreach ( $section["clean_content_xpath"] as $v ) {
				if ( ! $v ) {
					continue;
				}
				$nodes = $xpath->query( $v );
				$node = $nodes ? $nodes->item( 0 ) : NULL;
				if ( $node ) {
					$nodes2delete[] = $node;
				}
			}
			
			foreach ( $nodes2delete as $element ) {
				try {
					if ( $element->parentNode ) {
						$element->parentNode->removeChild( $element );
					}
				} catch ( Exception $ex ) {
				}
			}
			
			$xpath = new DomXPath( $dom );
			$body = $xpath->query( './/body' );
			$body = $body ? $body->item( 0 ) : NULL;
			$content = $body ? $this->get_inner_html( $body ) : '';
			
			unset( $body );
			unset( $xpath );
			unset( $nodes2delete );
		}
		
		return $content;
	}

	public function clean_content_regexp( $content, $section ) {
		if ( is_array( $section["clean_content_regexp"] ) ) {
			foreach ( $section["clean_content_regexp"] as $regexp ) {
				if ( is_array( $regexp ) ) {
					$limit = $regexp['limit'];
					$regexp = $regexp['regexp'];
				} else {
					$limit = - 1;
				}
				$content = preg_replace( $regexp, "", $content, $limit );
			}
		}
		
		return $content;
	}

	public function get_inner_html( DOMNode $element ) {
		$innerHTML = "";
		$children = $element->childNodes;
		
		if ( $children ) {
			foreach ( $children as $child ) {
				$innerHTML .= $element->ownerDocument->saveHTML( $child );
			}
		}
		
		return $innerHTML;
	}

	function url2base( $url ) {
		$base_url = preg_replace( '/\/[^\/]*$/', '', $url );
		return $base_url;
	}

	function clean_content( $content, $section ) {
		$content = $this->remove_script( $content );
		$content = $this->clean_content_regexp( $content, $section );
		$content = $this->clean_content_xpath( $content, $section );
		
		return $content;
	}

	function urls_rel2abs( $content, $base_url, $dront_site_url ) {
		$content = preg_replace( 
			'/(src|href)\s*=\s*(["\'])\s*(\/(?!\/)[^\"\' ]+)/', 
			'\1=\2' . $dront_site_url . '\3', 
			$content );
		$content = preg_replace( 
			'/(src|href)\s*=\s*(["\'])\s*((?!https?:\/\/)[^\"\' ]+)/', 
			'\1=\2' . $base_url . '/\3', 
			$content );
		return $content;
	}

	function get_headers_from_curl_response( $header_text ) {
		$headers = array();
		
		foreach ( explode( "\r\n", $header_text ) as $i => $line ) {
			if ( $i === 0 ) {
				$headers['STATUS'] = $line;
				preg_match( "/HTT\w+\/\d+.\d+\s+(\d+)/", $line, $matches );
				$headers['STATUS_CODE'] = trim( $matches[1] );
			} else {
				if ( $line && strpos( $line, ':' ) ) {
					list( $key, $value ) = explode( ': ', $line );
					$headers[$key] = $value;
				}
			}
		}
		
		return $headers;
	}

	function remove_script( $html ) {
		$html = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $html );
		return $html;
	}

	public function maybe_import( $external_file_url ) {
		$exist_attachment = $this->get_attachment_by_old_url( $external_file_url );
		
		if ( $exist_attachment ) {
			$attachment_id = $exist_attachment->ID;
		} else {
			$attachment_id = $this->import_big_file( $external_file_url );
		}
		unset( $exist_attachment );
		
		return $attachment_id;
	}

	public function maybe_import_local_file( $filename ) {
		$exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $filename );
		$thumbnail_id = $exist_attachment ? $exist_attachment->ID : TST_Import::get_instance()->import_local_file( 
			$filename );
		return $thumbnail_id;
	}
} //class TST_Import