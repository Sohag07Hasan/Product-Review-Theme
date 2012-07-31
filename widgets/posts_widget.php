<?php
add_action('widgets_init', 'pr_pyre_posts_load_widgets');

function pr_pyre_posts_load_widgets()
{
	register_widget('pr_Pyre_Posts_Widget');
}

class pr_Pyre_Posts_Widget extends WP_Widget {
	
	function pr_Pyre_Posts_Widget()
	{
		$widget_ops = array('classname' => 'pyre_posts', 'description' => '');

		$control_ops = array('id_base' => 'pr_pyre_posts-widget');

		$this->WP_Widget('pr_pyre_posts-widget', 'Product Review: Recent Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		$images = true;
		$rating = true;
		
		echo $before_widget;
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		
		<?php
		$post_types = get_post_types();
		unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
		
		if($post_type == 'all') {
			$post_type_array = $post_types;
		} else {
			$post_type_array = $post_type;
		}
		?>
		
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'cat' => $categories,
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
			<?php if($images && pr_has_post_thumbnail()): ?>
			<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
			<?php else: ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
			<?php endif; ?>
			<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
			<span class="block-meta"><?php the_time(get_option('date_format')); ?>, <?php comments_popup_link(); ?></span>
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
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		$instance['show_images'] = true;
		$instance['show_rating'] = true;
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 3);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>
