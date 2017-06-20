<?php
/**
 * Shortcodes
 **/

add_filter('widget_text', 'do_shortcode');

/** sitemap **/
add_shortcode('rdc_sitemap', 'rdc_sitemap_screen');
function rdc_sitemap_screen($atts){
	
	$out =  wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));
	
	return $out;
}



/** Map **/
add_shortcode('pw_map', 'rdc_pw_map_screen');
function rdc_pw_map_screen($atts){
	
	extract(shortcode_atts(
		array(
			'address'           => false,
			'width'             => '100%',
			'height'            => '360px',
			'enablescrollwheel' => 'false',
			'zoom'              => 16,
			'disablecontrols'   => 'false',
			'v_shift' => 0,
			'h_shift' => 0
		),
		$atts
	));
		
	$coord = rdc_map_get_coordinates($address);	
	
	if( !is_array( $coord ) )
		return '';
	
	$map_id = uniqid( 'pw_map_' );
	$zoomControl = ($disablecontrols == 'false') ? 'true' : 'false';
	
	$center = array();
	$center['lat'] = (float)$coord['lat'] + (float)$v_shift;
	$center['lng'] = (float)$coord['lng'] + (float)$h_shift;
	
	ob_start();
?>
<div class="pw_map-wrap">
<div class="pw_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr($height); ?>; width: <?php echo esc_attr( $width); ?>"></div>
</div>
<script type="text/javascript">
	if (typeof mapFunc == "undefined") {
		var mapFunc = new Array();
	}	
	
	mapFunc.push(function (){
		
		var map = L.map('<?php echo $map_id ; ?>', {
			zoomControl: <?php echo $zoomControl;?>,
			scrollWheelZoom: <?php echo $enablescrollwheel;?>,
			center: [<?php echo $center['lat'];?>, <?php echo $center['lng'];?>],
			zoom: <?php echo $zoom;?>
		});

		//https://b.tile.openstreetmap.org/16/39617/20480.png
		//http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: 'Карта &copy; <a href="http://osm.org/copyright">Участники OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
			maxZoom: 24,
			minZoom: 3			
		}).addTo(map);
		
		var pin = L.divIcon({
			className: 'mymap-icon',
			iconSize: [36, 36],
			html: '<svg class="icon-marker"><use xlink:href="#icon-marker" /></svg>'
		});
		
		L.marker([<?php echo $coord['lat'];?>, <?php echo $coord['lng'];?>], { icon: pin }).addTo(map);
	});
		
</script>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

add_action('wp_footer', function(){
	
	$base = get_template_directory_uri().'/assets/img/';	
?>
<script type="text/javascript">
	L.Icon.Default.imagePath = '<?php echo $base; ?>';
	
	if (typeof mapFunc != "undefined") {
		for (var i = 0; i < mapFunc.length; i++) {
			mapFunc[i]();
		}
	}	
</script>
<?php
}, 100);

function rdc_map_get_coordinates( $address, $force_refresh = false ) {

    $address_hash = md5( $address );
    $coordinates = get_transient( $address_hash );

    if ($force_refresh || $coordinates === false) {

    	$args       = array( 'q' => urlencode( $address ), 'format' => 'json', 'limit' => 1 );
    	$url        = add_query_arg( $args, 'http://nominatim.openstreetmap.org/search/' );
     	$response 	= wp_remote_get( $url );
			
		
     	if( is_wp_error( $response ) )
     		return;

     	$data = wp_remote_retrieve_body( $response );

     	if( is_wp_error( $data ) )
     		return;
		
		
		if ( $response['response']['code'] == 200 ) {

			$data = json_decode( $data );

			if (isset($data[0]->lat)) {			  	

			  	$cache_value['lat'] = $data[0]->lat;
			  	$cache_value['lng'] = $data[0]->lon;
			  	$cache_value['address_name'] = (string) $data[0]->display_name;

			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			
			} else {
				return 'Неизвесная ошибка. Убедитесь что шорткод указан корректно';
			}

		} else {
		 	return 'Геокодер недоступен';
		}

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
}









/** Youtube video caption **/
add_shortcode('yt_caption', 'rdc_yt_caption_screen');
function rdc_yt_caption_screen($atts, $content = null){	
	
	return '<div class="yt-caption">'.apply_filters('rdc_the_content', $content).'</div>';
}


/** Buttons **/
add_shortcode('rdc_btn', 'rdc_btn_screen');
function rdc_btn_screen($atts){
	
	extract(shortcode_atts(array(				
		'url'  => '',
		'txt'  => ''
	), $atts));
	
	if(empty($url))
		return '';
	
	$url = esc_url($url);
	$txt = apply_filters('rdc_the_title', $txt);
	
	ob_start();
?>
<span class="rdc-btn"><a href="<?php echo $url;?>" class="rdc-button"><?php echo $txt;?></a></span>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}



/** Toggle **/
if(!shortcode_exists( 'su_spoiler' ))
	add_shortcode('su_spoiler', 'rdc_su_spoiler_screen');

function rdc_su_spoiler_screen($atts, $content = null){
	
	extract(shortcode_atts(array(
        'title' => 'Подробнее',
        'open'  => 'no',
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$title = apply_filters('rdc_the_title', $title);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	if($open == 'yes')
		$class .= ' toggled';
	
	ob_start();
?>
<div class="su-spoiler<?php echo $class;?>">
	<div class="su-spoiler-title"><span class="su-spoiler-icon"></span><?php echo $title;?></div>
	<div class="su-spoiler-content"><?php echo apply_filters('rdc_the_content', $content);?></div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Quote **/
add_shortcode('rdc_quote', 'rdc_quote_screen');
function rdc_quote_screen($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'name' => '',        
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$name = apply_filters('rdc_the_title', $name);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	ob_start();
?>
<div class="rdc-quote <?php echo $class;?>">	
	<div class="rdc-quote-content"><?php echo apply_filters('rdc_the_content', $content);?></div>
	<?php if(!empty($name)) { ?>
		<div class="rdc-quote-cite"><?php echo $name;?></div>
	<?php } ?>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}
