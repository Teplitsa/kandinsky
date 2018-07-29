<?php
/**
 * The header for our theme.
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<?php include_once(get_template_directory()."/assets/svg/svg.svg"); //all svgs ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'knd' ); ?></a>

<header id="site_header" class="site-header">
	
	<div class="site-header-panel">
			
		<div class="site-panel-row">
        
			<div class="site-branding site-panel-cell"><?php knd_logo_markup();?></div>
			
			<?php $header_text = knd_get_theme_mod('text_in_header'); ?>
			<div class="site-details site-panel-cell">
				<div class="site-details-cell"><?php echo apply_filters('knd_the_content', $header_text); ?></div>
			</div>									
			
			<div class="trigger-button donate site-panel-cell hide-upto-medium">
				<a id="trigger_donate"  href="<?php echo knd_get_theme_mod('knd_hero_image_support_url') ?>"><?php echo knd_get_theme_mod('knd_hero_image_support_button_caption'); ?></a>
			</div>
			
			<div class="trigger-button menu site-panel-cell">
				<a id="trigger_menu" href="<?php echo home_url('sitemap');?>">
					<?php knd_svg_icon('icon-menu');?>					
				</a>				
			</div>
			
		</div>
        	
	</div>
	

	<div class="nav-overlay"></div>
	<nav id="site_nav" class="site-nav">
		<div class="site-nav-title">
			
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="snt-cell">
				<h1 class="logo-name"><?php bloginfo('name'); ?></h1>
				<h2 class="logo-name hide-upto-medium"><?php bloginfo('description'); ?></h2>
			</a>
			
			<div id="trigger_menu_close" class="trigger-button close"><?php knd_svg_icon('icon-close');?></div>
		</div>
		<?php
			$after = '<span class="submenu-trigger">'.knd_svg_icon('icon-up', false).knd_svg_icon('icon-down', false).'</span>';
			wp_nav_menu(array('menu' => __('Main menu', 'knd'), 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu', 'after' => $after));
		?>
		<div class="search-holder"><?php get_search_form();?></div>
		<?php knd_social_links();?>
	</nav>		
</header>

<div id="site_content" class="site-content"><a name="#content"></a>