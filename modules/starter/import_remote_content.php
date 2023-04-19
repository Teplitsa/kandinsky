<?php if( !defined('WPINC') ) die;

function knd_get_wizard_plot_names($plot_name = '') {

	$plot_names = array(
		'problem-org' => 'color-line',
		'fundraising-org' => 'withyou',
		'public-campaign' => 'dubrovino',
	);

	return empty($plot_name) ?
		$plot_names : (isset($plot_names[$plot_name]) ? $plot_names[$plot_name] : false);

}

/**
 * Class to download test content from specified source, extract, put extracted data into PHP array and KND_Piece and to access extracted data.
 * Usage:
 * $plot_name = 'color-line';
 * $imp = new KND_Import_Remote_Content($plot_name);
 * $imported_data = $imp->import_content();
 *
 *
 */
class KND_Import_Remote_Content {
	
	private $content_importer = NULL; // remote content imported (depends on content source), only KND_Import_Git_Content supported
	private $plot_data = NULL; // array with data, represented as array and KND_Piece
	private $plot_name = NULL; // color-line, withyou, dubrovino

	function __construct($plot_name) {

		if(knd_get_wizard_plot_names($plot_name)) {

			$this->plot_name = knd_get_wizard_plot_names($plot_name);
			$this->content_importer = new KND_Import_Git_Content($this->plot_name);

		}

		$this->parsedown = new Parsedown();

	}

	public function __get($name) {
		if($name == 'plot_name') {
			return $this->plot_name;
		} elseif($name == 'plot_data') {
			return $this->plot_data;
		} elseif($name == 'possible_plots') {
			return array_values(knd_get_wizard_plot_names());
		} elseif($name == 'possible_wizard_plots') {
			return array_keys(knd_get_wizard_plot_names());
		} else {
			return null;
		}
	}

	/**
	 * Import remote content and extract it into $this->plot_data array, 
	 * using $this->content_importer to do all source dependent things.
	 *
	 */
	function import_content() {

		$this->download_content();
		$this->extract_content();
		
		return $this->parse_plot_data();
	}

	/**
	 * Store parsed content in plot_data
	 *
	 */
	function parse_plot_data() {
		$this->plot_data = $this->parse_content($this->plot_name);
		return $this->plot_data;
	}

	/**
	 * Download content using specified importer.
	 *
	 */
	function download_content() {
		$this->content_importer->download();
	}

	/**
	 * Extract content using specified importer.
	 *
	 */
	function extract_content() {
		$this->content_importer->extract();
	}

	/**
	 * Parse extracted content using specified importer.
	 *
	 */
	function parse_content($plot_name) {
		return $this->content_importer->parse($plot_name);
	}

	function import_downloaded_content() {

		$this->plot_data = $this->parse_exist_content();
		return $this->plot_data;

	}

	function parse_exist_content() {
		return $this->content_importer->parse_exist_content($this->plot_name);
	}
	
	function extract_downloaded_file() {
		return $this->content_importer->extract_downloaded_file($this->plot_name);
	}

	/**
	 * Check if piece with name exists in section or not.
	 *
	 * @param string    $piece_name    The name of the piece.
	 * @param string    $section       The name of the section.
	 * @return bool
	*/
	function is_piece($piece_name, $section = '') {
		return $section ?
			isset($this->plot_data[$this->plot_name][$section][$piece_name]) :
			isset($this->plot_data[$this->plot_name][$piece_name]);
	}

	/**
	 * Return raw $this->plot_data element by name and section.
	 *
	 * @param string    $piece_name    The name of the piece.
	 * @param string    $section       The name of the section.
	 * @return array
	*/
	function get_fdata($piece_name, $section = '') {

		try {
			$val = $section ?
				$this->plot_data[$this->plot_name][$section][$piece_name] :
				$this->plot_data[$this->plot_name][$piece_name];
		} catch (Exception $ex) {
			$val = NULL;
		}

		return $val;
	}
	
	/**
	 * Return piece by name and section.
	 *
	 * @param string    $piece_name    The name of the piece.
	 * @param string    $section       The name of the section.
	 * @return KND_Piece|NULL
	*/
	function get_piece($piece_name, $section = '') {

		try {
			if($section) {
				$val = isset($this->plot_data[$this->plot_name][$section][$piece_name]['piece']) ? $this->plot_data[$this->plot_name][$section][$piece_name]['piece'] : NULL;
			}
			else {
				$val = isset($this->plot_data[$this->plot_name][$piece_name]['piece']) ? $this->plot_data[$this->plot_name][$piece_name]['piece'] : NULL;
			}
		} catch (Exception $ex) {
			$val = NULL;
		}

		return $val;
	}
	
