<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bb
 */

$cc_link = '<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons СС-BY-SA 3.0</a>';
$tst = __("Teplitsa of social technologies", 'knd');
$banner = get_template_directory_uri().'/assets/images/te-st-logo-10x50';
$footer_text = get_theme_mod('footer_text');
?>
</div><!--  #site_content -->

<div id="bottom_bar" class="bottom-bar">
    <div class="container">
        <div class="flex-row">
            <div class="flex-mf-5">
            
            <h1 class="logo-name"><?php bloginfo('name');?></h1>
            <h2 class="logo-name"><?php bloginfo('description');?></h2>
            
            <?php if( False && !is_page('subscribe') ) {?>
                <h5><?php #_e('Subscribe to our newsletter', 'knd');?></h5>
                <div class="newsletter-form in-footer">
                    <?php #echo rdc_get_newsletter_form('bottom');?>
                </div>
            <?php }?>
            
            
            </div>

            <div class="flex-mf-4">
            <?php $social_icons = knd_social_links(array(), false);

            if($social_icons) {?>

                <?php echo $social_icons;?>

            <?php }?>
            </div>
        </div>
    </div>
</div>

<footer class="site-footer"><div class="container">		
	
	<div class="widget-area"><?php dynamic_sidebar( 'knd-footer-sidebar' );?></div>
    
	<div class="hr"></div>
    
	<div class="flex-row">
		
		<div class="flex-mf-6">		
				
			<div class="copy">
				<?php echo apply_filters('rdc_the_content', $footer_text); ?>	
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'knd'), $cc_link);?></p>
			</div>
			
		</div>
		
		<div class="flex-mf-6">
			<div class="te-st-bn">
				<p class="support">Сайт работает <br>на «Кандинском»</p>
				<a title="<?php echo $tst;?>" href="http://te-st.ru/" class="rdc-banner">					
					<svg class="rdc-icon"><use xlink:href="#icon-te-st" /></svg>
				</a>
			</div>			
		</div>
	</div>
	
	
</div></footer>

<?php wp_footer(); ?>
</body>
</html>