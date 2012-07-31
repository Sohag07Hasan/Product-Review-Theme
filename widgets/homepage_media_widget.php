<?php
add_action('widgets_init', 'pyre_homepage_media_load_widgets');

function pyre_homepage_media_load_widgets()
{
	register_widget('Pyre_Homepage_Media_Widget');
}

class Pyre_Homepage_Media_Widget extends WP_Widget {
	
	function Pyre_Homepage_Media_Widget()
	{
		$widget_ops = array('classname' => 'pyre_homepage_media', 'description' => 'Homepage media slider widget.');

		$control_ops = array('id_base' => 'pyre_homepage_media-widget');

		$this->WP_Widget('pyre_homepage_media-widget', 'Avenue Theme: Home Carousel', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{	
		extract($args);
		
		$title = $instance['title'];
		$post_type = $instance['post_type'];
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		
		echo $before_widget;
		?>
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'cat' => $categories,
		));
		if($recent_posts->found_posts >= 3):
		?>
		<div class="block full">
			
			<h3><a href="<?php echo get_category_link($categories); ?>"><?php echo $title; ?></a> <span class="arrows">&raquo;</span></h3>
			
			<div class="wrapper-carousel">
			
				<div class="anyClass" id="anyClass_<?php echo $this->number; ?>">
					<ul>
						<?php
						$post_types = get_post_types();
						unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
						
						if($post_type == 'all') {
							$post_type_array = $post_types;
						} else {
							$post_type_array = $post_type;
						}
						
						if($categories != 'all') {
							$categories_array = array($categories);
						}
						?>
						
						<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
						<?php if(has_post_thumbnail()): ?>
						<?php
						if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
							$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
						} else {
							$icon = '';
						}
						?>
						<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'media-thumb'); ?>
						<li><?php echo $icon; ?><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  width='140' height='90' /></a></li>
						<?php else: ?>
						<li><?php echo $icon; ?><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/timthumb.php?src=<?php bloginfo('template_directory'); ?>/images/thumbnail.png&w=140&h=90"  width='140' height='90' /></a></li>
						<?php endif; ?>
						<?php endwhile; ?>
					</ul>
				</div>
				
				<a class="prev" id="prev_<?php echo $this->number; ?>" href="#"><span>prev</span></a>
				<a class="next" id="next_<?php echo $this->number; ?>" href="#"><span>next</span></a>

			</div>
		</div>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = $new_instance['post_type'];
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Media', 'post_type' => 'all', 'categories' => 'all', 'posts' => 6);
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