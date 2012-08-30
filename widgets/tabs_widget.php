<?php
add_action('widgets_init', 'pr_pyre_tabs_load_widgets');

function pr_pyre_tabs_load_widgets()
{
	register_widget('pr_Pyre_Tabs_Widget');
}

class pr_Pyre_Tabs_Widget extends WP_Widget {
	
	function pr_Pyre_Tabs_Widget()
	{
		$widget_ops = array('classname' => 'pyre_tabs', 'description' => 'Popular posts, comments and tags tabbed widget.');

		$control_ops = array('id_base' => 'pr_pyre_tabs-widget');

		$this->WP_Widget('pr_pyre_tabs-widget', 'Product Review: Tabs', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$posts = $instance['posts'];
		$comments = $instance['comments'];
		$tags_count = $instance['tags'];
		$show_popular_posts = isset($instance['show_popular_posts']) ? 'true' : 'false';
		$show_comments = isset($instance['show_comments']) ? 'true' : 'false';
		$show_tags = isset($instance['show_tags']) ? 'true' : 'false';
		
		echo $before_widget;
		
		if($title) {
			echo $before_title.$title.$after_title;
		}		
		?>
		<!-- BEGIN WIDGET -->
		<div class="tabs-wrapper">
			
			<ul class="tabs">
				<?php if($show_popular_posts == 'true'): ?><li><a href="#tab1"><?php _e('Popular'); ?></a></li><?php endif; ?>
				<?php if($show_comments == 'true'): ?><li><a href="#tab2"><?php _e('Comments'); ?></a></li><?php endif; ?>
				<?php if($show_tags == 'true'): ?><li><a href="#tab3"><?php _e('Tags'); ?></a></li><?php endif; ?>
			</ul>
			
			<div class="tabs_container">
			
				<?php if($show_popular_posts == 'true'): ?>
				<div id="tab1" class="tab_content">
					<?php
					$popular_posts = new WP_Query('showposts='.$posts.'&orderby=comment_count&order=DESC');
					if($popular_posts->have_posts()): ?>
						<?php while($popular_posts->have_posts()): $popular_posts->the_post(); ?>
						<?php
						if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
							$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
						} else {
							$icon = '';
						}
						?>
						<div class="block-item-small">
							<?php if(pr_has_post_thumbnail()): ?>
							<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta"><?php the_time(get_option('date_format')); ?>, <?php comments_popup_link(); ?></span>
						</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<?php if($show_comments == 'true'): ?>
				<div id="tab2" class="tab_content">
					<?php
					$number = $instance['comments'];
					global $wpdb;
					$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
					$the_comments = $wpdb->get_results($recent_comments);
					foreach ($the_comments as $comment) { ?>
						<div class="block-item-small">
							<?php echo get_avatar($comment, '50'); ?>
							<h2><?php echo strip_tags($comment->comment_author); ?> says:</h2>
							<a class="comment-text-side" href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> on <?php echo $comment->post_title; ?>"><?php echo string_limit_words(strip_tags($comment->com_excerpt), 12); ?>...</a>
						</div>
					<?php } ?>
				</div>
				<?php endif; ?>
				
				<?php if($show_tags == 'true'): ?>
				<div id="tab3" class="tab_content">
					<p class='tag_cloud'>
					<?php
					$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => $tags_count));
					foreach ((array) $tags as $tag) {
					?>
					<?php echo '<a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a>';	?>
					<?php } ?>
					</p>
				</div>
				<?php endif; ?>
				
			</div>
		
		</div>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['posts'] = $new_instance['posts'];
		$instance['comments'] = $new_instance['comments'];
		$instance['tags'] = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_comments'] = $new_instance['show_comments'];
		$instance['show_tags'] = $new_instance['show_tags'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('posts' => 3, 'comments' => '3', 'tags' => 20, 'show_popular_posts' => 'on', 'show_comments' => 'on', 'show_tags' =>  'on');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('comments'); ?>">Number of comments:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" value="<?php echo $instance['comments']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>">Number of tags:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" value="<?php echo $instance['tags']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_popular_posts'], 'on'); ?> id="<?php echo $this->get_field_id('show_popular_posts'); ?>" name="<?php echo $this->get_field_name('show_popular_posts'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_popular_posts'); ?>">Show popular posts</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_comments'], 'on'); ?> id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_comments'); ?>">Show comments</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_tags'], 'on'); ?> id="<?php echo $this->get_field_id('show_tags'); ?>" name="<?php echo $this->get_field_name('show_tags'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_tags'); ?>">Show tags</label>
		</p>
	<?php }
}
?>
