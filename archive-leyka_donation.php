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
<div class="heading">
	<div class="container-text">
		<?php knd_section_title(); ?>
		<?php if($campaign){ ?>
		<h4><?php esc_html_e('Campaign', 'knd');?>: <a href="<?php echo get_permalink($campaign);?>"><?php echo get_the_title($campaign);?></a></h4>
		<?php } ?>
	</div>
</div>

<div class="container-text main-content">

	<section class="donations-history-results donation_history">
	<?php
		if(have_posts() && class_exists('Leyka_Donation')){
			foreach($wp_query->posts as $p){
				$donation = new Leyka_Donation($p);
				$amount   = number_format($donation->sum, 0, '.', ' ');
				$meta     = array();
				$name     = $donation->donor_name;
				$name     = ( !empty($name)) ? $name : __('Anonymous', 'knd');
				$meta[]   = '<span>' . $name . '</span>';
				$meta[]   = '<time>' . $donation->date_funded . '</time>';
				?>
				<div class="ldl-item">
					<div class='amount'><?php echo esc_html( $amount ); ?> <?php echo esc_html( $donation->currency_label ); ?></div>
					<div class="meta"><?php echo wp_kses_post( implode(' / ', $meta) ); ?></div>
				</div>
				<?php
			}
		}
		else {
			echo "<p>";
			echo "</p>";
		}
	?>
	</section>

	<?php knd_posts_pagination( array( 'screen_reader_text' => esc_html__( 'Donations navigation', 'knd' ) ) ); ?>

</div>

<?php get_footer();
