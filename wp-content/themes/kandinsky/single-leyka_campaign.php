<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 
 
get_header();
?>
<article class="main-content leyka-campaign">
<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo knd_posted_on($cpost); //for event ?></div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?>, <?php echo get_post_meta($cpost->ID, 'campaign_age', True);?></h1>
	</header>
	
	<main>
    
		<div class="campaign-form">
				<?php echo apply_filters('the_content', $cpost->post_content); ?>
		</div>
        
    
        <!-- 
        <div class="widget donation_cta"><?php #echo apply_filters('knd_entry_the_content', $cpost->post_content); ?></div>
         -->
        
        <div class="widget donation_history">
        
            <h3><?php _e('Our supporters', 'knd');?></h3>
            <?php echo leyka_get_donors_list($cpost->ID, array('num' => 10, 'show_purpose' => 0));?>
            
            <div class="all-link"><a href="<?php echo get_permalink($cpost);?>donations"><?php _e('Full list', 'knd');?>&nbsp;&rarr;</a></div>
        </div>
    
	</main>
	
</div>
</article>
<?php
get_footer();
