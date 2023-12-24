<?php

if(!class_exists('Leyka_Payment_Method'))
	return;

get_template_part('/modules/donations/widgets');

function knd_activate_leyka() {

	$git_imp = new KND_Import_Git_Content('color-line');
	if ( ! Knd_Filesystem::get_instance()->is_dir( $git_imp->is_dir() ) ) {
		return;
	}

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
			<div class="<?php echo esc_attr( $currency ); ?> amount-variants-container" <?php echo $currency == $current_curr ? '' : 'style="display:none;"';?> >
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

/**
 * Deprecated, remove in version 3.0
 */
function knd_donation_card(WP_Post $campaign){

if($campaign->post_type != Leyka_Campaign_Management::$post_type) { // Wrong campaign data
	return;
}

$thumbnail_size = apply_filters('leyka_campaign_card_thumbnail_size', 'medium_large', $campaign);

$url = trailingslashit(get_permalink($campaign->ID)).'#leyka-payment-form';

$leyka_campaign = new Leyka_Campaign($campaign);

$target = (int)$leyka_campaign->target;
$curr_label = leyka_get_currency_label('rur');
$collected = $leyka_campaign->get_collected_amount();

if($target <= 0) {
	//return;
}

$round = $target ? $collected/$target : 0;
$percentage = round($round*100);
if($percentage > 100) {
	$percentage = 100;
}

$target_f = number_format($target, 0, '.', ' ');
$collected_f = number_format($collected, 0, '.', ' ');

$campaign_age = get_post_meta($campaign->ID, 'campaign_age', true);
$is_finished = get_post_meta($campaign->ID, 'is_finished', true);

?>

<article <?php post_class('knd-block-item', $campaign); ?>>
	<div class="leyka-shortcode campaign-card wp-block-leyka-card">

		<?php if ( has_post_thumbnail( $campaign->ID ) ) { ?>
			<a href="<?php echo get_permalink( $campaign ); ?>" class="campaign-thumb sub-block" style="background-image:url(<?php echo get_the_post_thumbnail_url(  $campaign->ID, $thumbnail_size ); ?>);" title="<?php echo get_the_title( $campaign->ID ); ?>"></a>
		<?php } ?>

		<h2 class="campaign-title sub-block"><?php echo get_the_title( $campaign->ID ); ?></h2>

		<?php
			if ( $is_finished ){
				$exerpt = esc_html__( 'Thank you for you support. This campaign is finished and help is going to be provided. Please follow the updates.', 'knd' );
			} else {
				$exerpt = knd_get_post_excerpt( $campaign, 28, false );
			}
			if ( $exerpt ) { ?>
				<div class="campaign-excerpt"><?php echo esc_html( $exerpt ); ?></div>
			<?php
			}
		?>

		<div class="progressbar-unfulfilled sub-block">
			<div class="progressbar-fulfilled" style="width: <?php echo $percentage;?>%;"></div>
		</div>

		<div class="bottom-line sub-block">

			<div class="bottom-line-item target-info">

				<div class="funded"><?php echo leyka_format_amount( $campaign->total_funded ); ?> <?php echo leyka_get_currency_label(); ?></div>

				<div class="target"><?php echo sprintf(__('We need to raise: %s %s', 'leyka'), $target_f, leyka_get_currency_label() ); ?></div>

			</div>

			<?php if( ! $is_finished ) { ?>
				<a class="bottom-line-item leyka-button-wrapper" href="<?php echo esc_url( $url );?>"><?php esc_html_e( 'Help now', 'knd' ); ?></a>
			<?php } ?>
		</div>

	</div>
</article>
<?php
}

// customize default leyka colors
remove_action( 'wp_head', 'leyka_inline_scripts');

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
			--color-main: 		<?php echo esc_attr( $colors[0] ); ?>;
			--color-main-dark: 	<?php echo esc_attr( $colors[1] ); ?>;
			--color-main-light: <?php echo esc_attr( $colors[2] ); ?>;
		}
	</style>
	<?php
}
add_action('wp_head', 'knd_leyka_inline_scripts');

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
add_action('parse_query', 'knd_leyka_request_corrected');

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
add_action('init', 'knd_leyka_rewrite_rules');

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

function knd_clear_donation_transients(){
	delete_transient('knd_default_campaigns');
}
add_action('knd_save_demo_content', 'knd_clear_donation_transients');
