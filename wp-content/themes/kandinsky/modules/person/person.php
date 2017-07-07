<?php
require get_template_directory().'/modules/person/widgets.php';
require get_template_directory().'/modules/person/hooks.php';
require get_template_directory().'/modules/person/tax.php';

class KND_Person {
    
    public static function setup_starter_data() {
        $input_file = get_template_directory() . '/modules/person/csv/person.csv';
        knd_import_posts_from_csv($input_file, 'person', 'person_cat');
    }
    
}

