<?php
/** Formidable filters **/

// @to_do (no correction for repeatable section)
add_filter('frm_submit_button_class', 'rdc_formidable_submit_classes', 2, 2);
function rdc_formidable_submit_classes($class, $form){
	
	
	$class[] = 'rdc-submit-button';
	
	return $class;
}

add_filter('frm_field_classes', 'rdc_formidable_field_classes', 2, 2);
function rdc_formidable_field_classes($class, $field){
		
	if(in_array($field['type'], array('text', 'email', 'textarea', 'url', 'number'))) { 
		$class = 'rdc-textfield__input';
	}
	elseif($field['type'] == 'checkbox'){
		
		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){			
			$class .= " rdc-checkbox__input";
		}
		else {
			$class = "rdc-checkbox__input";
		}
	}
	elseif($field['type'] == 'radio'){
		$class = "rdc-radio__button";
	}
	elseif($field['type'] == 'file'){
		$class = "rdc-file__input";
	}
	
	if($field['invalid'])
		$class .= " rdc-has-error";
		
	return $class;
}

add_filter('frm_replace_shortcodes', 'rdc_formidable_default_html', 2, 3);
function rdc_formidable_default_html($html, $field, $params) {
		
	if(in_array($field['type'], array('text', 'email', 'number', 'url', 'textarea')))  {
			
		$html = str_replace(array('frm_form_field', 'form-field'), 'rdc-textfield frm_form_field', $html);
		
		$html = str_replace('frm_primary_label', 'rdc-textfield__label frm_primary_label', $html);
		$html = str_replace('frm_error', 'rdc-textfield__error frm_error', $html);
		
		if(isset($field['read_only']) && (int)$field['read_only'] == 1){			
			$html = str_replace('<input', '<input disabled="disabled" ', $html);
		}
				
		preg_match("#<\s*?label\b[^>]*>(.*?)</label\b[^>]*>#s", $html, $l);
		
		if(!empty($l)){ 
			$html = str_replace($l[0], '', $html); //delete label
			$pos = strrpos($html, "</div>");
			if($pos){
				$html = substr_replace($html, $l[0].'</div>', $pos, 6); //move it on top
			}
		}
	}	
	elseif($field['type'] == 'checkbox'){
		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){			
			$html = str_replace('<label for=', '<label class="rdc-checkbox" for=', $html);
		}
		else {
			$html = str_replace('<label for=', '<label class="rdc-checkbox" for=', $html);
		}
		
		$html = str_replace('frm_form_field', 'rdc-inputfix frm_form_field', $html);
		$html = str_replace('frm_primary_label', 'rdc-inputfix__label frm_primary_label', $html);
		
	}
	elseif($field['type'] == 'radio'){
		
		$html = str_replace('<label for=', '<label class="rdc-radio" for=', $html);
		$html = str_replace('frm_primary_label', 'rdc-inputfix__label frm_primary_label', $html);
		$html = str_replace('frm_form_field', 'rdc-inputfix frm_form_field', $html);
	}
	elseif($field['type'] == 'select'){
		
		$html = str_replace('frm_form_field', 'rdc-select frm_form_field', $html);
		$html = str_replace('frm_primary_label', 'rdc-inputfix__label frm_primary_label', $html);
		
		preg_match("#<\s*?select\b[^>]*>(.*?)</select\b[^>]*>#s", $html, $l);
		if(!empty($l)){
			$html = str_replace($l[0], '<label class="rdc-select-wrap">'.$l[0].'</label>', $html); //delete label
		}
	}	
	elseif($field['type'] == 'file'){ //not ready
				
		preg_match_all("/<input[^>]+>/i", $html, $m); 
		if(isset($m[0]) && !empty($m[0])){
			
			$label = esc_attr($field['name']);
			$input = implode('', array_map('trim', (array)$m[0]));
			$id = esc_attr($field['id']);
			//frm_field_95_container
						
			$html ='<div id="frm_field_'.$id.'_container" class="mdl-textfield mdl-js-textfield mdl-textfield--file mdl-textfield--full-width frm_form_field form-field  frm_top_container is-disabled"><input class="mdl-textfield__input" placeholder="'.$label.'" type="text" class="uploadFile" disabled="disabled"/><div class="mdl-button mdl-button--primary mdl-button--icon mdl-button--file"><i class="material-icons">attach_file</i>'.$input.'</div>';
			if(isset($params['errors']['field'.$id])){
				$html .= '<div class="frm_error">Некорректный формат файла.</div>';
			}
			$html .= '</div>';
		}
		
	}
	
	
	return $html;
}


add_filter('frm_field_label_seen', 'rdc_formidable_input_options_html', 2, 3);
function rdc_formidable_input_options_html($opt, $opt_key, $field) {
	
	if(is_admin())
		return $opt;
	
	if($field['type'] == 'checkbox') {
		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){
			$opt = "<span class='rdc-checkbox__label'>{$opt}</span>";
		}
		else {
			$opt = "<span class='rdc-checkbox__label'>{$opt}</span>";
		}
		
	}
	elseif($field['type'] == 'radio') {
		$opt = "<span class='rdc-radio__label'>{$opt}</span>";
	}
	
	return $opt;
}


add_filter('frm_filter_final_form', 'rdc_formidable_submit_button_html', 50);
function rdc_formidable_submit_button_html($html){
	
	//// /<p .*?class="(.*?someClass.*?)">(.*?)<\/p>/
	//preg_match("/<[^>]*type=\"submit\"[^>]*>/", $html, $l);
	//
	//if(!empty($l)){
	//	$icon = rdc_svg_icon('icon-subscribe-button', false);
	//	$html = str_replace($l[0], '<span class="rdc-submit-wrap">'.$icon.$l[0].'</span>', $html);
	//}
	
	$html = str_replace('frm-show-form', 'frm-show-form novalidate', $html);
	
	return $html;
}


/** Functions to interact with Formidable **/
function rdc_frm_get_form_id_from_key ( $form_key ) {
	global $wpdb;
	
	$table_name = $wpdb->prefix.'frm_forms'; //more reliable than $frmdb->forms
	$form_id = $wpdb->get_var($wpdb->prepare("SELECT id from $table_name WHERE form_key = %s ", $form_key));	
	return is_numeric($form_id) ? $form_id : 0;
}


