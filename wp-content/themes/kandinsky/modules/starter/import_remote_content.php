<?php

error_reporting(E_ALL);

class KND_Import_Remote_Content {
    
    private $content_importer = NULL;
    private $plot_data = NULL;
    private $plot_name = NULL;
    
    function __construct($plot_name) {
        $this->content_importer = new KND_Import_Git_Content();
        $this->plot_name = $plot_name;
        $this->parsedown = new Parsedown();
    }
    
    public function __get($name) {
        if($name == 'plot_name') {
            return $this->plot_name;
        }
    }
    
    function import_content() {
        $this->download_content();
        $this->extract_content();
        
        $this->plot_data = $this->parse_content($this->plot_name);
        return $this->plot_data;
    }
    
    function download_content() {
        return $this->content_importer->download();
    }
    
    function extract_content() {
        return $this->content_importer->extract();
    }
    
    function parse_content($plot_name) {
        return $this->content_importer->parse($plot_name);
    }

    function is_piece($piece_name, $section = '') {
        
        if($section) {
            return isset($this->plot_data[$this->plot_name][$section][$piece_name]);
        }
        else {
            return isset($this->plot_data[$this->plot_name][$piece_name]);
        }
        
    }
    
    function get_fdata($piece_name, $section = '') {
    
        try {
            if($section) {
                $val = $this->plot_data[$this->plot_name][$section][$piece_name];
            }
            else {
                $val = $this->plot_data[$this->plot_name][$piece_name];
            }
        }
        catch (Exception $ex) {
            $val = NULL;
        }
    
        return $val;
    }
    
    function get_piece($piece_name, $section = '') {
        
        try {
            if($section) {
                $val = $this->plot_data[$this->plot_name][$section][$piece_name]['piece'];
            }
            else {
                $val = $this->plot_data[$this->plot_name][$piece_name]['piece'];
            }
        }
        catch (Exception $ex) {
            $val = NULL;
        }
        
        return $val;
    }
    
    // possible keys: title, tags, cat, lead, content, thumb
    function get_val($piece_name, $key, $section = '') {
        
        $piece = $this->get_fdata($piece_name, $section);
        
        try {
            $val = $piece['piece']->$key;
        }
        catch(Exception $ex) {
            $val = NULL;
        }
        
        return $val;
    }
    
    function get_thumb_attachment_id($piece) {
        
        $file_data = NULL;
        
        if(isset($this->plot_data[$this->plot_name][$piece->section_name][$piece->thumb])) {
            $file_data = $this->plot_data[$this->plot_name][$piece->section_name][$piece->thumb];
        }
        elseif(isset($this->plot_data[$this->plot_name]['img'][$piece->thumb])) {
            $file_data = $this->plot_data[$this->plot_name]['img'][$piece->thumb];
        }
        
        return isset($file_data['attachment_id']) ? $file_data['attachment_id'] : NULL;
    }
    
    function parse_text($text) {
        
        $new_text = $text;
        
        $new_text = preg_replace("/\/\/(.*?)(\n|$)/", '[knd_r]\1[/knd_r]', $new_text);
        
        $new_text = $this->parsedown->text($new_text);
        
        return $new_text;
    }
}


class KND_Import_Git_Content {
    
    private $content_archive_url = 'https://github.com/Teplitsa/kandinsky-text/archive/master.zip';
    private $import_content_files_dir = NULL;
    private $zip_fpath = NULL;
    private $content_files = array();
    private $piece_parser = NULL;
    
    function __construct() {
        if(!defined('FS_METHOD')) {
            define('FS_METHOD', 'direct');
        }
        
        $this->piece_parser = new KND_Git_Piece_Parser();
    }
    
    public function download() {
        return $this->download_git_zip();
    }
    
    public function extract() {
        return $this->unzip_git_zip();
    }
    
    public function parse($plot_name) {
        return $this->parse_git_files($plot_name);
    }
    
    private function download_git_zip() {
        
        $attachment_id = TST_Import::get_instance()->import_big_file( $this->content_archive_url );
//         $attachment_id = TST_Import::get_instance()->maybe_import( $this->content_archive_url );
        $this->zip_fpath = get_attached_file( $attachment_id );
    }
    
    private function unzip_git_zip() {
        
        if(!$this->zip_fpath) {
            throw Exception("No zip file!");
        }
        
        if(!is_file($this->zip_fpath)) {
            throw Exception("Zip file not found: {$this->zip_fpath}");
        }
        
        WP_Filesystem();
        $destination = wp_upload_dir();
        $destination_path = $destination['path'];
        $unzipped_dir = $destination_path . '/kandinsky-text-master';
        
        if(is_dir($unzipped_dir)) {
            knd_rmdir($unzipped_dir);
        }
        
//         echo $destination_path . "\n<br />\n";
        $unzipfile = unzip_file( $this->zip_fpath, $destination_path );
        
        if( !is_wp_error($unzipfile) ) {
            $this->import_content_files_dir = $destination_path . '/kandinsky-text-master';
        } else {
            $this->import_content_files_dir = NULL;
            throw Exception("Unzip FAILED: {$this->zip_fpath} to {$destination_path} Error: " . var_export($unzipfile, True) );
        }
    }
    
