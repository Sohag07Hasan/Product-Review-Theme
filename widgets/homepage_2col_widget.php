<?php
add_action('widgets_init', 'pr_pyre_homepage_2col_load_widgets');

function pr_pyre_homepage_2col_load_widgets()
{
	register_widget('pr_Pyre_Homepage_2col_Widget');
}

class pr_Pyre_Homepage_2col_Widget extends WP_Widget {
	
	function pr_Pyre_Homepage_2col_Widget()
	{
		$widget_ops = array('classname' => 'pyre_homepage_2col', 'description' => 'Homepage 2-column recent posts widget.');

		$control_ops = array('id_base' => 'pr_pyre_homepage_2col-widget');

		$this->WP_Widget('pr_pyre_homepage_2col-widget', 'Product Review: Home 2-column', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$show_excerpt = isset($instance['show_excerpt']) ? 'true' : 'false';
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		$images = true;

		$title_2 = $instance['title_2'];
		$post_type_2 = 'all';
		$categories_2 = $instance['categories_2'];
		$posts_2 = $instance['posts_2'];
		$images_2 = true;
		
		echo $before_widget;
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
		
		<div class="block half">
			<h3><a href="<?php echo get_category_link($categories); ?>"><?php echo $title; ?></a>  <span class="arrows">&raquo;</span></h3>
			
			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts,
				'cat' => $categories,
				'orderby' => 'meta_value',
				'meta_key' => 'ReviewAZON_ReviewCount',
				'order' => 'DESC'
			)); 
			?>
			<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<?php
			if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
				$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
			} else {
				$icon = ''; 
			}
			?>
			<?php if($counter == 1): ?>
			<div class="block-item-big">
				<?php if($images && pr_has_post_thumbnail()): ?>
				<?php $image = pr_get_post_thumbnail_src(0, $post->ID); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?>
				
				<p class="reviewClass">
					<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
						<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>
						<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" />
						<span class="revewNumber"> (<?php echo pr_get_avg_rating(); ?>) </span> 
						<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>
					</a>
				</p>
				
						
				</div>
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=290&h=160" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?></div>				
				<?php endif; ?>
											
				<?php //do_action('pr_bigcustomerReview_Price', $post, array('width'=>290, 'height'=>160)); ?>
							
				<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php pr_the_title(34); ?></a></h2>
				<!--<span class="block-meta"><?php //the_time('F j, Y'); ?>, <?php //comments_popup_link(); ?></span> -->
				<?php if($show_excerpt == 'true'): ?><p><?php echo string_limit_words(get_the_excerpt(), 15); ?> ...</p><?php endif; ?>
			</div>
			<?php else: ?>
			<div class="block-item-small">
				<?php if($images && pr_has_post_thumbnail()): ?>
				<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?>
				
				</div>
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>				
				<?php endif; ?>
				<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php pr_the_title(34); ?></a></h2>
				<span class="block-meta">
					
					<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
						<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>						
						<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" />
						<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>					
					</a>
				
				</span>
			</div>
			<?php endif; ?>
			<?php $counter++; endwhile; ?>
			
		</div>
		
		<?php
		$post_types = get_post_types();
		unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
		
		if($post_type_2 == 'all') {
			$post_type_2_array = $post_types;
		} else {
			$post_type_2_array = $post_type;
		}
		?>
		
		<div class="block half last">
			<h3><a href="<?php echo get_category_link($categories_2); ?>"><?php echo $title_2; ?></a>  <span class="arrows">&raquo;</span></h3>

			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts_2,
				'cat' => $categories_2,
				'orderby' => 'meta_value',
				'meta_key' => 'ReviewAZON_ReviewCount',
				'order' => 'DESC'
			));
			?>			
			<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<?php
			if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
				$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
			} else {
				$icon = '';
			}
			?>
			<?php if($counter == 1): ?>
			<div class="block-item-big">
				<?php if($images && pr_has_post_thumbnail()): ?>
				<?php $image = pr_get_post_thumbnail_src(0, $post->ID); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?>
				
				<p class="reviewClass">
					<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
						<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>
						<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" />
						<span class="revewNumber"> (<?php echo pr_get_avg_rating(); ?>) </span> 
						<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>
					</a>
				</p>
				
				</div>				
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=290&h=160" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?></div>
				<?php endif; ?>										
				
				<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php pr_the_title(40); ?></a></h2>
				
				<!-- <span class="block-meta"><?php //the_time('F j, Y'); ?>, <?php //comments_popup_link(); ?></span> -->
				
				<?php if($show_excerpt == 'true'): ?><p><?php echo string_limit_words(get_the_excerpt(), 15); ?> ...</p><?php endif; ?>
			</div>
			<?php else: ?>
			<div class="block-item-small">
				<?php if($images && pr_has_post_thumbnail()): ?>
				<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
				<?php endif; ?>
				<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php pr_the_title(34); ?></a></h2>
				<span class="block-meta">
					
					
					<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
						<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>						
						<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" />
						<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>					
					</a>
				
				
				</span>
			</div>
			<?php endif; ?>
			<?php $counter++; endwhile; ?>
			
		</div>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['show_excerpt'] = $new_instance['show_excerpt'];
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		$instance['show_images'] = true;
		
		$instance['title_2'] = $new_instance['title_2'];
		$instance['post_type_2'] = 'all';
		$instance['categories_2'] = $new_instance['categories_2'];
		$instance['posts_2'] = $new_instance['posts_2'];
		$instance['show_images_2'] = true;
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('show_excerpt' => null, 'title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 4, 'title_2' => 'Recent Posts', 'post_type_2' => 'all', 'categories_2' => 'all', 'posts_2' => 4);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Show excerpt</label>
		</p>
		
		<h3>Column One</h3>
		
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
		
		<h3 style='margin-top: 40px;'>Column Two</h3>
		
		<p>
			<label for="<?php echo $this->get_field_id('title_2'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title_2'); ?>" name="<?php echo $this->get_field_name('title_2'); ?>" value="<?php echo $instance['title_2']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories_2'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories_2'); ?>" name="<?php echo $this->get_field_name('categories_2'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories_2']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories_2']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('posts_2'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts_2'); ?>" name="<?php echo $this->get_field_name('posts_2'); ?>" value="<?php echo $instance['posts_2']; ?>" />
		</p>
	<?php }
}
?>
