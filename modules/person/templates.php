<?php if( !defined('WPINC') ) die;

/** Template tags to display people on site **/

function knd_person_card(WP_Post $cpost, $linked = false){

    $pl = get_permalink($cpost);    
    $ex = knd_get_post_excerpt($cpost);
?>
<article class="tpl-person card <?php if($linked) { echo 'linked'; }?>">
<?php if($linked) {?> <a href="<?php echo $pl; ?>" class="person-link"><?php } ?>
    
    <div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'square');?></div>
    <div class="entry-data">
        <h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
        <div class="entry-meta"><?php echo apply_filters('knd_the_content', $ex);?></div>
    </div>
    
<?php if($linked) {?></a><?php } ?>
</article>
<?php
}


function knd_people_gallery($category_ids = '', $person_ids = '', $num = -1){
    
    $args = array(
        'post_type'=> 'person',
        'posts_per_page' => (int)$num
    );
    
    if($person_ids) {
        $args['post__in'] = explode(',', $person_ids);
    }
    else if($category_ids) {
        $args['tax_query'] = array(
            array(
                'taxonomy'=> 'person_cat',
                'field'   => 'id',
                'terms'   => $category_ids
            )
        );
    }


    $people = get_posts($args);
    if(empty($people)){
        return;
    }
?>
    <div class="knd-people-gallery flex-row centered">
    <?php foreach($people as $person) {?>
        <div class="person flex-cell flex-sm-6 flex-md-col-5"><?php knd_person_card($person);?></div>
    <?php }?>
    </div>
<?php
}