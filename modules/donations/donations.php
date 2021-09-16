<?php

if(!class_exists('Leyka_Payment_Method'))
	return;

get_template_part('/modules/donations/widgets');

/** Custom donation functions */

add_filter('leyka_icons_text_text_box', 'knd_text_pm_icon');
function knd_text_pm_icon($icons){
	//size 155x80 px
	
	$icons = array(get_template_directory_uri().'/assets/images/text-box.png');
		
	return $icons;
}

//no icon for text gateway
add_filter('leyka_icons_text_text_box', 'knd_empty_icons');	
function knd_empty_icons(){
	return array();
}

function knd_activate_leyka() {
	$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
	$imp->import_downloaded_content();
	
	$pdb = KND_Plot_Data_Builder::produce_builder($imp);
	$pdb->build_leyka_capmaigns();
}
register_activation_hook( 'leyka/leyka.php', 'knd_activate_leyka' );

/** Form template **/
//custom amount field
function knd_amount_field($form){
	
	if(!defined('LEYKA_VERSION'))
		return;
	
	$supported_curr = leyka_get_active_currencies();
	$current_curr = $form->get_current_currency();

	if(empty($supported_curr[$current_curr])) {
		return; // Current currency isn't supported
	}
?>

<div class="leyka-field amount-selector amount mixed">
	
<div class="currency-selector-row" >
	<div class="currency-variants">
<?php
	foreach($supported_curr as $currency => $data) {

	$variants = explode(',', $data['amount_settings']['fixed']);?>
		<div class="<?php echo $currency;?> amount-variants-container" <?php echo $currency == $current_curr ? '' : 'style="display:none;"';?> >
			<div class="amount-variants-row">
				<?php foreach($variants as $i => $amount) { ?>
					<label class="figure rdc-radio" title="<?php esc_attr_e( 'Please, specify your donation amount', 'knd' );?>">
						<input type="radio" value="<?php echo (int)$amount;?>" name="leyka_donation_amount" class="rdc-radio__button" <?php checked($i, 0);?> <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?>>
						<span class="rdc-radio__label"><?php echo (int)$amount;?></span>
					</label>
				<?php } ?>
				
				<label class="figure-flex">
					<span class="figure-sep"><?php esc_html_e('or', 'knd');?></span>
					<input type="text" title="<?php esc_attr_e( 'Specify the amount of your donation', 'knd' );?>" name="leyka_donation_amount" class="donate_amount_flex" value="<?php echo esc_attr($supported_curr[$current_curr]['amount_settings']['flexible']);?>" <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?> maxlength="6" size="6">
				</label>
			</div>
		</div>	
	<?php } ?>
	</div>
	<div class="currency"><span class="currency-frame"><?php echo $form->get_currency_field();?></span></div>
</div>

<div class="leyka_donation_amount-error field-error"></div>
	
</div>
<?php
}