    private function parse_git_files($plot_name) {
        
        if(!$this->import_content_files_dir) {
            throw Exception("No git content dir!");
        }
        
        if(!is_dir($this->import_content_files_dir)) {
            throw Exception("Unzipped dir not found: {$this->import_content_files_dir}");
        }
        
        $plot_dir = $this->import_content_files_dir . '/' . $plot_name;
        
        if(!is_dir($plot_dir)) {
            throw Exception("Plot dir not found: {$plot_dir}");
        }
        
        $this->content_files[$plot_name] = $this->scan_content_dir($plot_dir);
        
        return $this->content_files;
    }
    
    private function scan_content_dir($plot_dir, $section = '') {
        
        $plot_dir_listing = scandir($plot_dir);
        $inner_content_files = array();
        
        foreach ($plot_dir_listing as $key => $value) {
        
            if (!in_array($value,array(".", "..", "README.md"))) {
                
                $fpath = $plot_dir . DIRECTORY_SEPARATOR . $value;
                
                if(is_dir($fpath)) {
                    $inner_content_files[$value] = $this->scan_content_dir($fpath, $value);
                }
                else {
                    
                    $file_data = array('file' => $fpath);
                    $piece_name = preg_replace("/\.md$/", "", $value);
                    
                    if(preg_match("/.*\.md$/", $value)) {
                        
                        if(is_file($fpath)) {
                            $piece_data = $this->piece_parser->parse_post( $fpath );
                            $piece_data['piece_name'] = $piece_name;
                            $piece_data['piece_section'] = $section;
                            $file_data['piece'] = new KND_Piece($piece_data);
                        }
                        
                    }
                    elseif(preg_match("/.*\.(svg|jpg|jpeg|png)$/", $value)) {
                        
                        $attachment_id = TST_Import::get_instance()->maybe_import_local_file( $fpath );
                        $file_data['attachment_id'] = $attachment_id;
                        
                    }
                    
                    $inner_content_files[$piece_name] = $file_data;
                }
                
            }
        }
        
        return $inner_content_files;
    }
}


class KND_Git_Piece_Parser {
    
    function __construct() {
    }
    
    function parse_post( $fpath ) {
        
        $content = file_get_contents($fpath);
        $content_parts = explode("+++", $content);
        $text = trim(end($content_parts));
        
        $parsed_data = array();
        if( count($content_parts) > 1 ) {
            $header = trim($content_parts[0]);
            $parsed_data = $this->parse_post_header($header);
        }
        
        $parsed_data['content'] = $text;
        
        return $parsed_data;
    }
    
    function parse_post_header($header_text) {
        
        $header_text = trim($header_text);
        $header_lines = explode("\n", $header_text);
        $parsed_data = array();
        
        foreach($header_lines as $k => $line) {
            
            $line_parts = explode("=", $line);
            
            if(count($line_parts) > 0) {
                
                $param_name = trim($line_parts[0]);
                $param_val = trim($line_parts[1]);
                
                if($param_name) {
                    $param_val = trim(trim($param_val, "'\"“”"));
                    $parsed_data[$param_name] = $param_val;
                }
                
            }
        }
        
        return $parsed_data;
    }
}

class KND_Piece {

    public $title = "";
    public $tags_str = "";
    public $cat_str = "";
    public $thumb = "";
    public $lead = "";
    public $content = "";
    public $slug = "";
    
    public $tags = array();
    public $cat = array();
    
    public $piece_section = NULL;
    public $piece_name = NULL;

    function __construct($post_params) {

        $this->title = isset($post_params['title']) ? $post_params['title'] : "";
        $this->thumb = isset($post_params['thumb']) ? $post_params['thumb'] : "";
        $this->lead = isset($post_params['lead']) ? $post_params['lead'] : "";
        $this->content = isset($post_params['content']) ? $post_params['content'] : "";
        $this->slug = isset($post_params['slug']) ? $post_params['slug'] : "";
        
        $this->tags_str = isset($post_params['tags']) ? $post_params['tags'] : "";
        $terms = explode(",", $this->tags_str);
        foreach($terms as $term) {
            $term = trim($term);
            if($term) {
                $this->tags[] = $term;
            }
        }
        
        $this->cat_str = isset($post_params['cat']) ? $post_params['cat'] : "";
        $terms = explode(",", $this->cat_str);
        foreach($terms as $term) {
            $term = trim($term);
            if($term && strtolower($term) != 'uncategorized') {
                $this->cat[] = $term;
            }
        }
        
        $this->piece_name = isset($post_params['piece_name']) ? $post_params['piece_name'] : "";
        $this->piece_section = isset($post_params['piece_section']) ? $post_params['piece_section'] : "";
        
    }
    
    function get_post_slug() {
        
        $slug = "";
        
        if($this->slug) {
            
            $slug = $this->slug;
            
        }
        else {
            
            if($this->piece_section) {
                $slug = $this->piece_section . "-";
            }
            
            $slug .= $this->piece_name;
            
        }
        
        return $slug;
    }
    
}