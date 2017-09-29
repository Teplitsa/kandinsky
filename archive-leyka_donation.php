<?php
/**
 * Donation history
 **/

$campaign_slug = get_query_var('leyka_campaign_filter');
$campaign = null;

if($campaign_slug) {	
	if($campaign = get_posts(array('post_type' => 'leyka_campaign', 'post_name' => $campaign_slug)))
		$campaign = reset($campaign);		
}

get_header();
?>
<header class="heading">
	<div class="container-text">
		<?php knd_section_title(); ?>
		<?php if($campaign){ ?>
		<h4><?php esc_html_e('Campaign', 'knd');?>: <a href="<?php echo get_permalink($campaign);?>"><?php echo get_the_title($campaign);?></a></h4>
		<?php } ?>
	</div>
</header>

<div class="container-text main-content">
<section class="donations-history-results donation_history">
<?php
	if(have_posts() && class_exists('Leyka_Donation')){
		foreach($wp_query->posts as $p){
			$donation = new Leyka_Donation($p);
			$amount = number_format($donation->sum, 0, '.', ' ');
			
			echo "<div class='ldl-item'>";
			echo "<div class='amount'>{$amount} {$donation->currency_label}</div>";
			
			$meta = array();
			$name = $donation->donor_name;
			$name = (!empty($name)) ? $name : __('Anonymous', 'knd');				
			$meta[] = '<span>'.$name.'</span>';
			
			$meta[] = '<time>'.$donation->date_funded.'</time>';
			echo "<div class='meta'>".implode(' / ', $meta)."</div>";
			echo "</div>";
		}
	}
	else {
		echo "<p>";
		echo "</p>";
	}
?>
</section>
<section class="paging"><?php knd_paging_nav($wp_query); ?></section>
</div>

<?php get_footer();