function knd_donation_card(WP_Post $campaign){


if($campaign->post_type != Leyka_Campaign_Management::$post_type) { // Wrong campaign data
	return;
}

$thumbnail_size = apply_filters('leyka_campaign_card_thumbnail_size', 'post-thumbnail', $campaign);
$css_class = apply_filters('leyka_campaign_card_class', 'leyka-campaign-card', $campaign);
if(has_post_thumbnail($campaign->ID)) {
	$css_class .= ' has-thumb';
}

$url = trailingslashit(get_permalink($campaign->ID)).'#leyka-payment-form';

$leyka_campaign = new Leyka_Campaign($campaign);

$target = (int)$leyka_campaign->target;
$curr_label = leyka_get_currency_label('rur');
$collected = $leyka_campaign->get_collected_amount();

if($target <= 0) {
	return;
}

$percentage = round(($collected/$target)*100);
if($percentage > 100) {
	$percentage = 100;
}

$target_f = number_format($target, 0, '.', ' ');
$collected_f = number_format($collected, 0, '.', ' ');

$campaign_age = get_post_meta($campaign->ID, 'campaign_age', true);
$is_finished = get_post_meta($campaign->ID, 'is_finished', true);

?>
<article <?php post_class('tpl-post card flex-cell flex-md-6', $campaign); ?>>
	<div class="<?php echo esc_attr($css_class);?>">
		<?php if(has_post_thumbnail($campaign->ID)) {?>
			<div class="lk-thumbnail">
				<a href="<?php echo get_permalink($campaign);?>">
					<?php echo get_the_post_thumbnail(
						$campaign->ID,
						$thumbnail_size,
						array('alt' => esc_attr(sprintf(__('Thumbnail for - %s', 'knd'), $campaign->post_title)),)
					);?>
				</a>
			</div>
		<?php }?>

		<div class="lk-info">
		
			<div class="help-purpose"><?php echo knd_leyka_help_purpose($campaign);?></div>
		
			<h4 class="lk-title"><a href="<?php echo get_permalink($campaign);?>">
				<?php echo get_the_title($campaign);?><?php if($campaign_age): echo ", {$campaign_age}"; endif;?>
			</a></h4>

			<?php
				if ( $is_finished ){
					$exerpt = esc_html__( 'Thank you for you support. This campaign is finished and help is going to be provided. Please follow the updates.', 'knd' );
					$css = 'closed';
				} else {
					$exerpt = knd_get_post_excerpt( $campaign, 28, false );
					$css = 'regular';
				}
			?>
			<p class="<?php echo esc_attr( $css ); ?>"><?php echo esc_html( $exerpt ); ?></p>

		</div>

		<div class="leyka-scale-compact">
			
			<div class="leyka-scale-scale">
				<div class="target">
					<div style="width:<?php echo $percentage;?>%" class="collected">&nbsp;</div>
				</div>
			</div>
			
			<div class="flex-row leyka-scale-label">
			
				<div class="flex-cell flex-sm-6">
					<div class="caption"><?php esc_html_e('Collected', 'knd')?></div>
					<div class="sum"><?php echo $collected_f?> <?php echo $curr_label?></div>
				</div>
				
				<?php if(!$is_finished):?>
				<div class="flex-cell flex-sm-6 knd-campaign-needed">
					<div class="caption"><?php esc_html_e('Needed', 'knd')?></div>
					<div class="sum"><?php echo $target_f?> <?php echo $curr_label?></div>
				</div>
				<?php endif?>
				
			</div>
		</div>
		<?php if(!$is_finished):?>
		<div class="leyka-scale-button-alone">
			<a href="<?php echo $url;?>"><?php esc_html_e( 'Help now', 'knd' ); ?></a>
		</div>
		<?php endif?>

	</div>
</article>
<?php
}

// customize default leyka colors
remove_action( 'wp_head', 'leyka_inline_scripts');

add_action('wp_head', 'knd_leyka_inline_scripts');
function knd_leyka_inline_scripts(){
	
	$main_color = knd_get_main_color();
	$dark_color = knd_color_luminance($main_color, -0.1);
	$light_color = knd_color_luminance($main_color, 0.2);
	
	$colors = array($main_color, $dark_color, $light_color); // Leyka green

// detect if we have JS at all... ?>

	<script>
		document.documentElement.classList.add("leyka-js");
	</script>
	<style>
		:root {
			--color-main: 		<?php echo $colors[0];?>;
			--color-main-dark: 	<?php echo $colors[1];?>;
			--color-main-light: <?php echo $colors[2];?>;
		}
	</style>
	<?php
}


