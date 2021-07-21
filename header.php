<?php
/**
 * The header.
 *
 * @package Kandinsky
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php include_once get_template_directory() . '/assets/svg/svg.svg'; // all svgs. ?>

	<?php get_template_part( 'template-parts/header' ); ?>

	<div id="site_content" class="site-content">
