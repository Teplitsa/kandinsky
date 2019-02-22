<?php
/** Error 404 **/


$er_text = __('Unfortunately this page has been removed or never exists. Please, use the search field to find the information you need or visit the homepage.', 'knd');

get_header();
?>

<header class="page-header">
    <div class="container"><div class="text-column">
    
        <h1 class="page-title"><?php esc_html_e('Error 404', 'knd');?></h1>
        <div class="page-intro"><?php esc_html_e('Page not found', 'knd');?></div>
    </div></div>
</header>

<div class="page-content err404-content">
    <div class="container"><div class="the-content text-column err-404-text">
        <?php echo apply_filters('knd_entry_the_content', $er_text); ?>
    </div></div>
    <div class="widget-full widget_search search-holder"><?php get_search_form();?></div>
</div>


<?php get_footer(); 