add_action('parse_query', 'knd_leyka_request_corrected');
function knd_leyka_request_corrected(WP_Query $query) {

	if(is_admin()) {
		return;
	}
	
	if(is_post_type_archive( Leyka_Campaign_Management::$post_type ) && $query->is_main_query()) {
		$meta_query = $query->get( 'meta_query' );
		if(!$meta_query) {
			$meta_query = array();
		}
		
		$meta_query[] = array(
			'key'     => 'campaign_target',
			'value'   => '1',
			'compare' => '>'
		);
		
		if(isset($query->query_vars['completed']) && $query->query_vars['completed'] == 'true') {
			$meta_query[] = array(
				'key' => 'is_finished',
				'value' => 1,
			);
		}
		elseif(isset($query->query_vars['active']) && $query->query_vars['active'] == 'true') {
			$meta_query[] = array(
				'key' => 'is_finished',
				'value' => 0,
			);
		}
		
		$query->set( 'meta_query', $meta_query );
	}

}

add_action('init', 'knd_leyka_rewrite_rules');
function knd_leyka_rewrite_rules(){
	
	add_rewrite_rule(
		'^campaign/completed/page/(\d+)/?$',
		'index.php?post_type=' . Leyka_Campaign_Management::$post_type . '&completed=true&paged=$matches[1]',
		'top'
	);
	
	add_rewrite_rule(
		'^campaign/completed/?$',
		'index.php?post_type=' . Leyka_Campaign_Management::$post_type . '&completed=true',
		'top'
	);
	
	
	add_rewrite_rule(
		'^campaign/active/page/(\d+)/?$',
		'index.php?post_type=' . Leyka_Campaign_Management::$post_type . '&active=true&paged=$matches[1]',
		'top'
	);
	
	add_rewrite_rule(
		'^campaign/active/?$',
		'index.php?post_type=' . Leyka_Campaign_Management::$post_type . '&active=true',
		'top'
	);
	
	flush_rewrite_rules();
}

function knd_leyka_add_query_vars_filter( $vars ){
	$vars[] = "completed";
	$vars[] = "active";
	return $vars;
}
add_filter( 'query_vars', 'knd_leyka_add_query_vars_filter' );

function knd_leyka_help_purpose($campaign) {
	$terms = wp_get_post_terms( $campaign->ID, 'post_tag' );
	$cnt = count($terms);
	return $cnt && isset($terms[$cnt - 1]) ? $terms[$cnt - 1]->name : __('Charity', 'knd');
}

// edit kid age
function knd_leyka_age_metabox_display_callback($post) {
	
	$kid_age = get_post_meta($post->ID, 'campaign_age', true);
?>
	<div class='inside'>
		<p>
			<input type="text" name="knd-leyka-kid-age" value="<?php echo esc_html($kid_age); ?>" /> 
		</p>
	</div>
<?php 
	wp_nonce_field( 'knd_leyka_kid_age_nonce_action', 'knd-leyka-save-kid-age' );
}

function knd_leyka_add_metabox() {
	$plot = knd_get_theme_mod('knd_site_scenario');
	if($plot == 'fundraising-org') {
		add_meta_box( 'knd-leyka-kid-age', esc_html__( 'Kid age', 'knd' ), 'knd_leyka_age_metabox_display_callback', 'leyka_campaign' );
	}
}
add_action( 'add_meta_boxes', 'knd_leyka_add_metabox' );

function knd_leyka_save_kid_age_metabox( $post_id, $post ) {
	
	$nonce_name   = isset( $_POST['knd-leyka-save-kid-age'] ) ? $_POST['knd-leyka-save-kid-age'] : '';
	$campaign_age   = isset( $_POST['knd-leyka-kid-age'] ) ? $_POST['knd-leyka-kid-age'] : '';
	
	$nonce_action = 'knd_leyka_kid_age_nonce_action';
	
	if ( ! isset( $nonce_name ) ) {
		return;
	}
	
	if( $post->post_type != 'leyka_campaign' ) {
		return;
	}

	if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	
	update_post_meta( $post_id, 'campaign_age', $campaign_age );
	
}
add_action( 'save_post', 'knd_leyka_save_kid_age_metabox', 10, 2 );

function knd_clear_donation_transients(){
	delete_transient('knd_default_campaigns');
}
add_action('knd_save_demo_content', 'knd_clear_donation_transients');