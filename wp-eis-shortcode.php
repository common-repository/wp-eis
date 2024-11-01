<?php  
add_shortcode('wp_eis', 'wp_eis_shortcode');
function wp_eis_shortcode($att, $content) {
	$att = shortcode_atts(
		array(
			'id'	  => '1',
			'content' => !empty($content) ?  $content : ''
		)
		, $att);
	extract($att);
	$ID_name = $id;

	if($ID_name) {
		global $wpdb;

		$get_eis = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_name WHERE ID_name = {$ID_name}");
		$get_eis = $get_eis[0];
		$get_eis_items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eis_items WHERE ID_name = {$ID_name}");


		// Order By Order 
		$new_get_eis_items = array();
		foreach ($get_eis_items as $key => $value) {
			if( empty($value->order) || $value->order == 0 ) { 
				$new_key = $value->order = $value->ID; 
			} else {
				$new_key = $value->order;
			}
			$new_get_eis_items[$new_key] = $value;
		}
		if( !function_exists('sort_by_id') ) {
			function sort_by_id($a, $b){
			    if ( $a->order < $b->order ) return -1;
			    if ( $a->order > $b->order ) return 1;
			    return 0; // equality
			}
			uasort($new_get_eis_items, 'sort_by_id');
		}
		
		$output  = '<h1>'.$content.'</h1>'."\n";
        $output .= '<div class="eis-wrapper">'."\n";
        $output .= '<div id="'.str_replace("_", "-", $get_eis->name).'" class="ei-slider">'."\n";
        $output .= '<ul class="ei-slider-large">'."\n";

        // EIS Large Images 
        if ( count($new_get_eis_items) > 0) {
        	foreach ($new_get_eis_items as $get_eis_item) {
        		$path_info = pathinfo($get_eis_item->image);
        		$output .= '<li>'."\n";
					$output .= '<img src="'.$get_eis_item->image.'" alt="'.$path_info['filename'].'"/>'."\n";
		            $output .= '<div class="ei-title">'."\n";
		                $output .= '<h2>'. stripslashes( $get_eis_item->title_h2 ).'</h2>'."\n";
		                $output .= '<h3>'. stripslashes( $get_eis_item->title_h3 ).'</h3>'."\n";
		            $output .= '</div>'."\n";
		        $output .= '</li>'."\n";
        	}
        }
		
        $output .= '</ul><!-- ei-slider-large -->'."\n";
       	$output .= '<ul class="ei-slider-thumbs">'."\n";
       	$output .= '<li class="ei-slider-element">Current</li>'."\n";

       	// EIS Thumbnails 
       	if ( count($new_get_eis_items) > 0 ) {
       		$path_info = pathinfo($get_eis_item->image);
       		foreach ($new_get_eis_items as $get_eis_item) {
				$output .= '<li><a href="#">'.$path_info['filename'].'</a><img src="'.$get_eis_item->image.'" width="'.$get_eis->thumb_max_width.'" alt="'.$path_info['filename'].'" /></li>'."\n";
       		}
       	}

		$output .= '</ul><!-- ei-slider-thumbs -->'."\n";
		$output .= '</div><!-- ei-slider -->'."\n";
		$output .= '</div><!-- eis-wrapper -->'."\n";

// EIS Settings Options 
$options = get_option('wp_eis_settings');

$h2_fontname = isset($options['eis_h2_font']) && !empty($options['eis_h2_font']) 							? 'font-family: '.$options['eis_h2_font'].';' 					: '';
$h2_fontsize = isset($options['eis_h2_size']) && !empty($options['eis_h2_size']) 							? 'font-size: '.$options['eis_h2_size'].'px;' 					: '';
$h2_fontcolor= isset($options['eis_h2_color']) && !empty($options['eis_h2_color']) 							? 'color: '.$options['eis_h2_color'].';' 						: '';

$h3_fontname = isset($options['eis_h3_font']) && !empty($options['eis_h3_font']) 							? 'font-family: '.$options['eis_h3_font'].';' 					: '';
$h3_fontsize = isset($options['eis_h3_size']) && !empty($options['eis_h3_size']) 							? 'font-size: '.$options['eis_h3_size']."px;" 					: '';
$h3_fontcolor= isset($options['eis_h3_color']) && !empty($options['eis_h3_color']) 							? 'color: '.$options['eis_h3_color'].';'						: '';

$nav_color= isset($options['eis_nav_color']) && !empty($options['eis_nav_color']) 							? 'background-color: '.$options['eis_nav_color'].';'			: '';
$nav_hover_color= isset($options['eis_nav_hover_color']) && !empty($options['eis_nav_hover_color']) 		? 'background-color: '.$options['eis_nav_hover_color'].';' 		: '';
$nav_current_color= isset($options['eis_nav_current_color']) && !empty($options['eis_nav_current_color']) 	? 'background-color: '.$options['eis_nav_current_color'].';' 	: '';

$loading_image = isset($options['eis_loading_image']) && (!empty($options['eis_loading_image']) && ($options['eis_loading_image'] !== 'default') ) 
				 ? 'background: url('.WP_EIS_URL.'images/loading/'.$options['eis_loading_image'].'.gif) no-repeat center;'
				 : '';

// EIS CSS Settings
$output .=
<<< EOF
<style>
	.ei-title h2 {{$h2_fontname} {$h2_fontsize} {$h2_fontcolor}}
	.ei-title h3 {{$h3_fontname} {$h3_fontsize} {$h3_fontcolor}}
	.ei-slider-thumbs li a {{$nav_color}}
 	.ei-slider-thumbs li a:hover {{$nav_hover_color}}
 	.ei-slider-thumbs li.ei-slider-element {{$nav_current_color}}
 	.ei-slider-loading {{$loading_image}}
</style>
EOF;

$loading_title 		= isset($options['eis_loading_title']) && !empty($options['eis_loading_title']) ? 			$options['eis_loading_title'] 										: 'Loading...';
$id_slider 			= "#".str_replace("_", "-", $get_eis->name);
$animation 			= ( isset( $get_eis->animation ) && !empty( $get_eis->animation ) ) ? 						"animation: '".$get_eis->animation."'," 					: ''; 
$autoplay 			= ( isset( $get_eis->autoplay )  && !empty( $get_eis->autoplay ) ) ? 						"autoplay: ".$get_eis->autoplay."," 						: ''; 
$slideshow_interval = ( isset( $get_eis->slideshow_interval )  && !empty( $get_eis->slideshow_interval ) ) ? 	"slideshow_interval: ".$get_eis->slideshow_interval."," 	: ''; 
$speed 				= ( isset( $get_eis->speed )  && !empty( $get_eis->speed ) ) ? 								"speed: ".$get_eis->speed."," 								: ''; 
$easing 			= ( isset( $get_eis->easing )  && !empty( $get_eis->easing ) ) ? 							"easing: '".$get_eis->easing."'," 							: ''; 
$titles_factor 		= ( isset( $get_eis->titles_factor )  && !empty( $get_eis->titles_factor ) ) ? 				"titlesFactor: ".$get_eis->titles_factor."," 				: ''; 
$titles_speed 		= ( isset( $get_eis->titles_speed )  && !empty( $get_eis->titles_speed ) ) ? 				"titlesSpeed: ".$get_eis->titles_speed."," 					: ''; 
$titles_easing 		= ( isset( $get_eis->titles_easing )  && !empty( $get_eis->titles_easing ) ) ? 				"titlesEasing: '".$get_eis->titles_easing."'," 				: ''; 
$thumb_max_width 	= ( isset( $get_eis->thumb_max_width )  && !empty( $get_eis->thumb_max_width ) ) ? 			"thumbMaxWidth: ".$get_eis->thumb_max_width."" 				: ''; 

// EIS JS Settings
$output .= 
<<< EOF
<script type=text/javascript>
jQuery(document).ready( function($) {
	$('{$id_slider}').eislideshow({
		{$animation}
		{$autoplay}
		{$slideshow_interval}
		{$speed}
		{$easing}
		{$titles_factor}
		{$titles_speed}
		{$titles_easing}
		{$thumb_max_width}
	});
	$('{$id_slider} .ei-slider-loading').html('{$loading_title}');
});
</script>
EOF;
			
	}
	

	wp_enqueue_script('wp-eis-easing',  WP_EIS_URL.'js/jquery-easing.1.3.js', array('jquery'), '1.3' );
	wp_enqueue_script('wp-eis-slideshow',  WP_EIS_URL.'js/jquery-eislideshow.js', array('jquery'), '1.3' );
		
	return $output;

}
add_action('wp_enqueue_scripts', 'add_wp_eis_style');
function add_wp_eis_style() {
	wp_enqueue_style('wp-eis-theme', WP_EIS_URL.'css/wp-eis-theme.css', false);
}

?>