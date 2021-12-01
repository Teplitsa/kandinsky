<?php

if ( ! defined( 'WPINC' ) )
	die();

function knd_get_post( $post_id, $post_type = 'post' ) {
	global $wpdb;
	$post = null;
	if ( preg_match( '/^\d+$/', $post_id ) ) {
		$post = get_post( $post_id, OBJECT );
	} else {
		$post_id = $wpdb->get_var( 
			$wpdb->prepare( 
				"SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s LIMIT 1 ", 
				$post_id, 
				$post_type ) );
		if ( $post_id ) {
			$post = get_post( $post_id, OBJECT );
		}
	}
	return $post;
}

function knd_clean_csv_slug( $slug ) {
	return preg_replace( '/\s+/', '', trim( $slug ) );
}

function knd_get_temp_dir() {
	return ini_get( 'upload_tmp_dir' ) ? ini_get( 'upload_tmp_dir' ) : sys_get_temp_dir();
}

function knd_import_posts_from_csv( $input_file, $post_type, $taxonomy = '', $post_meta = array() ) {
	if ( ( $handle = fopen( $input_file, "r" ) ) !== FALSE ) {
		$i = 0;
		
		while ( ( $line = fgetcsv( $handle, 1000000, "," ) ) !== FALSE ) {
			
			$i += 1;
			
			if ( $i == 1 ) {
				continue;
			}
			
			$post_title = trim( $line[0] );
			$post_name = knd_clean_csv_slug( trim( $line[2] ) );
			if ( ! $post_name ) {
				$post_name = sanitize_title( $post_title );
			}
			$exist_page = knd_get_post( $post_name, $post_type );
			
			$page_data = array();
			
			$page_data['ID'] = $exist_page ? $exist_page->ID : 0;
			$page_data['post_type'] = $post_type;
			$page_data['post_status'] = 'publish';
			$page_data['post_excerpt'] = empty( $line[7] ) ? '' : trim( $line[7] );
			
			$page_data['post_title'] = $post_title;
			$page_data['post_name'] = $post_name;
			$page_data['menu_order'] = (int) $line[5];
			$page_data['post_content'] = trim( $line[1] );
			$page_data['post_parent'] = 0;
			
			foreach ( $post_meta as $meta_name => $value_index ) {
				$page_data['meta_input'][$meta_name] = empty( $line[$value_index] ) ? '' : trim( $line[$value_index] );
			}
			
			// thumbnail
			$thumb_id = false;
			$thumbnail_url = trim( $line[4] );
			if ( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
				$thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
			}
			
			if ( $thumb_id ) {
				$page_data['meta_input']['_thumbnail_id'] = (int) $thumb_id;
			}
			
			$uid = wp_insert_post( $page_data );
			
			// add to tax
			if ( $taxonomy ) {
				$term_slug = knd_clean_csv_slug( trim( $line[6] ) );
				if ( ! empty( $line[6] ) && $line[6] != 'none' ) {
					wp_set_object_terms( (int) $uid, $term_slug, $taxonomy, false );
					wp_cache_flush();
				}
			}
			
			unset( $line );
		}
	}
}

function knd_build_imported_url( $url ) {
	$res_url = home_url();
	
	if ( $url ) {
		if ( preg_match( "/^\/.*/", $url ) || preg_match( "/^http[s]?:\/\/.*/", $url ) ) {
			$res_url = home_url( $url );
		} elseif ( preg_match( "/^([-0-9a-z_]+)\/(.+)$/", $url, $matches ) ) {
			$post_type = $matches[1];
			$post_slug = $matches[2];
			
			$post = knd_get_post( $post_slug, $post_type );
			if ( $post ) {
				$res_url = get_permalink( $post );
			}
		}
	}
	
	return $res_url;
}