	/**
	 * Return piece property by name and section.
	 *
	 * @param string    $piece_name    The name of the piece.
	 * @param string    $key           Piece property name. Possible keys: title, tags, cat, lead, replace_images, content, thumb, slug.
	 * @param string    $section       The name of the section.
	 * @return string|int|NULL
	*/
	function get_val($piece_name, $key, $section = '') {

		$piece = $this->get_fdata($piece_name, $section);
		
		try {
			$val = $piece['piece']->$key;
		} catch(Exception $ex) {
			$val = NULL;
		}

		return $val;

	}

	/**
	 * Return WP attachment ID of piece thumb.
	 *
	 * @param KND_Piece    $piece
	 * @return int|NULL
	*/
	function get_thumb_attachment_id($piece) {

		$file_data = NULL;

		if(isset($this->plot_data[$this->plot_name][$piece->piece_section][$piece->thumb])) {
			$file_data = $this->plot_data[$this->plot_name][$piece->piece_section][$piece->thumb];
		} elseif(isset($this->plot_data[$this->plot_name]['img'][$piece->thumb])) {
			$file_data = $this->plot_data[$this->plot_name]['img'][$piece->thumb];
		}
		
		return isset($file_data['attachment_id']) ? $file_data['attachment_id'] : NULL;
	}

	function get_image_attachment_id($image_name) {

		$file_data = NULL;

		if(isset($this->plot_data[$this->plot_name]['img'][$image_name])) {
			$file_data = $this->plot_data[$this->plot_name]['img'][$image_name];
		}

		return isset($file_data['attachment_id']) ? $file_data['attachment_id'] : NULL;

	}
	
	/**
	 * Parse text with parsedown parser, regexp etc.
	 *
	 * @param string    $text
	 * @return string
	*/
	function parse_text($text) {
	
		$new_text = $text;
	
		$new_text = preg_replace("/(?:^|\s+)\/\/(.*?)(\n|$)/s", '[knd_r]\1[/knd_r]', $new_text);

		if(preg_match_all("/mdlink\s*=\s*\"(.*?)\"/", $new_text, $matches)) {
			foreach($matches[0] as $i => $match) {
				$url = knd_build_imported_url($matches[1][$i]);
				$new_text = str_replace($match, $url, $new_text);
			}
		}

		if(preg_match_all("/img\s*=\s*[\"'](.*?)[\"']/", $new_text, $matches)) {
			foreach($matches[0] as $i => $match) {
				
				$attachment_id = $this->get_image_attachment_id(trim($matches[1][$i]));
				
				if($attachment_id) {
					$image_src = wp_get_attachment_image( $attachment_id, 'medium', false, array( 'alt' => '' ) );
				}
				else {
					$image_src = '';
				}

				$new_text = str_replace($match, $image_src, $new_text);
			}
		}

		$new_text = $this->parsedown->text($new_text);

		return $new_text;
	}

}

/**
 * Class to download test data from github, extract, put extracted data into PHP array and KND_Piece and to access extracted data.
 *
 */
class KND_Import_Git_Content {
	
	private $content_archive_url = false;
	private $plot_name = '';
	public $import_content_files_dir = NULL;
	private $zip_fpath = NULL;
	private $content_files = array();
	private $piece_parser = NULL;
	private $distr_attachment_id = NULL;

	function __construct($plot_name) {

		if( !defined('FS_METHOD') ) {
			define('FS_METHOD', 'direct');
		}

		$plot_name = 'color-line';

		$locale = get_locale();

		$lang = 'en';

		//$plot_name = 'color-line'; //withyou, dubrovino
		// https://knd.s3.eu-central-1.amazonaws.com/kandinsky-text-color-line-master.zip
		// https://knd.s3.eu-central-1.amazonaws.com/kandinsky-text-color-line-en.zip

		//$content_archive_url = 'http://kandinsky.loc/import/kandinsky-import-ru.zip';
		//$content_archive_url = 'https://knd.bootwp.com/import/kandinsky-import-' . $lang . '.zip';

		if ( 'ru_RU' === $locale ) {
			$lang = 'ru';
		}

		$content_archive_url = 'https://kndwp.org/import/kandinsky-import-' . $lang . '.zip';

		$this->content_archive_url = $content_archive_url;

		$this->plot_name = $plot_name;

		$this->piece_parser = new KND_Git_Piece_Parser();

	}
	
