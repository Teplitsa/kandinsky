<?php

/**
 * Build WP content structures using imported data.
 * Usage:
 * $pdb = KND_Plot_Data_Builder::produce_builder($importer);
 * $pdb->build_all();
 *
 *
 */
class KND_Plot_Data_Builder {
	
	protected $imp = NULL;
	protected $parsedown = NULL;
	protected $data_routes = array();
	protected $cta_list = array();
	
	function __construct($imp) {
		$this->imp = $imp;
		$this->shortcode_builder = new KND_Shortcode_Builder($this, $this->imp);
	}
	
	/**
	 * Produce specific builder depends on importer.
	 *
	 * @param string    $imp    KND_Import_Remote_Content
	 * 
	 * @return KND_Plot_Data_Builder Extended class instance
	 */
	public static function produce_builder($imp) {
		return self::produce_plot_builder($imp->plot_name, $imp);
	}
	
	public static function produce_plot_builder($plot_name, $imp) {

		$plot_name_cap = preg_replace("/[-_]*/", "", ucfirst($plot_name));
		$class_name = "KND_{$plot_name_cap}_Data_Builder";
		if(class_exists($class_name)) {
			$builder = new $class_name($imp);
		} else {
			$builder = NULL;
		}

		return $builder;
	}
	
	/**
	 * Create WP content structures using imported data.
	 *
	 */
	public function build_all() {
		$this->build_posts();
		$this->build_pages();
		$this->build_leyka_capmaigns();
		$this->build_title_and_description();
		$this->build_theme_files();
		$this->build_option_files();
		$this->build_theme_colors();
		$this->build_theme_options();
		$this->build_general_options();
		$this->build_menus();
	}

	public function build_leyka_capmaigns() {
		
		if(!defined('LEYKA_VERSION')) {
			return;
		}
		
		$this->_install_leyka_settings();
		$this->_install_payment_methods();
		$this->_install_campaigns_with_donations();
		$this->_reset_default_pages();
	}

	public function _install_leyka_settings() {
		$org_name = get_option('blogname');
		$org_description = get_option('blogdescription');
		$org_address = esc_html__( '165150, Arkhangelsk region, Velsky district, Velsk, st. Rogozin, 48, office. 12', 'knd' );

		# NGO data
		$this->safe_update_option('leyka_org_full_name', "{$org_name} \"{$org_description}\"");
		$this->safe_update_option('leyka_org_face_fio_ip', esc_html__( 'Kotov Aristarkh Evgraphovich', 'knd' ) );
		$this->safe_update_option('leyka_org_face_fio_rp', esc_html__( 'Sobakin Yevlampy Mstislavovich', 'knd' ) );
		$this->safe_update_option('leyka_org_face_position', esc_html__( 'Director', 'knd' ) );
		$this->safe_update_option('leyka_org_address', $org_address);
		
		# reg and bank account
		$this->safe_update_option('leyka_org_state_reg_number', '1134567890123');
		$this->safe_update_option('leyka_org_kpp', '223456789');
		$this->safe_update_option('leyka_org_inn', '333456789012');
		$this->safe_update_option('leyka_org_bank_account', '44445678901234567890');
		$this->safe_update_option('leyka_org_bank_name', esc_html__( 'MEOW Zoobank', 'knd' ) );
		$this->safe_update_option('leyka_org_bank_bic', '555556789');
		$this->safe_update_option('leyka_org_bank_corr_account', '66666678901234567890');
		
		// View settings:
		$this->safe_update_option('leyka_donation_form_template', 'star');
		$this->safe_update_option('leyka_donation_sum_field_type', 'mixed');
		$this->safe_update_option('leyka_scale_widget_place', '-');
		$this->safe_update_option('leyka_donations_history_under_forms', 0);
		$this->safe_update_option('leyka_show_campaign_sharing', 0);
		
		// Misc settings:
		$this->safe_update_option('leyka_agree_to_terms_needed', 1);
		$this->safe_update_option('leyka_terms_agreed_by_default', 1);
		$this->safe_update_option('leyka_agree_to_terms_text_text_part', esc_html__('I accept', 'knd'));
		$this->safe_update_option('leyka_agree_to_terms_text_link_part', esc_html__('Terms of Service', 'knd'));
	}
	
	public function _install_payment_methods() {
		$available_pms = array(
			'yandex-yandex_money', 'mixplat-sms', 'quittance-bank_order', 'text-text_box'
		);
		$this->safe_update_option('leyka_pm_available', $available_pms);
		
		if( !get_option('leyka_pm_order') ) {
		
			$pm_order = array();
			foreach((array)get_option('leyka_pm_available') as $pm_full_id) {
				if($pm_full_id) {
					$pm_order[] = "pm_order[]={$pm_full_id}";
				}
			}
		
			$this->safe_update_option('leyka_pm_order', implode('&', $pm_order));
		
		}
		
	}

