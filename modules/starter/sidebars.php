<?php

class KND_StarterSidebars {
    
    public static function setup_footer_sidebar() {
        
        $sidebars = get_option( 'sidebars_widgets' );
        
        /*// empty sidebar
        $sidebars['knd-footer-sidebar'] = array();
        update_option( 'sidebars_widgets', $sidebars );
        
        $menu_name = esc_html__( 'Kandinsky our work footer menu', 'knd' );
        $our_work_menu = wp_get_nav_menu_object( $menu_name );
        
        $menu_name = esc_html__( 'Kandinsky news footer menu', 'knd' );
        $news_menu = wp_get_nav_menu_object( $menu_name );
        
        if(true) { // always change
            
            $text_widgets = get_option('widget_text');

            $home_url = home_url('/');
            $text_widget_text = get_option('knd_footer_contacts');
            $text_widgets[] = array('title' => __('About Us', 'knd'), 'text' => trim(preg_replace('/\r\n|\r|\n/', '', $text_widget_text)), 'filter' => 'content' );
            $text_widgets_keys = array_keys($text_widgets);
            $widget_index = end($text_widgets_keys);
            $sidebars['knd-footer-sidebar'][] = 'text-' . $widget_index;
            
            $nav_menu_widgets = get_option('widget_nav_menu');
            
            if( $our_work_menu ) {
                $nav_menu_widgets[] = array('title' => __('Our Work', 'knd'), 'nav_menu' => $our_work_menu->term_id);
                $nav_menu_widgets_keys = array_keys($nav_menu_widgets);
                $widget_index = end($nav_menu_widgets_keys);
                $sidebars['knd-footer-sidebar'][] = 'nav_menu-' . $widget_index;
            }
            
            if( $news_menu ) {
                $nav_menu_widgets[] = array('title' => __('News', 'knd'), 'nav_menu' => $news_menu->term_id);
                $nav_menu_widgets_keys = array_keys($nav_menu_widgets);
                $widget_index = end($nav_menu_widgets_keys);
                $sidebars['knd-footer-sidebar'][] = 'nav_menu-' . $widget_index;
            }
            
            $home_url = home_url('/');
            $text_widget_text = get_option('knd_footer_security_pd');
            $text_widgets[] = array('title' => __('Security policy', 'knd'), 'text' => trim(preg_replace('/\r\n|\r|\n/', '', $text_widget_text)), 'filter' => 'content' );
            $text_widgets_keys = array_keys($text_widgets);
            $widget_index = end($text_widgets_keys);
            $sidebars['knd-footer-sidebar'][] = 'text-' . $widget_index;
            
            // save options permanently
            update_option( 'widget_nav_menu', $nav_menu_widgets );
            update_option( 'widget_text', $text_widgets );
            update_option( 'sidebars_widgets', $sidebars );
        }
        */
        
    }
    
}