	/**
	 * Download content from github.
	 *
	 */
	public function download() {
		$this->download_git_zip();
	}
	
	/**
	 * Extract files from archive.
	 *
	 */
	public function extract() {
		$this->unzip_git_zip();
	}
	
	public function extract_downloaded_file($plot_name) {

		$exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $this->content_archive_url );
		if( $exist_attachment ) {

			$this->distr_attachment_id = $exist_attachment->ID;
			$this->zip_fpath = get_post_meta( $this->distr_attachment_id, 'kandinsky_zip_fpath', true );
			$this->import_content_files_dir = get_post_meta($this->distr_attachment_id, 'kandinsky_import_content_files_dir', true);

		}

		return $this->unzip_git_zip();

	}

	/**
	 * Extract files from archive.
	 *
	 */
	public function parse($plot_name) {
		return $this->parse_git_files($plot_name);
	}

	public function parse_exist_content($plot_name) {

		$exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $this->content_archive_url );
		if( $exist_attachment ) {

			$this->distr_attachment_id = $exist_attachment->ID;
			$this->zip_fpath = get_post_meta( $this->distr_attachment_id, 'kandinsky_zip_fpath', true );
			$this->import_content_files_dir = get_post_meta($this->distr_attachment_id, 'kandinsky_import_content_files_dir', true);

		}

		return $this->parse_git_files($plot_name);

	}
	
	/**
	 * Download zip file from github and put it into WP files gallery.
	 *
	 */
	private function download_git_zip() {

		$this->distr_attachment_id = TST_Import::get_instance()->import_big_file($this->content_archive_url);
		$this->zip_fpath = get_attached_file($this->distr_attachment_id);
		
		$destination = wp_upload_dir();
		$this->import_content_files_dir = "{$destination['path']}/kandinsky-text-{$this->plot_name}-master";
		update_post_meta( $this->distr_attachment_id, 'kandinsky_zip_fpath', wp_slash($this->zip_fpath) );
		update_post_meta( $this->distr_attachment_id, 'kandinsky_import_content_files_dir', wp_slash($this->import_content_files_dir) );

	}

	/**
	 * Unzip archive into uploads dir.
	 *
	 */
	private function unzip_git_zip() {

		if(!$this->zip_fpath) {
			throw new Exception(__('No zip file', 'knd'));
		}

		if(!is_file($this->zip_fpath)) {
			throw new Exception(sprintf(__('Zip file not found: %s', 'knd'), $this->zip_fpath));
		}

		$destination = wp_upload_dir();
		$unzipped_dir = "{$destination['path']}/kandinsky-text-{$this->plot_name}-master";

		if( ! Knd_Filesystem::get_instance()->rmdir($unzipped_dir, true) ) {
			throw new Exception(sprintf(__('Old import files cleanup FAILED: %s.', 'knd'), $destination["path"]));
		}

		$unzipfile = unzip_file( $this->zip_fpath, $destination['path'] );

		if( !is_wp_error($unzipfile) ) {

			$this->import_content_files_dir = "{$destination['path']}/kandinsky-text-{$this->plot_name}-master";

			update_post_meta( $this->distr_attachment_id, 'kandinsky_zip_fpath', wp_slash($this->zip_fpath) );
			update_post_meta( $this->distr_attachment_id, 'kandinsky_import_content_files_dir', wp_slash($this->import_content_files_dir) );

			unlink($this->zip_fpath);
			$this->zip_fpath = false;

		} else {
			$this->import_content_files_dir = NULL;
			throw new Exception(sprintf(__('Unzip FAILED: %s to %s. Error: %s', 'knd'), $this->zip_fpath, $destination["path"], var_export($unzipfile, true)));
		}
	}

	/**
	 * Parse extracted files and put into $this->content_files.
	 *
	 * @param string    $plot_name    Plot name
	 * @return array
	 */
	private function parse_git_files($plot_name) {

		if( !$this->import_content_files_dir ) {
			throw new Exception(__('No git content dir!', 'knd'));
		}

		if( !Knd_Filesystem::get_instance()->is_dir($this->import_content_files_dir) ) {
			throw new Exception(sprintf(__('Unzipped dir not found: %s', 'knd'), $this->import_content_files_dir));
		}

		$plot_dir = $this->import_content_files_dir;

		if( !Knd_Filesystem::get_instance()->is_dir($plot_dir) ) {
			throw new Exception(sprintf(__('Plot dir not found: %s', 'knd'), $plot_dir));
		}

		$this->content_files[$plot_name] = $this->scan_content_dir($plot_dir);
		
		return $this->content_files;

	}
	
	/**
	 * Recursively scan dir with extracted files and put parsed content into arrays or KND_Piece.
	 *
	 * @param string    $plot_dir   Dir path
	 * @param section   $section    Section name
	 * @return array
	 */
	private function scan_content_dir($plot_dir, $section = '') {

		$plot_dir_listing = scandir($plot_dir);
		$inner_content_files = array();

		foreach ($plot_dir_listing as $key => $value) {

			if (!in_array($value,array(".", "..", "README.md"))) {

				$fpath = $plot_dir . DIRECTORY_SEPARATOR . $value;

				if(Knd_Filesystem::get_instance()->is_dir($fpath)) {
					$inner_content_files[$value] = $this->scan_content_dir($fpath, $value);
				}
				else {
					
					$file_data = array('file' => $fpath);
					$piece_name = preg_replace("/\.md$/", "", $value);
					
					if(preg_match("/.*\.md$/", $value)) {
						
						if(Knd_Filesystem::get_instance()->is_file($fpath)) {
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

	/**
	 * Check if import folder exists.
	 *
	 * @param string $plot_name
	 * @return string
	*/
	function is_dir() {
		$destination = wp_upload_dir();
		return "{$destination['path']}/kandinsky-text-{$this->plot_name}-master";
	}
}

/**
 * Parse local file and put parsed data into array.
 *
 */
class KND_Git_Piece_Parser {

	function __construct() {
		$this->parsedown = new Parsedown();
	}

	/**
	 * Parse local file.
	 *
	 * @param string    $fpath   File path
	 * @return array
	 */
	function parse_post( $fpath ) {
		
		$content = Knd_Filesystem::get_instance()->get_contents($fpath);
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

	/**
	 * Parse file header, that located before first +++ string.
	 *
	 * @param string    $header_text   Header text
	 * @return array
	 */
	private function parse_post_header($header_text) {
		
		$header_text = trim($header_text);
		$header_lines = explode("\n", $header_text);
		$parsed_data = array();

		foreach($header_lines as $k => $line) {

			$line_parts = explode("=", $line);

			if(count($line_parts) <= 0) {
				continue;
			}

			$param_name = empty($line_parts[0]) ? '' : trim($line_parts[0]);
			$param_val = empty($line_parts[1]) ? '' : trim(trim(trim($line_parts[1]), "'\""));

			if( !$param_name || !$param_val ) {
				continue;
			}

			$parsed_data[$param_name] = $param_val;

		}

		return $parsed_data;
	}
}

/**
 * Parsed content item.
 *
 */
class KND_Piece {

	public $title = "";
	public $tags= "";
	public $cat = "";
	public $thumb = "";
	public $lead = "";
	public $replace_images = "";
	public $content = "";
	public $slug = "";
	public $url = "";

	public $metas = array();
	
	public $tags_list = array();
	public $cats_list = array();
	
	public $piece_section = NULL;
	public $piece_name = NULL;

	function __construct($post_params) {

		foreach($post_params as $k => $v) {
			$this->$k = $v;
		}
		
		if(isset($post_params['url'])) {
			$this->url = knd_build_imported_url(isset($post_params['url']) ? $post_params['url'] : "");
		}
		elseif(isset($post_params['link'])) {
			$this->url = knd_build_imported_url(isset($post_params['link']) ? $post_params['link'] : "");
		}
		
		$terms = explode(",", $this->tags);
		foreach($terms as $term) {
			$term = trim($term);
			if($term) {
				$this->tags_list[] = $term;
			}
		}
		
		$terms = explode(",", $this->cat);
		foreach($terms as $term) {
			$term = trim($term);
			if($term && strtolower($term) != 'uncategorized') {
				$this->cats_list[] = $term;
			}
		}
		
	}
	
	public function __get($name) {
		return NULL;
	}
	
	/**
	 * Get parsed item slug to use as WP Post name.
	 *
	 * @return string
	 */
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
