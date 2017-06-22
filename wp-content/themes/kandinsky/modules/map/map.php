<?php
/** Move map shortcode here **/

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
        $response   = wp_remote_get( $url );
            
        
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


