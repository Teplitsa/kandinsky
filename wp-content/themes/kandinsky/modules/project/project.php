<?php
require get_template_directory().'/modules/project/hooks.php';

class KND_Project {
    
    public static function setup_starter_data() {
    }
    
    public static function get_short_list($num = 3) {
        
        //query
        $posts = get_posts(array('post_type' => 'post', 'posts_per_page' => $num));
        
        return $posts;
    }
    
    public static function print_short_list($posts) {
?>
        <section class="container-wide knd-projects-widget">
        
        <div class="container">
        
        <h2 class="section-title">ПРОЕКТЫ «ЛИНИИ ЦВЕТА»</h2>
        
        <div class="section-links">
            <a href="#">Все проекты</a>
            <a href="#">Пресса о нас</a>
            <a href="#">Отчеты</a>
        </div>
            
        <div class="main-content cards-holder knd-news-widget-body">
        
        <div class="cards-loop">
            <?php
                if(!empty($posts)){
                    foreach($posts as $p){
                        knd_project_card($p);
                    }
                }
            ?>
        </div>
        
        </div>
        
        </div>
        
        </section>
<?php

    }
    
}

