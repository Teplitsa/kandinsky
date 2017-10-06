<?php


//require get_template_directory().'/modules/org/hooks.php';
require get_template_part('/modules/org/', 'hooks'); //require get_template_directory().'/modules/org/widgets.php';
//require get_template_directory().'/modules/org/tax.php';

require get_template_directory('/modules/org/', 'tax.php');
class KND_Org {
    public static function setup_starter_data() {
        $input_file = get_template_directory().'/modules/org/csv/org.csv';
        knd_import_posts_from_csv($input_file, 'org', 'org_cat');
    }
}