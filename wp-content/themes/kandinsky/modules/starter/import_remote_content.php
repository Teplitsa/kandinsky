<?php

error_reporting(E_ALL);

class KND_Import_Remote_Content {
    
    private $content_importer = NULL;
    
    
    function __construct() {
        $this->content_importer = new KND_Import_Git_Content();
    }
    
    function import_content($line_name) {
        $this->download_content();
        $this->extract_content();
        return $this->parse_content($line_name);
    }
    
    function download_content() {
        return $this->content_importer->download();
    }
    
    
    function extract_content() {
        return $this->content_importer->extract();
    }
    
    
    function parse_content($line_name) {
        return $this->content_importer->parse($line_name);
    }
}


class KND_Import_Git_Content {
    
    private $content_archive_url = 'https://github.com/Teplitsa/kandinsky-text/archive/master.zip';
    private $import_content_files_dir = NULL;
    private $zip_fpath = NULL;
    private $content_files = array();
    private $post_parser = NULL;
    
    function __construct() {
        if(!defined('FS_METHOD')) {
            define('FS_METHOD', 'direct');
        }
        
        $this->post_parser = new KND_Git_Post_Parser();
    }
    
    
    public function download() {
        return $this->download_git_zip();
    }
    
    public function extract() {
        return $this->unzip_git_zip();
    }
    
    public function parse($line_name) {
        return $this->parse_git_files($line_name);
    }
    
    private function download_git_zip() {
        
        $att_id = TST_Import::get_instance()->maybe_import( $this->content_archive_url );
        $this->zip_fpath = get_attached_file( $att_id );
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
        
//         echo $destination_path . "\n<br />\n";
        $unzipfile = unzip_file( $this->zip_fpath, $destination_path );
        
        if( !is_wp_error($unzipfile) ) {
            $this->import_content_files_dir = $destination_path . '/kandinsky-text-master';
        } else {
            $this->import_content_files_dir = NULL;
            throw Exception("Unzip FAILED: {$this->zip_fpath} to {$destination_path} Error: " . var_export($unzipfile, True) );
        }
    }
    
    
    private function parse_git_files($line_name) {
        
        if(!$this->import_content_files_dir) {
            throw Exception("No git content dir!");
        }
        
        if(!is_dir($this->import_content_files_dir)) {
            throw Exception("Unzipped dir not found: {$this->import_content_files_dir}");
        }
        
        $line_dir = $this->import_content_files_dir . '/' . $line_name;
        
        if(!is_dir($line_dir)) {
            throw Exception("Line dir not found: {$line_dir}");
        }
        
        $this->content_files[$line_name] = $this->scan_content_dir($line_dir);
        
        return $this->content_files;
    }
    
    private function scan_content_dir($line_dir) {
        
        $line_dir_listing = scandir($line_dir);
        $inner_content_files = array();
        
        foreach ($line_dir_listing as $key => $value) {
        
            if (!in_array($value,array(".", "..", "README.md"))) {
                
                $fpath = $line_dir . DIRECTORY_SEPARATOR . $value;
                
                if(is_dir($fpath)) {
                    $inner_content_files[$value] = $this->scan_content_dir($fpath, $value);
                }
                else {
                    $file_data = array('file' => $fpath);
                    
                    if(is_file($fpath)) {
                        $file_data['parsed_post'] = $this->post_parser->parse_post( $fpath );
                    }
                    
                    $inner_content_files[$value] = $file_data;
                }
                
            }
        }
        
        return $inner_content_files;
    }
}


class KND_Git_Post_Parser {
    
    function __construct() {
    }
    
    
    function parse_post( $fpath ) {
        
        $content = file_get_contents($fpath);
        $content_parts = explode("+++", $content);
        $text = trim(end($content_parts));
        
        $post_params = array();
        if( count($content_parts) > 1 ) {
            $header = trim($content_parts[0]);
            $post_params = $this->parse_post_header($header);
        }
        
        $Parsedown = new Parsedown();
        $html_text = $Parsedown->text($text);
//         echo $html_text . "<br />================================================================<br />";
        $post_params['content'] = $html_text;
        
//         if(count($post_params) > 1) {
//             print_r($post_params);
//         }

        return new KND_Imported_Post($post_params);
    }
    
    function parse_post_header($header_text) {
        
        $header_text = trim($header_text);
        $header_lines = explode("\n", $header_text);
        $post_params = array();
        
        foreach($header_lines as $k => $line) {
            
            $line_parts = explode("=", $line);
            
            if(count($line_parts) > 0) {
                
                $param_name = trim($line_parts[0]);
                $param_val = trim($line_parts[1]);
                
                if($param_name) {
                    $param_val = trim(trim($param_val, "'\"“”"));
                    $post_params[$param_name] = $param_val;
                }
                
            }
        }
        
        return $post_params;
    }
}


class KND_Imported_Post {
    
    public $title = "";
    public $tags = array();
    public $cat = array();
    public $lead = "";
    public $content = "";
    
    
    function __construct($post_params) {
        
        $this->title = $post_params['title'];
        $this->tags = $post_params['tags'];
        $this->cat = $post_params['cat'];
        $this->thumb = $post_params['thumb'];
        $this->lead = $post_params['lead'];
        $this->content = $post_params['content'];
    }
    
}