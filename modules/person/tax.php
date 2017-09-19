<?php

class KND_PersonCategory {
    
    public static function setup_starter_data() {
        wp_insert_term( __('Volonteers', 'knd'), 'person_cat', array('slug' => 'volunteers'));
        wp_insert_term( __('Team', 'knd'), 'person_cat', array('slug' => 'team'));
    }
    
}