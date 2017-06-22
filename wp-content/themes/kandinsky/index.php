<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

$about_post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_name IN (%s) LIMIT 1", 'about-us'));

if($about_post):
?>

<article id="single-page" class="main-content tpl-page-fullwidth">

<div class="container">
<div class="entry-content">

<div id="pl-72">
<div id="pg-72-0" class="panel-grid">
<div class="panel-row-style-homepage-intro homepage-intro panel-row-style">
<div id="pgc-72-0-0" class="panel-grid-cell">
<div class="no-bottom-margin panel-cell-style">


<div id="panel-72-0-0-0" class="so-panel widget widget_tst-featureditem panel-first-child" data-index="0">
<div class="so-widget-tst-featureditem so-widget-tst-featureditem-base">
<section class="intro-head-image text-over-image">
<div class="tpl-pictured-bg" style="background-image: url(https://ngo2.ru/kandinsky-files/knd-img1.jpg)"></div>
</section>
<section class="intro-head-content text-over-image has-button">
<div class="ihc-content">
<a href="#">
<h1 class="ihc-title">
<span>Поддержать «Линию Цвета»</span>
</h1>
<div class="ihc-desc">
<p>
<b>Помоги людям бороться с алкогольной зависимостью.</b>
В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваши пожертвования помогут организовать для них реабилитационную программу.
</p>
</div>
<div class="cta">Сделать пожертвование</div>
</a>
</div>
</section>
</div>
</div>

<div id="panel-72-0-0-1" class="so-panel widget widget_sow-editor panel-last-child" data-index="1">
<div class="content-center container-extended-colored panel-widget-style">
<div class="so-widget-sow-editor so-widget-sow-editor-base">
<div class="siteorigin-widget-tinymce textwidget">
<h2>Наша организация «Линия Цвета»</h2>
<p>
Более 10 лет мы помогаем людям, страдающим алкоголизмом в нашем городе
<br>
организуя реабилитационные программы.
</p>
</div>
</div>
</div>
</div>

</div></div></div></div></div></div></div>
</article>

<?php

endif;


if(is_home() && $paged == 1) { //featured posts
	//2 for featured 
	$featured = array_slice($posts, 0, 2); 
	array_splice($posts, 0, 2);
?>
<section class="featured-post"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2">
	<?php
		foreach($featured as $f){
			rdc_related_post_card($f);
		}
	?>
</div>
</div></section>
<?php } ?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">
	<?php
		if(!empty($posts)){
			foreach($posts as $p){
				rdc_post_card($p);
			}
		}
		else {
			#echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>


<section class="paging"><?php rdc_paging_nav($wp_query); ?></section>

<?php get_footer();