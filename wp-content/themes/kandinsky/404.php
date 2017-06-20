<?php
/** Error **/


$er_text = get_theme_mod('er_text');
$src = get_template_directory_uri().'/assets/img/er404.jpg';
get_header();
?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
</section>

<section class="main-content single-post-section">
	<div class="entry-content err-text">
		<?php echo apply_filters('rdc_the_content', $er_text); ?>	
	</div>
</section>

<section class="er-picture">
	<div class="container-wide">
		<div class="err-bg" style="background-image: url(<?php echo $src;?>);"></div>
	</div>
</section>

<?php get_footer(); ?>