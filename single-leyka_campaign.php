<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 
$age = get_post_meta($cpost->ID, 'campaign_age', true);
if(!empty($age))
	$age = ', '.$age;


$is_finished = get_post_meta($cpost->ID, 'is_finished', true);

get_header();
?>
<article class="main-content leyka-campaign">
<div class="container">

	<header class="entry-header-single container-text">
        <div class="entry-meta">
        <?php if($is_finished) { ?>
            <a href="<?php echo site_url('/campaign/completed/'); ?>" class="entry-link">
                <?php esc_html_e('They alredy got help', 'knd'); ?>
            </a> 
        <?php } else { ?>
            <a href="<?php echo site_url('/campaign/active/'); ?>" class="entry-link">
                <?php esc_html_e('They need help', 'knd'); ?>
            </a> 
        <?php } ?>
        </div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?><?php echo $age;?></h1>
	</header>
	
	<main class="container-text">
    
		<div class="campaign-form ">
			<?php echo apply_filters('the_content', $cpost->post_content); ?>
		</div>

    
        <div class="related-campaigns">
            <a href="<?php echo site_url('/campaign/active/'); ?>" class="entry-link"><?php esc_html_e('They need help', 'knd') ?></a> 
            <a href="<?php echo site_url('/campaign/completed/'); ?>" class="entry-link"><?php esc_html_e('They alredy got help', 'knd') ?></a> 
        </div>
    
	</main>
	
</div>
</article>

<?php
get_footer();