	public function _remove_all_section_campaigns_with_donations() {

		// foreach($this->imp->possible_plots as $plot_name) {

		// 	if ( $plot_name != $this->imp->plot_name) {

		// 		$builder = self::produce_plot_builder($plot_name, $this->imp);

		// 		if ( isset( $builder->data_routes ) ) {
		// 			$plot_config = $builder->data_routes;

		// 			foreach($plot_config['leyka_campaigns'] as $section => $section_data) {

		// 				$post_type = Leyka_Campaign_Management::$post_type;
		// 				$post_pieces_name = $section_data;

		// 				foreach($post_pieces_name as $piece_name) {

		// 					$piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
		// 					$slug = $piece->get_post_slug();

		// 					$campaign = knd_get_post($slug, $post_type);
		// 					if($campaign) {
		// 						$leyka_campaign = new Leyka_Campaign($campaign);
								
		// 						$donations = $leyka_campaign->get_donations();
		// 						foreach($donations as $donation) {
		// 							$donation->delete(True);
		// 						}
								
		// 						$leyka_campaign->delete(True);
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }
	}
	
	public function _install_campaigns_with_donations() {
		
		//$this->_remove_all_section_campaigns_with_donations();
		
		foreach(array_keys($this->data_routes['leyka_campaigns']) as $section) {
			$this->_install_section_campaigns_with_donations($section);
		}
		
		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );
		
	}
	
	public function _install_section_campaigns_with_donations($section) {
		global $wpdb;
		
		$pieces = $this->data_routes['leyka_campaigns'][$section];
		
		foreach($pieces as $piece_name) {
			
			$piece = $this->imp->get_piece($piece_name, $section);
			
			if($piece) {
				$piece->content = $this->imp->parse_text($piece->content);
				
				$campaign_data['name'] = $piece->get_post_slug();
				$campaign_data['title'] = $piece->title;
				$campaign_data['content'] = $piece->content;
				$campaign_data['target'] = $piece->target;
				$campaign_data['age'] = $piece->age;
				
				$campaign_post = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND post_name = %s", Leyka_Campaign_Management::$post_type, $campaign_data['name']));
				if($campaign_post) {
					$campaign_post = new WP_Post( $campaign_post );
					$campaign = new Leyka_Campaign($campaign_post);
				
					$this->_delete_campaign_donations($campaign);
					$campaign->delete(True);
				}
				
				$campaign_id = wp_insert_post(array(
					'post_type' => Leyka_Campaign_Management::$post_type,
					'post_status' => 'publish',
					'post_title' => $campaign_data['title'],
					'post_name' => $campaign_data['name'],
					'post_content' => $campaign_data['content'],
					'post_parent' => 0,
				));
				
				update_post_meta($campaign_id, 'campaign_target', $campaign_data['target']);
				update_post_meta($campaign_id, 'campaign_age', $campaign_data['age']);
				update_post_meta($campaign_id, 'campaign_template', 'star');
				$campaign = new Leyka_Campaign($campaign_id);
				
				$this->_install_campaign_donations($campaign);
				$campaign->refresh_target_state();
				
				// add tags
				if(count($piece->tags_list)) {
					$taxonomy = 'post_tag';
					$terms_list = $this->get_terms_list($piece->tags_list, $taxonomy);
				
					if($terms_list) {
						wp_set_object_terms((int)$campaign_id, $terms_list, $taxonomy, false);
					}
				}
				
				//finished campaign
				if(preg_match("/^closed.*/", $piece_name)) {
					update_post_meta($campaign_id, 'is_finished', 1);
				}
				
				# add thumbnail
				$thumb_id = $this->imp->get_thumb_attachment_id($piece);
				if($thumb_id) {
					update_post_meta($campaign->ID, '_thumbnail_id', (int)$thumb_id);
				}
			}
		}
		
		wp_cache_flush();
	}
	
	public function _install_campaign_donations($campaign) {
		$donations_data = array(
			array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
			array('gateway_id' => 'mixplat', 'payment_method_id' => 'sms', 'donor_name' => esc_html__( 'Korovin Ostap Rudolfovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 30.0),
			array('gateway_id' => 'quittance', 'payment_method_id' => 'bank_order', 'donor_name' => esc_html__( 'Bykov Ivan Ivanovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 420.0),
			array('gateway_id' => 'text', 'payment_method_id' => 'text_box', 'donor_name' => esc_html__( 'Moose Veniamin Robertovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 210.0),
		);
		
		if($campaign->post_name == 'heal-kid') {
			$add_donations_data = array(
				array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
				array('gateway_id' => 'mixplat', 'payment_method_id' => 'sms', 'donor_name' => esc_html__( 'Korovin Ostap Rudolfovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 30.0),
				array('gateway_id' => 'quittance', 'payment_method_id' => 'bank_order', 'donor_name' => esc_html__( 'Bykov Ivan Ivanovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 420.0),
				array('gateway_id' => 'text', 'payment_method_id' => 'text_box', 'donor_name' => esc_html__( 'Moose Veniamin Robertovich','knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 210.0),
				array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
				array('gateway_id' => 'mixplat', 'payment_method_id' => 'sms', 'donor_name' => esc_html__( 'Korovin Ostap Rudolfovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 30.0),
				array('gateway_id' => 'quittance', 'payment_method_id' => 'bank_order', 'donor_name' => esc_html__( 'Bykov Ivan Ivanovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 420.0),
				array('gateway_id' => 'text', 'payment_method_id' => 'text_box', 'donor_name' => esc_html__( 'Moose Veniamin Robertovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 210.0),
				array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
				array('gateway_id' => 'mixplat', 'payment_method_id' => 'sms', 'donor_name' => esc_html__( 'Korovin Ostap Rudolfovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 30.0),
				array('gateway_id' => 'quittance', 'payment_method_id' => 'bank_order', 'donor_name' => esc_html__( 'Bykov Ivan Ivanovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 420.0),
				array('gateway_id' => 'text', 'payment_method_id' => 'text_box', 'donor_name' => esc_html__( 'Moose Veniamin Robertovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 210.0),
				array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
				array('gateway_id' => 'mixplat', 'payment_method_id' => 'sms', 'donor_name' => esc_html__( 'Korovin Ostap Rudolfovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 30.0),
				array('gateway_id' => 'quittance', 'payment_method_id' => 'bank_order', 'donor_name' => esc_html__( 'Bykov Ivan Ivanovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 420.0),
				array('gateway_id' => 'text', 'payment_method_id' => 'text_box', 'donor_name' => esc_html__( 'Moose Veniamin Robertovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 210.0),
				array('gateway_id' => 'yandex', 'payment_method_id' => 'yandex_money', 'donor_name' => esc_html__( 'Martynov Semyon Semyonovich', 'knd' ), 'donor_email' => 'test@ngo2.ru', 'amount' => 150.0),
			);
			$donations_data = array_merge($donations_data, $add_donations_data);
		}

		foreach($donations_data as $donation_data) {
			$donation_id = Leyka_Donation::add(array(
				'gateway_id' => $donation_data['gateway_id'],
				'payment_method_id' => $donation_data['payment_method_id'],
				'campaign_id' => $campaign->ID,
				'purpose_text' => $campaign->title,
				'status' => 'funded',
				'payment_type' => 'single',
				'amount' => $donation_data['amount'],
				'currency' => 'rur',
				'donor_name' => $donation_data['donor_name'],
				'donor_email' => $donation_data['donor_email'],
			));
		
			$donation = new Leyka_Donation($donation_id);
			$campaign->update_total_funded_amount($donation);
		}
	}
	
	public function _delete_campaign_donations($campaign) {
		$donations = $campaign->get_donations();
		foreach($donations as $donation) {
			$donation->delete(True);
		}
	}
	
	public function _reset_default_pages() {
		leyka_get_default_success_page();
		leyka_get_default_failure_page();
	}
	
	/**
	 * Create WP posts, according to builder config, using imported files as content.
	 *
	 */
	public function build_posts() {

		//$this->remove_all_other_plots_posts();

		foreach(array_keys($this->data_routes['posts']) as $section) {
			$this->build_section_posts($section);
		}
		
		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );
	}

	public function remove_all_other_plots_posts() {
		// foreach($this->imp->possible_plots as $plot_name) {
			
		// 	if( $plot_name != $this->imp->plot_name ) {

		// 		$builder = self::produce_plot_builder( $plot_name, $this->imp );

		// 		if ( isset( $builder->data_routes ) ) {
		// 			$plot_config = $builder->data_routes;

		// 			foreach($plot_config['posts'] as $section => $section_data) {
						
		// 				$post_type = isset($section_data['post_type']) ? $section_data['post_type'] : 'post';
		// 				$post_pieces_name = $section_data['pieces'];
						
		// 				foreach($post_pieces_name as $piece_name) {
							
		// 					$piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
		// 					$slug = $piece->get_post_slug();
							
		// 					$post = knd_get_post($slug, $post_type);
		// 					if($post) {
		// 						$this->safe_delete_post( $post );
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }
	}
	
	public function remove_all_content() {
		// global $wpdb;
		// $sql = "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'knd_val_hash_%'";
		// $wpdb->query($sql);
		
		// foreach($this->imp->possible_plots as $plot_name) {
		// 		$builder = self::produce_plot_builder($plot_name, $this->imp);
		// 		$plot_config = $builder->data_routes;
		
		// 		foreach($plot_config['posts'] as $section => $section_data) {
		
		// 			$post_type = isset($section_data['post_type']) ? $section_data['post_type'] : 'post';
		// 			$post_pieces_name = $section_data['pieces'];
		
		// 			foreach($post_pieces_name as $piece_name) {
		
		// 				$piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
		// 				$slug = $piece->get_post_slug();
		
		// 				$post = knd_get_post($slug, $post_type);
		// 				if($post) {
		// 					wp_delete_post( $post->ID, true );
		// 				}
		// 			}
		// 		}
				
		// 		foreach($plot_config['pages'] as $section => $section_data) {
				
		// 			$post_type = isset($section_data['post_type']) ? $section_data['post_type'] : 'page';
		// 			if(isset($section_data['piece'])) {
		// 				$slug = $section_data['post_slug'];
		// 				$post = knd_get_post($slug, $post_type);
		// 				if($post) {
		// 					wp_delete_post( $post->ID, true );
		// 				}
		// 			}
		// 			elseif(isset($section_data['pieces'])) {
		// 				$post_pieces_name = $section_data['pieces'];
						
		// 				foreach($post_pieces_name as $piece_name) {
						
		// 					$piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
		// 					$slug = $piece->get_post_slug();
						
		// 					$post = knd_get_post($slug, $post_type);
		// 					if($post) {
		// 						wp_delete_post( $post->ID, true );
		// 					}
		// 				}
		// 			}
		// 		}
				
		// 		foreach($plot_config['pages_templates'] as $section => $section_data) {
				
		// 			$post_type = isset($section_data['post_type']) ? $section_data['post_type'] : 'page';
		// 			$slug = $section_data['post_slug'];
		// 			$post = knd_get_post($slug, $post_type);
		// 			if($post) {
		// 				wp_delete_post( $post->ID, true );
		// 			}
		// 		}
				
		// 		if(defined('LEYKA_VERSION')) {
		// 			foreach($plot_config['leyka_campaigns'] as $section => $section_data) {
					
		// 				$post_type = Leyka_Campaign_Management::$post_type;
		// 				$post_pieces_name = $section_data;
					
		// 				foreach($post_pieces_name as $piece_name) {
					
		// 					$piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
		// 					$slug = $piece->get_post_slug();
					
		// 					$campaign = knd_get_post($slug, $post_type);
		// 					if($campaign) {
		// 						$leyka_campaign = new Leyka_Campaign($campaign);
					
		// 						$donations = $leyka_campaign->get_donations();
		// 						foreach($donations as $donation) {
		// 							$donation->delete(true);
		// 						}
					
		// 						$leyka_campaign->delete(true);
		// 					}
		// 				}
		// 			}
		// 		}

		// 		$this->remove_options($plot_config);
		// 		$this->remove_menus($plot_config);
		// 		$this->remove_sidebars($plot_config);

		// }
	}
	
	public function remove_sidebars($plot_config) {
		//...
	}
	
	public function remove_menus($plot_config) {

		// foreach(array_keys($plot_config['menus']) as $key) {
		// 	$menu_object = wp_get_nav_menu_object( $key );
		// 	if($menu_object) {
		// 		wp_delete_term( $menu_object->term_id, 'nav_menu' );
		// 	}
		// }

	}
	
	public function remove_options($plot_config) {

		// foreach(array_keys($plot_config['theme_options']) as $key) {
		// 	remove_theme_mod( $key );
		// }

		// foreach(array_keys($plot_config['theme_colors']) as $key) {
		// 	remove_theme_mod( $key );
		// }

		// foreach(array_keys($plot_config['general_options']) as $key) {
		// 	delete_option( $key );
		// }

	}
	
	/**
	 * Create WP posts, according to builder config, using imported files as templates.
	 *
	 */
	public function build_pages() {

		$homepage_content = '';
		$replace_images   = '';

		foreach($this->data_routes['pages'] as $section => $page_options) {

			if(isset($page_options['pieces'])) {
				$this->build_section_pages($section);

				foreach( $page_options['pieces'] as $piece_name ) {
					if ( 'home' === $piece_name ) {
						$piece = $this->imp->get_piece($piece_name, $section);
						if($piece) {
							$homepage_content = $this->imp->parse_text($piece->content);
							$replace_images = $piece->replace_images;
						}
					}
				}

			} else {
				$this->build_section_simple_page($page_options);
			}

		}
		
		foreach($this->data_routes['pages_templates'] as $section => $page_options) {
			if(isset($page_options['template'])) {
				$this->build_section_template_page($section, $page_options);
			}
		}

		$homepage_content = trim( $homepage_content );

		// Find and replace image in demo content.
		if ( $replace_images ) {
			$find_images = array();
			$replace_urls = array();
			$replace_images = explode( ',', $replace_images );
			foreach ( $replace_images as $key => $image_slug ) {

				$attachment = knd_get_post_by_title( $image_slug, 'attachment' );
				if ( $attachment ) {
					$image_url = wp_get_attachment_image_url( $attachment->ID, 'large' );
					$key = $key+1;
					$find_images[] = '%img' . $key;
					$replace_urls[] = $image_url;
				}

			}

			$homepage_content = str_replace( $find_images, $replace_urls, $homepage_content );
		}

		$homepage_content = str_replace( '{{home_url}}', home_url(), $homepage_content );

		// set home page
		$piece = new KND_Piece( array(
			'slug'    => 'home',
			'title'   => __('Home page', 'knd'),
			'content' => $homepage_content,
			'metas'   => array(
				'_knd_is_page_title' => true,
			),
		) );
		$homepage_id = $this->safe_save_post($piece, 'page');
		$this->safe_update_option( 'page_on_front', $homepage_id );
		$this->safe_update_option( 'show_on_front', 'page' );
		
		// set news page
		$piece = new KND_Piece(array('slug' => 'news', 'title' => __('News', 'knd')));
		$homepage_id = $this->safe_save_post($piece, 'page');
		$this->safe_update_option( 'page_for_posts', $homepage_id );

		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );
	}
	
	/**
	 * Create WP posts, according to section config, using imported files as content.
	 *
	 */
	public function build_section_posts($section) {
		
		$post_type = $this->data_routes['posts'][$section]['post_type'];
		$pieces = $this->data_routes['posts'][$section]['pieces'];
		
		if(preg_match('/^root_.*/', $section)) {
			$section = '';
		}
		
		foreach($pieces as $piece_name) {
			$piece = $this->imp->get_piece($piece_name, $section);
			if($piece) {
				$piece->content = $this->imp->parse_text($piece->content);
				$this->safe_save_post($piece, $post_type);
			}
		}
	}
	
	public function build_section_pages($section) {
	
		$post_type = $this->data_routes['pages'][$section]['post_type'];
		$pieces = $this->data_routes['pages'][$section]['pieces'];
		
		if(preg_match('/^root_.*/', $section)) {
			$section = '';
		}
	
		foreach($pieces as $piece_name) {
			$piece = $this->imp->get_piece($piece_name, $section);
			if($piece) {
				$piece->content = $this->imp->parse_text($piece->content);
				$this->safe_save_post($piece, $post_type);
			}
		}
	}
	
	public function build_section_simple_page($page_options) {
	
		$post_type = isset($page_options['post_type']) ? $page_options['post_type'] : 'page';
		$post_slug = isset($page_options['post_slug']) ? $page_options['post_slug'] : '';
		$piece_name = isset($page_options['piece']) ? $page_options['piece'] : '';
		$section = isset($page_options['section']) ? $page_options['section'] : '';
	
		if(!$piece_name) {
			return;
		}
		
		if(preg_match('/^root_.*/', $section)) {
			$section = '';
		}
	
		$piece = $this->imp->get_piece($piece_name, $section);
		
		if($piece) {
			$piece->content = $this->imp->parse_text($piece->content);
			$piece->slug = $post_slug;
			$this->safe_save_post($piece, $post_type);
		}
		
	}
	
	/**
	 * Create WP posts, according to section config, using imported files as templates.
	 *
	 */
	public function build_section_template_page($section, $page_options) {
	
		$post_type = isset($page_options['post_type']) ? $page_options['post_type'] : 'page';
		$post_slug = isset($page_options['post_slug']) ? $page_options['post_slug'] : '';
		$template = isset($page_options['template']) ? $page_options['template'] : '';
	
		if(!$template) {
			return;
		}
		
		if(preg_match('/^root_.*/', $section)) {
			$section = '';
		}
		
		$template_piece = $this->imp->get_piece($template, $section);
		
		$template_piece->content = $this->fill_template_with_pieces( $template_piece->content, $section );
		$template_piece->slug = $post_slug;
		
		$this->safe_save_post($template_piece, $post_type);
	}
	
	/**
	 * Fill template with data from importer.
	 *
	 * @param string    $template_content    template file name
	 * @param string    $section             section, to search content in
	 * 
	 * @return string   template, where all tags replaces with proper content
	 */
	public function fill_template_with_pieces($template_content, $section) { // remove $post param, if useless
		$template_content = $this->fill_content_tags($template_content, $section);
		$template_content = $this->fill_shortcode_tags($template_content, $section);
		
		return $template_content;
	}
	
	/**
	 * Replace content tags with proper content.
	 *
	 * @param string    $template_content    template file name
	 * @param string    $section             section, to search content in
	 * 
	 * @return string   template, where content tags replaces with proper content
	 */
	public function fill_content_tags($template_content, $section) {
		
		preg_match_all("/\[\s*?content\s*(.*?)\]/", $template_content, $matches);
		
		foreach($matches[0] as $i => $tag) {
		
			$attributes_str = $matches[1][$i];
			$attributes = $this->parse_attributes($attributes_str);
		
			if(isset($attributes['name'])) {
				$piece_name = $attributes['name'];
				$piece = $this->imp->get_piece($piece_name, $section);
				
				if($piece) {
					$piece->content = $this->imp->parse_text($piece->content);
					$content = $piece->content;
					$template_content = str_replace($tag, $content, $template_content);
				}
			}
		
		}
		
		return $template_content;
	}
	
	/**
	 * Replace shortcodes tags with proper content.
	 *
	 * @param string    $template_content    template file name
	 * @param string    $section             section, to search content in
	 * 
	 * @return string   template, where shortcodes tags replaces with proper shortcodes
	 */
	public function fill_shortcode_tags($template_content, $section) {
		
		preg_match_all("/\[\s*?shortcode\s*(.*?)\]/", $template_content, $matches);
		
		foreach($matches[0] as $i => $tag) {
		
			$attributes_str = $matches[1][$i];
			$attributes = $this->parse_attributes($attributes_str);
		
			$shortcode_name = isset($attributes['name']) ? $attributes['name'] : '';
			
			if($shortcode_name) {
			 
				if(isset($attributes['content'])) {
					
					if(is_array($attributes['content'])) {
						$pieces = array();
						foreach($attributes['content'] as $piece_name) {
							$pieces[] = $this->imp->get_piece($piece_name, $section);
						}
					}
					else {
						$piece_name = $attributes['content'];
						$pieces = array($this->imp->get_piece($piece_name, $section));
					}
					
					unset($attributes['content']);
					
				}
				else {
					$pieces = array();
				}
				
				$build_method_name = "build_{$attributes['name']}";
				
				if($build_method_name && method_exists($this->shortcode_builder, $build_method_name)) {
					unset($attributes['name']);
					$result_shorcode = $this->shortcode_builder->$build_method_name($shortcode_name, $pieces, $attributes);
					$template_content = str_replace($tag, $result_shorcode, $template_content);
				}
			}
		
		}
		
		return $template_content;
	}
	
	/**
	 * Parse template tag attributes.
	 *
	 * @param string    $attributes_str    attributes string
	 * 
	 * @return string   array with key - value attributes
	 */
	public static function parse_attributes($attributes_str) {
		preg_match_all( "/(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[\"']))+.)[\"']?/", $attributes_str, $matches);
		
		$attrs = array();
		foreach($matches[1] as $i => $attr_name) {
			$attr_val = $matches[2][$i];
			
			if(isset($attrs[$attr_name])) {
				
				if(!is_array($attrs[$attr_name])) {
					$attrs[$attr_name] = array($attrs[$attr_name]);
				}
				
				$attrs[$attr_name][] = $attr_val;
				
			}
			else {
				$attrs[$attr_name] = $attr_val;
			}
		}
		
		return $attrs;
	}
	
	/**
	 * Save WP post using imported piece as data source.
	 *
	 * @param KND_Piece       $piece      imported piece
	 * @param string          $post_type  WP post type
	 * 
	 * @return int|WP_Error   WP post ID or error
	 */
	public function save_post($piece, $post_type) {
		
		$post_title = trim( $piece->title );
		$post_name = $piece->get_post_slug();
		if(!$post_name) {
			$post_name = sanitize_title($post_title);
		}
		$exist_page = knd_get_post( $post_name, $post_type );
		
		if($exist_page) {
			if(!$this->validate_post_hash($exist_page) && !get_option( 'knd_update_may_remove_my_content', false )) {
				return;
			}
		}
		
		$post_content = trim( $piece->content );

		// Find and replace image in demo content.
		if ( isset( $piece->replace_images ) ) {
			$find_images = array();
			$replace_urls = array();
			$replace_images = explode( ',', $piece->replace_images );
			foreach ( $replace_images as $key => $image_slug ) {

				$attachment = knd_get_post_by_title( $image_slug, 'attachment' );
				if ( $attachment ) {
					$image_url = wp_get_attachment_image_url( $attachment->ID, 'large' );
					$key = $key+1;
					$find_images[] = '%img' . $key;
					$replace_urls[] = $image_url;
				}

			}

			$post_content = str_replace( $find_images, $replace_urls, $post_content );
		}

		$post_content = str_replace( '{{home_url}}', home_url(), $post_content );

		$page_data = array();

		$page_data['ID'] = $exist_page ? $exist_page->ID : 0;
		$page_data['post_type'] = $post_type;
		$page_data['post_status'] = 'publish';
		$page_data['post_excerpt'] = empty($piece->lead) ? '' : trim($piece->lead);
		
		$page_data['post_title'] = $post_title;
		$page_data['post_name'] = $post_name;
		$page_data['menu_order'] = 0;
		$page_data['post_content'] = $post_content;
		$page_data['post_parent'] = 0;
		
		//thumbnail
		$thumb_id = $this->imp->get_thumb_attachment_id($piece);
		
		if($thumb_id){
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		if( isset( $piece->metas ) && is_array( $piece->metas ) ) {
			foreach( $piece->metas as $meta_name => $meta_value ) {
				$page_data['meta_input'][ $meta_name ] = $meta_value;
			}
		}

		$uid = wp_insert_post($page_data);

		// add to tax
		if(count($piece->tags_list)) {
			$taxonomy = 'post_tag';
			$terms_list = $this->get_terms_list($piece->tags_list, $taxonomy);
			
			if($terms_list) {
				wp_set_object_terms((int)$uid, $terms_list, $taxonomy, false);
				wp_cache_flush();
			}
		}

		if(count($piece->cats_list)) {
			$taxonomy = ($post_type == 'person') ? 'person_cat' : 'category';
			$terms_list = $this->get_terms_list($piece->cats_list, $taxonomy);

			if($terms_list) {
				wp_set_object_terms((int)$uid, $terms_list, $taxonomy, false);
				wp_cache_flush();
			}
		}
		
		$result_post = get_post($uid);
		if($result_post) {
			$this->save_post_hash($result_post);
		}
		
		return $uid;
	}
	
	/**
	 * Get taxonomy terms by names list.
	 *
	 * @param array      $terms_names  terms names
	 * @param string     $taxonomy     taxonomy name
	 *
	 * @return array     WP terms_id list
	 */
	public function get_terms_list($terms_names, $taxonomy) {
		$terms_list = array();
		
		if(!empty($terms_names) && is_array($terms_names)) {
			foreach($terms_names as $term_name) {
			
				$term = get_term_by( 'name', $term_name, $taxonomy );
				if($term) {
					$terms_list[] = $term->term_id;
				}
				else {
					$res = wp_insert_term( $term_name, $taxonomy );
					if(!is_wp_error($res)) {
						$terms_list[] = $res['term_id'];
					}
				}
			
			}
		}
		
		return $terms_list;
	}
	
	/**
	 * Import files from imported data.
	 *
	 */
	public function build_theme_files() {
		
		foreach($this->data_routes['theme_files'] as $option_name => $option) {
			
			$file = $option['file'];
			$section = isset($option['section']) ? $option['section'] : '';
			$logo_fdata = $this->imp->get_fdata($file, $section);
			
			if($logo_fdata && isset($logo_fdata['attachment_id']) && $logo_fdata['attachment_id']) {
				$this->safe_set_theme_mod($option_name, $logo_fdata['attachment_id']);
			}
			
		}
		
	}
	
	public function build_option_files() {
		
		foreach($this->data_routes['option_files'] as $option_name => $option) {
		
			$file = $option['file'];
			$section = isset($option['section']) ? $option['section'] : '';
			$logo_fdata = $this->imp->get_fdata($file, $section);
		
			if($logo_fdata && isset($logo_fdata['attachment_id']) && $logo_fdata['attachment_id']) {
				$this->safe_update_option($option_name, $logo_fdata['attachment_id']);
			}
		
		}
		
	}
	
	/**
	 * Get call to action URL depends on builder config.
	 *
	 * @param string     $cta_key     CTA key, extracted from tempalate
	 *
	 * @return string    CTA URL
	 */
	public function get_cta_url($cta_key) {
		return isset($this->cta_list[$cta_key]) ? $this->cta_list[$cta_key] : '';
	}

	public function build_theme_options() {
		$this->save_theme_options($this->data_routes['theme_options']);
		do_action('knd_plotdata_build_theme_options');
	}
	
	
	public function build_theme_colors() {
	
		$this->save_theme_options($this->data_routes['theme_colors']);
	
	}
	
	public function save_theme_options($theme_options_list) {
		
		foreach($theme_options_list as $theme_option_name => $theme_option_piece_data) {
	
			if(is_array($theme_option_piece_data)) {
	
				$piece = $theme_option_piece_data['piece'];
				$field = isset($theme_option_piece_data['field']) ? $theme_option_piece_data['field'] : 'content';
				$section = isset($theme_option_piece_data['section']) ? $theme_option_piece_data['section'] : '';
				$this->safe_set_theme_mod($theme_option_name, $this->imp->get_val($piece, $field, $section));
			}
			else {
				$this->safe_set_theme_mod($theme_option_name, $theme_option_piece_data);
			}
		}

	}

	public function build_general_options() {
		//save permastructure
		$test = get_option('permalink_structure');
		if(empty($test)){
			update_option('permalink_structure', '/%year%/%monthnum%/%postname%/');
			flush_rewrite_rules();
		}
	}

	public function build_footer_sidebar() {
		// footer contacts
	}
	
	public function build_menus() {
		
		if(!isset($this->data_routes['menus']) || !is_array($this->data_routes['menus'])) {
			return;
		}

		foreach($this->data_routes['menus'] as $menu ) {

			if ( is_nav_menu( $menu['name'] ) ){
				wp_delete_nav_menu( $menu['name'] );
			}
			$menu_id = wp_create_nav_menu( $menu['name'] );

			if ( $menu_id ) {
				$args = array(
					'slug' => $menu['slug'],
				);
				wp_update_term( $menu_id, 'nav_menu', $args );
			}

			foreach($menu['items'] as $k => $v) {
				if(is_array($v)) {
					if(isset($v['post_type']) && isset($v['slug'])) {
						$page = knd_get_post( $v['slug'], $v['post_type'] );
						if($page) {
							KND_StarterMenus::add_post2menu($page, $menu_id, $k);
						}
					}
					elseif(isset($v['url']) && isset($v['title'])) {
						KND_StarterMenus::add_link2menu($v['title'], $v['url'], $menu_id, $k);
					}
				}
			}
		}

		if ( term_exists( esc_html__( 'Primary Menu', 'knd' ), 'nav_menu' ) ) {
			$nav_menu_locations = array();
			$menu = get_term_by( 'name', esc_html__( 'Primary Menu', 'knd' ), 'nav_menu' );
			if ( ! empty( $menu ) && ! is_wp_error( $menu ) ) {
				$menu_id                       = $menu->term_id;
				$nav_menu_locations['primary'] = $menu_id;
			}
			if ( $nav_menu_locations ) {
				set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
			}
		}

		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );
	}
	
	public function build_title_and_description() {
		//...
	}

	public function save_hash($name, $val) {
		update_option('knd_val_hash_' . $name, md5(maybe_serialize($val)));
	}

	public function validate_hash($name, $val) {
		$hash = get_option('knd_val_hash_' . $name);
		return !$hash || $hash == md5(maybe_serialize($val));
	}

	public function validate_post_hash($post) {
		$title_hash = get_option('knd_val_hash_' . $post->post_name . '_title');
		$content_hash = get_option('knd_val_hash_' . $post->post_name . '_content');
		$excerpt_hash = get_option('knd_val_hash_' . $post->post_name . '_excerpt');
		
		return !($title_hash || $content_hash || $excerpt_hash) || ($title_hash == md5($post->post_title) && $content_hash == md5($post->post_content) && $excerpt_hash == md5($post->post_excerpt));
	}

	public function save_post_hash($post) {
		if(!$post) {
			return;
		}
		update_option('knd_val_hash_' . $post->post_name . '_title', md5($post->post_title));
		update_option('knd_val_hash_' . $post->post_name . '_content', md5($post->post_content));
		update_option('knd_val_hash_' . $post->post_name . '_excerpt', md5($post->post_excerpt));
	}

	public function safe_update_option($key, $value) {
		if($this->validate_hash($key, get_option($key)) || get_option( 'knd_update_may_remove_my_content', false )) {
			update_option($key, $value);
			$this->save_hash($key, $value);
		}
	}
	
	public function safe_set_theme_mod($key, $value) {
		if($this->validate_hash($key, knd_get_theme_mod($key)) || knd_get_theme_mod( 'knd_update_may_remove_my_content', false )) {
			set_theme_mod($key, $value);
			$this->save_hash($key, $value);
		}
	}
	
	public function safe_delete_post($post) {
		// if($this->validate_post_hash($post) || get_option( 'knd_update_may_remove_my_content', false )) {
		// 	wp_delete_post( $post->ID, true );
		// }
	}

	public function safe_save_post($piece, $post_type) {
		return $this->save_post($piece, $post_type);
	}
}
