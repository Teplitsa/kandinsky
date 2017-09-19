<?php

/** == Posts elements == **/
function knd_post_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('knd_the_title', knd_get_post_excerpt($cpost, 25, true));
?>
<article class="flex-md-6 flex-lg-4 tpl-post card">
	<a href="<?php echo $pl; ?>" class="thumbnail-link">
	<div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	</div>
    
    <div class="entry-meta"><?php echo strip_tags(knd_posted_on($cpost), '<span>');?></div>
	</a>
</article>
<?php
}

function rdc_intro_card_markup_below($title, $subtitle, $img_id, $link = '', $button_text = '') {
	
	$button_text = (!empty($button_text)) ? $button_text : __('More', 'knd');
	$has_sharing = (!empty($link)) ? false : true;
?>
	<section class="intro-head-image">
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo wp_get_attachment_url( $img_id );?>)"></div>
	</section>
	<section class="intro-head-content<?php if(!empty($link)) { echo '  has-button'; }?>"><div class="ihc-content">
		<h1 class="ihc-title"><?php if(!empty($link)) { ?><a href="<?php echo esc_url($link);?>"><?php } ?>
			<?php echo apply_filters('knd_the_title', $title);?>
			<?php if(!empty($link)) { ?></a><?php } ?>
		</h1>
		<?php if($subtitle){ ?>
			<div class="frame">
				<div class="bit <?php if(!empty($link)){ echo 'md-8 exlg-9'; }?> ihc-desc"><?php echo apply_filters('knd_the_content', $subtitle); ?></div>
				<?php if(!empty($link)) { ?>
				<div class="bit md-4 exlg-3"><a href="<?php echo esc_url($link);?>"><?php echo $button_text;?></a></div>
				<?php } ?>
			</div>
		<?php } ?>	
	</div></section>
	<?php if($has_sharing) { ?>	
		<div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js();?></div>
	<?php }?>
<?php
}

function rdc_intro_card_markup_over($title, $subtitle, $img_id, $link = '', $button_text = '', $style = 'below') {
	
	$button_text = (!empty($button_text)) ? $button_text : __('More', 'knd');
	$has_sharing = (!empty($link)) ? false : true;
	
?>
	<section class="intro-head-image text-over-image">
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo wp_get_attachment_url( $img_id );?>)"></div>
	</section>
	<section class="intro-head-content text-over-image<?php if(!empty($link)) { echo '  has-button'; }?>"><div class="ihc-content">
	<?php if(!empty($link)) { ?><a href="<?php echo esc_url($link);?>"><?php } ?>
	
		<h1 class="ihc-title"><span><?php echo apply_filters('knd_the_title', $title);?></span></h1>
		<?php if($subtitle){ ?>
			<div class="ihc-desc"><?php echo apply_filters('knd_the_content', $subtitle); ?></div>
		<?php } ?>
		<?php if(!empty($link)) { ?>
			<div class="cta"><?php echo $button_text;?></div>
		<?php } ?>		
		
	<?php if(!empty($link)) { ?></a><?php } ?>
	</div></section>
	<?php if($has_sharing) { ?>	
		<div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js();?></div>
	<?php }

}


function knd_related_post_card(WP_Post $cpost) {

	$pl = get_permalink($cpost);
	$ex = apply_filters('knd_the_title', knd_get_post_excerpt($cpost, 40, true));
?>
<article class="flex-md-6 tpl-related-post card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
        <h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	</div>
    <?php if('project' != $cpost->post_type) { ?>
    <div class="entry-meta"><?php echo strip_tags(knd_posted_on($cpost), '<span>');?></div>
    <?php } ?>
    
</a></article>	
<?php
}

function knd_related_post_link(WP_Post $cpost) {
    $pl = get_permalink($cpost);
?>
    <a href="<?php echo $pl; ?>" class="entry-link"><?php echo get_the_title($cpost);?></h4></a>	
<?php
}

