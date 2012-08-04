<?php

define("PR_Avenue", get_option('siteurl') . '/wp-content/themes/Avenue');

function pr_bloginfo($string = ""){
	switch($string){
		case "template_directory" :
			echo PR_Avenue;
			break;
	}
}

/*
 * pr_bloginfo
 * */
function pr_blog_info(){
	return get_option('siteurl') . '/wp-content/themes/productreview';
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


//features post has image in their custom fields
function pr_has_featured_image($post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;	
	return (bool) pr_get_featured_image_src($post_id);
}

function pr_get_featured_image_src($post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	return get_post_meta($post_id, "ReviewAZON_FImage", true);
}


/*
 * add customer reviews and minimum price from Amazon Reivew plugin
 * */
add_action("pr_bigcustomerReview_Price", 'pr_bigcustomerReview_Price', 10, 2);
function pr_bigcustomerReview_Price($post, $properties){
	
}


function pr_get_avg_rating_image($post_id = null){
	$rating = pr_get_avg_rating($post_id);
	$ratings = explode(".", $rating);
	$fraction = 0;
	if(isset($ratings[1])) :		
		if($ratings[1] < 3){
			$fraction = 0;
		}
		elseif($ratings[1] > 2 && $ratings[1] < 8){
			$fraction = 0.5;
		}
		else{
			$fraction = 1;
		}		
	endif;
	
	$overall_rating = $ratings[0] + $fraction;
	
	/*
	if($overall_rating == 0) {
		$overall_rating = "0.5";
	}
	*/
	
	$imageurl = get_option('siteurl') . '/wp-content/themes/productreview/images/stars/' . $overall_rating . '.png';
	return $imageurl;
}

/*
 * Return the average ratings from Review zone plugin
 * */
function pr_get_avg_rating($post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;	
	$rating = get_post_meta($post_id, 'ReviewAZON_AverageRating', true);
	return (empty($rating)) ? 0 : $rating;
 }


/*
 * returns the minimum price of the Review Azon plugin
 * */
function pr_get_min_price($post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	return get_post_meta($post_id, 'ReviewAZON_LowestNewPrice', true);
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


/*
 * Top Level Categories
 * */
add_filter('category_template', 'pr_top_level_category_template');
function pr_top_level_category_template($template){
	if(pr_has_child_category()){
		$template = dirname(__FILE__) . '/top-level-category.php';
	}	
	
	return $template;	
}

/*
 * returns true if it is a top level category
 * */
function pr_has_child_category(){
	$term = get_queried_object();
	$child_categories = get_categories(array('type'=>'post', 'child_of'=>$term->term_id));
	return (empty($child_categories)) ? false : true;
}
