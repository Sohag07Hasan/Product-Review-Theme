<?php
add_action('widgets_init', 'pr_pyre_latest_reviews_load_widgets');

function pr_pyre_latest_reviews_load_widgets()
{
	register_widget('pr_Pyre_Latest_Reviews_Widget');
}

class pr_Pyre_Latest_Reviews_Widget extends WP_Widget {
	
	function pr_Pyre_Latest_Reviews_Widget()
	{
		$widget_ops = array('classname' => 'pyre_latest_reviews', 'description' => '');

		$control_ops = array('id_base' => 'pr_pyre_latest_reviews-widget');

		$this->WP_Widget('pr_pyre_latest_reviews-widget', 'Product Review: Latest Reviews', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title = $instance['title'];
		$posts = $instance['posts'];
		
		echo $before_widget;
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'meta_query' => array(
				array(
					'key' => 'pyre_overall_score',
					'value' => '0.5',
					'compare' => '>='
				)
			),
		));
		?>
					
		<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
		<div class="block-item-small">
			<?php
			if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
				$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
			} else {
				$icon = '';
			}
			?>
			<?php if(pr_has_post_thumbnail()): ?>
			<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
			<?php else: ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
			<?php endif; ?>
			<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php echo string_limit_words(get_the_title(), 7); ?></a></h2>
			<span class="block-meta"><?php the_time(get_option('date_format')); ?>, <?php comments_popup_link(); ?></span>
			<?php if(
				get_post_meta(get_the_ID(), 'pyre_overall_score', true)
			): ?>
			<div class="post-review-widget">
				<?php if(get_post_meta(get_the_ID(), 'pyre_overall_score', true)): ?>
				<span><img src="<?php pr_bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta(get_the_ID(), 'pyre_overall_score', true); ?>.png" alt="" /></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['posts'] = $new_instance['posts'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Latest Reviews', 'posts' => 3);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>
