<?php
add_action('widgets_init', 'pyre_social_counter_load_widgets');

function pyre_social_counter_load_widgets()
{
	register_widget('Pyre_Social_Counter_Widget');
}

class Pyre_Social_Counter_Widget extends WP_Widget {
	
	function Pyre_Social_Counter_Widget()
	{
		$widget_ops = array('classname' => 'pyre_social_counter', 'description' => 'Show number of RSS subscribes, twitter followers and facebook fans.');

		$control_ops = array('id_base' => 'pyre_social_counter-widget');

		$this->WP_Widget('pyre_social_counter-widget', 'Avenue Theme: Social Counter', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}		
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if(get_option('pyre_feedburner')) {
			$rss = get_option('pyre_feedburner');
		} else {
			$rss = get_bloginfo('rss2_url');
		}
		?>
		
		<div class="social-item">
			<a href='<?php echo $rss; ?>'><img src="<?php bloginfo('template_directory'); ?>/images/rss-big.png" alt="RSS"  width='32' height='32' /></a>
			
			<?php
			if(get_option('pyre_feedburner')) {
				$interval = 43200;
				
				if($_SERVER['REQUEST_TIME'] > get_option('pyre_feedburner_cache_time')) {
					@$api = wp_remote_get('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=' . get_option('pyre_feedburner'));
					@$xml = new SimpleXmlElement($api['body'], LIBXML_NOCDATA);
					@$feedburner_followers = (string) $xml->feed->entry['circulation'];
					
					if($feedburner_followers >= 1) {
						update_option('pyre_feedburner_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
						update_option('pyre_feedburner_followers', $feedburner_followers);
					}
				}
			}
			?>
			
			<?php if(get_option('pyre_feedburner_followers')): ?>
			<span class="social-count"><?php echo get_option('pyre_feedburner_followers'); ?></span>
			<span class="social-descrip"><?php _e('Subscribers', 'Avenue'); ?></span>
			<?php else: ?>
			<span class="social-count"><?php _e('Subscribe', 'Avenue'); ?></span>
			<span class="social-descrip"><?php _e('to RSS Feed', 'Avenue'); ?></span>
			<?php endif; ?>
		</div>
		
		<?php if(get_option('pyre_twitter_id')): ?>
		<div class="social-item">
			<a href='http://twitter.com/<?php echo get_option('pyre_twitter_id'); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/twitter-big.png" alt="Twitter"  width='32' height='32' /></a>
			<?php
			$interval = 3600;
			
			if($_SERVER['REQUEST_TIME'] > get_option('pyre_twitter_cache_time')) {
				@$api = wp_remote_get('http://twitter.com/statuses/user_timeline/' . get_option('pyre_twitter_id') . '.json');
				@$json = json_decode($api['body']);
				
				if(@$api['headers']['x-ratelimit-remaining'] >= 1) {
					update_option('pyre_twitter_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
					update_option('pyre_twitter_followers', $json[0]->user->followers_count);
				}
			}
			?>
			<span class="social-count"><?php echo get_option('pyre_twitter_followers'); ?></span>
			<span class="social-descrip"><?php _e('Followers', 'Avenue'); ?></span>
		</div>
		<?php endif; ?>
		
		<?php if(get_option('pyre_facebook_id')): ?>
		<div class="social-item last">
			<?php
			$interval = 3600;
			
			if($_SERVER['REQUEST_TIME'] > get_option('pyre_facebook_cache_time')) {
				@$api = wp_remote_get('http://graph.facebook.com/' . get_option('pyre_facebook_id'));
				@$json = json_decode($api['body']);
				
				if($json->likes >= 1) {
					update_option('pyre_facebook_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
					update_option('pyre_facebook_followers', $json->likes);
					update_option('pyre_facebook_link', $json->link);
				}
			}
			?>
			
			<a href='<?php echo get_option('pyre_facebook_link'); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/facebook-big.png" alt="Facebook"  width='32' height='32' /></a>
			
			<span class="social-count"><?php echo get_option('pyre_facebook_followers'); ?></span>
			<span class="social-descrip"><?php _e('Fans', 'Avenue'); ?></span>
		</div>
		<?php endif; ?>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
	}

	function form($instance)
	{
	}
}
?>