<?php
get_template_part('/modules/project/hooks');
get_template_part('/modules/project/widgets');

class KND_Project {
    
    public static function setup_starter_data() {
    }
    
    public static function get_short_list($num = 3) {
        
        //query
        $posts = get_posts(array('post_type' => 'project', 'posts_per_page' => $num));
        
        return $posts;
    }
    
}

add_action( 'customize_register', 'knd_projects_customize_register', 20 ); // 20 is important, must be more then priority in customizer.php
function knd_projects_customize_register( WP_Customize_Manager $wp_customize ) {

	$wp_customize->add_setting(
		'knd_projects_archive_title',
		array( 'default' => esc_html__( 'Our projects', 'knd' ) ) );

	$wp_customize->add_control(
		'knd_projects_archive_title',
		array(
			'label' => esc_html__('Projects archive title', 'knd'),
			'type' => 'text',
			'priority' => 20,
			'section' => 'knd_titles_and_captions' ) );

}