function rdc_event_card(WP_Post $cpost){
		
	//162	
	$event = new TST_Event($cpost);
	
	$pl = get_permalink($event->post_object);	
	
?>
<article class="tpl-event card" <?php echo $event->get_event_schema_prop();?>">
	<a href="<?php echo $pl; ?>" class="thumbnail-link" <?php echo $event->get_event_url_prop();?>>
	<div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<div class="entry-meta"><?php echo $event->posted_on_card();?></div>
		<h4 class="entry-title" <?php echo $event->get_event_name_prop();?>><?php echo get_the_title($cpost);?></h4>
		<div class="entry-summary">
			<p><?php echo apply_filters('tst_the_title', $event->get_participants_mark());?></p>
			<p><?php echo apply_filters('tst_the_title', $event->get_full_address_mark());?></p>
			<?php echo $event->get_event_offer_field();?>
		</div>
	</div>
	</a>
</article>
<?php
}

/** Projects */
function knd_project_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="flex-md-4 tpl-project card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<h4 class="entry-title"><span><?php echo get_the_title($cpost);?></span></h4>
</a></article>
<?php
}

function tst_project_card_group(WP_Post $cpost){
    knd_project_card($cpost);	
}

function tst_project_card_single(WP_Post $cpost){
    knd_project_card($cpost);	
}


/* Orgs */
function knd_org_card(WP_Post $cpost){?>
<article class="tpl-org logo">
	<a href="<?php echo esc_url($cpost->post_excerpt);?>" class="logo-link logo-frame" target="_blank" title="<?php echo esc_attr($cpost->post_title);?>">
		<span><?php echo get_the_post_thumbnail($cpost, 'post-thumbnail');?></span>
	</a>
</article>
<?php
}



/** search **/
function rdc_search_card(WP_Post $cpost) {
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('knd_the_title', knd_get_post_excerpt($cpost, 40, true));
	
	
?>
<article class="tpl-search"><a href="<?php echo $pl; ?>" class="entry-link">
	<div class="entry-meta"><?php echo strip_tags(knd_posted_on($cpost), '<span>');?></div>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-summary"><?php echo $ex;?></div>
</a></article>
<?php
}





/** == Helpers == **/

/** Excerpt **/
function knd_get_post_excerpt($cpost, $l = 30, $force_l = false){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);	
	
	return $e;
}


/** Deafult thumbnail for posts **/
function rdc_get_default_post_thumbnail($type = 'default_thumbnail', $size){
		
	$default_thumb_id = attachment_url_to_postid(get_theme_mod($type));
	$img = '';
	if($default_thumb_id){
		$img = wp_get_attachment_image($default_thumb_id, $size);	
	}
	
	return $img;
}

function knd_post_thumbnail($post_id, $size = 'post-thumbnail', $default = true){
	
	$thumb = get_the_post_thumbnail($post_id, $size);
	
	if(!$thumb && $default){
		$thumb = rdc_get_default_post_thumbnail('default_thumbnail', $size);
	}
			
	return $thumb;
}

function knd_post_thumbnail_src($post_id, $size = 'post-thumbnail'){
	
	$src = get_the_post_thumbnail_url($post_id, $size);
	if(!$src){
		$default_thumb_id = attachment_url_to_postid(get_theme_mod('default_thumbnail'));
		if($default_thumb_id){
			$src = wp_get_attachment_image_src($default_thumb_id, $size);
			$src = ($src) ? $src[0] : '';
		}
	}
	
	return $src;
}

function knd_single_post_thumbnail($post_id, $size = 'post-thumbnail', $post_format = 'standard'){
	
	$thumb_id = get_post_thumbnail_id($post_id);
	if(!$thumb_id)
		return;
	
	$thumb = get_post($thumb_id);
	$cap = (!empty($thumb->post_excerpt)) ? $thumb->post_excerpt : get_post_meta($thumb_id , '_wp_attachment_image_alt', true); //to_do make this real
	
	
	if($post_format == 'standard'){
?>
	<figure class="wp-caption alignnone">
		<?php echo wp_get_attachment_image($thumb_id, $size);?>
		<?php if(!empty($cap)) { ?>
			<figcaption class="wp-caption-text"><?php echo apply_filters('knd_the_title', $cap);?></figcaption>
		<?php } ?>
	</figure>	
<?php
	
	}
	elseif($post_format == 'introimg'){		
?>
	<figure class="introimg-figure">
		<div class="introimg">
			<div class="tpl-pictured-bg" style="background-image: url(<?php echo get_the_post_thumbnail_url($post_id, $size);?>);" ></div>
		</div>
		<?php if(!empty($cap)) { ?>
			<figcaption class="wp-caption-text"><?php echo apply_filters('knd_the_title', $cap);?></figcaption>
		<?php } ?>
	</figure>
<?php
	}

}



