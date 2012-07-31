<?php

define("PR_Avenue", get_option('siteurl') . '/wp-content/themes/Avenue');

function pr_bloginfo($string = ""){
	switch($string){
		case "template_directory" :
			echo PR_Avenue;
			break;
	}
}

// return true if the reviewzon plugin puts an image as thumb
function pr_has_post_thumbnail($image_type = 0, $post_id = null){	
	return (bool) pr_get_post_thumbnail_src( $image_type, $post_id );		
}



//return the thumbnail post id
function pr_get_post_thumbnail_src($image_type = 0, $post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	
	$meta_key = "";
	switch($image_type){
		case 0 :
			$meta_key = "ReviewAZON_LargeImage";
			break;
		case 1 :
			$meta_key = "ReviewAZON_MediumImage";
			break;
		case 2 :
			$meta_key = "ReviewAZON_SmallImage";
			break;
	}
	
	return get_post_meta( $post_id, $meta_key, true );		
}


// widgets
include_once "widgets/widgets.php";


//excerpt modification
add_action('get_the_excerpt', 'pr_shortcode', 7);
function pr_shortcode($content){	
	global $post;
		
	$shortcodes = shortcode_parse_atts($content);
	if(is_array($shortcodes)):	
		if($shortcodes[0] == "[ReviewAZON"){
			return get_post_meta($post->ID, "ReviewAZON_Description", true);			
		}		
	endif;
	
	return $content;
}
