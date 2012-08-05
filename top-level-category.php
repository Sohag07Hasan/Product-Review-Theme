<?php get_header(); ?>
		
	<!-- BEGIN MAIN -->
	<div id="main">
		
		<?php
			$term = get_queried_object();
		//	var_dump($term);
			$child_categories = get_categories(array('type'=>'post', 'child_of'=>$term->term_id, 'orderby'=>'count', 'order'=>'desc'));
						
			$widget_groups = array();
			$group_index = -1;
			
			foreach($child_categories as $key => $child_category){				
				if(fmod($key, 2) == 0){
					$group_index ++ ;
				}
				
				$widget_groups[$group_index][] = $child_category;												
			}
					
			
		?>
		<div class="block">
			<h3>
				<?php echo $term->name; ?>
				<span class="arrows">&raquo;&raquo;</span>
			</h3>
		</div>
		
		<!-- original widget contents goes here -->
		<?php foreach($widget_groups as $key => $widgets) : ?>
			
			<div class="homepage-widget">
				
				<?php foreach($widgets as $wkey => $widget) : ?>
									
					<div class="<?php echo ($wkey == 0) ? "block half" : "block half last" ; ?>">
					
					<h3>
						<a href="<?php echo get_category_link($widget->term_id); ?>"><?php echo $widget->name; ?></a>  <span class="arrows">&raquo;</span>
					</h3>
					
					<?php
						$recent_posts = new WP_Query(array(
							'posts_per_page' => 4,
							'cat' => $widget->term_id,
						));
					?>
					
					<?php
						$counter = 1;
						while($recent_posts->have_posts()): $recent_posts->the_post();
					?>
					
					<?php if($counter == 1) : ?>
					
						<div class="block-item-big">
							<?php if(pr_has_post_thumbnail()): ?>
							<?php $image = pr_get_post_thumbnail_src(0, $post->ID); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?>
							
							<p class="reviewClass">
								<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
									<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>
									<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" /> 
									<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>
								</a>
							</p>
							
							</div>				
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=290&h=160" alt="<?php the_title(); ?>"  width='290' height='160' /></a><?php echo $icon; ?></div>
							<?php endif; ?>										
							
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
							<?php if($show_excerpt == 'true'): ?><p><?php echo string_limit_words(get_the_excerpt(), 15); ?> ...</p><?php endif; ?>
						</div> <!-- end block-item-big -->
					
					<?php else: ?>
						<div class="block-item-small">
							<?php if(pr_has_post_thumbnail()): ?>
							<?php $image = pr_get_post_thumbnail_src(2, $post->ID); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>"  width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta">
								
								
								<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>
									<?php $img_Review = pr_get_avg_rating_image($post->ID); ?>						
									<img src="<?php echo $img_Review?>" alt="<?php echo $img_Review;?>" />
									<span class="priceClass"><?php echo pr_get_min_price($post->ID); ?></span>					
								</a>
							
							
							</span>
					</div> <!-- block-item-small -->
					
				<?php endif; ?>
				<?php $counter++; endwhile; ?>
					
					</div>
				<?php endforeach; ?>
				
			</div> <!-- homepage-widget -->			
		
		<?php endforeach; ?>
		
	
	</div>
		<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>
