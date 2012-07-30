<?php get_header(); ?>

			<!-- BEGIN MAIN -->
			<div id="main">
				
				<?php $fake_loop = new WP_Query('showposts=5'); while($fake_loop->have_posts()): $fake_loop->the_post(); ?>
				<?php endwhile; ?>
			
				<?php if(get_option('pyre_featured_slider') == 'On' && get_option('pyre_featured_tags')): ?>
				<?php
				$post_types = get_post_types();
				unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
				
				$featured_posts = new WP_Query(array(
					'post_type' => $post_types,
					'showposts' => 5,
					'tag' => get_option('pyre_featured_tags')
				));
				?>
				<!-- BEGIN WRAPPER-FEATURED -->
				<div id="wrapper-featured">
				
					<div id="wrapper-slides">
					
						<div id="slider" class="nivoSlider">
							<?php while($featured_posts->have_posts()): $featured_posts->the_post(); ?>
								
								<?php if(pr_has_post_thumbnail()): ?>
									<?php $image = pr_get_post_thumbnail_src(0, $post->ID); ?>									
									<?php $image_thumb = pr_get_post_thumbnail_src(2, $post->ID); ?>									
									<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" rel="<?php echo $image_thumb; ?>" title="#htmlcaption_<?php the_ID(); ?>" width='516' height='340' /></a>
								<?php else: ?>
									<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php get_option('siteurl'); ?>/wp-content/themes/Avenue/timthumb.php?src=<?php get_option('siteurl'); ?>/wp-content/themes/Avenue/images/thumbnail.png&w=516&h=340" alt="<?php the_title(); ?>" title="#htmlcaption_<?php the_ID(); ?>"  rel="<?php get_option('siteurl'); ?>/wp-content/themes/Avenue/timthumb.php?src=<?php get_option('siteurl'); ?>/wp-content/themes/Avenue/images/thumbnail.png&w=70&h=60"  width='516' height='340'/></a>
								<?php endif; ?>
								
							<?php endwhile; ?>
							
						</div>
						
						<?php while($featured_posts->have_posts()): $featured_posts->the_post(); ?>
						<div class="nivo-html-caption" id="htmlcaption_<?php the_ID(); ?>">
							<h1><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h1>
							<p><?php echo string_limit_words(get_the_excerpt(), 15); ?> <span class="block-arrows"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'>&raquo;</a></span></p>
						</div>
						<?php endwhile; ?>
						
					</div>
					
				</div>
				<!-- END WRAPPER-FEATURED -->
				<?php endif; ?>
				
				<!-- BEGIN HOMEPAGE BLOCKS -->
				<?php if(get_option('pyre_homepage_style') == 'magazine'): ?>
					<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage')): ?>
					<div class="block full">
						
						<h3><?php _e('Recent Posts', 'Avenue'); ?> <span class="arrows">&raquo;</span></h3>
						
						<?php
						$recent_posts = new WP_Query(array(
							'showposts' => 4,
						));
						?>
						<?php
						$big_count = round(4 / 4);
						if(!$big_count) { $big_count = 1; }
						?>
						<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
						<?php
						if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
							$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
						} else {
							$icon = '';
						}
						?>
						<?php if($counter <= $big_count): ?>
						<div class="block-item-big">
							<?php if(has_post_thumbnail()): ?>
							<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'widget-image'); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" width='290' height='160' /></a><?php echo $icon; ?></div>
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/timthumb.php?src=<?php bloginfo('template_directory'); ?>/images/thumbnail.png&w=290&h=160" alt="<?php the_title(); ?>" width='290' height='160' /></a><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
						</div>
						<?php else: ?>
						<div class="block-item-small">
							<?php if(has_post_thumbnail()): ?>
							<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'widget-image-thumb'); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/timthumb.php?src=<?php bloginfo('template_directory'); ?>/images/thumbnail.png&w=60&h=60" alt="<?php the_title(); ?>" width='50' height='50' /></a><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
						</div>
						<?php endif; ?>
						<?php $counter++; endwhile; ?>
					
					</div>
					<?php endif; ?>
					<?php endif; ?>
					
					<?php if(get_option('pyre_homepage_style') == 'blog'): ?>
					<div class="block archive">
					
						<h3>
							<?php _e('Recent Posts', 'Avenue'); ?>
							<span class="arrows">&raquo;</span>
						</h3>
						
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<div id="post-<?php the_ID(); ?>" <?php post_class('block-item-big'); ?>>
							<?php
							if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
								$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
							} else {
								$icon = '';
							}
							?>
							<?php if(has_post_thumbnail()): ?>
							<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'widget-image'); ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" width='290' height='160' /></a><?php echo $icon; ?></div>
							<?php else: ?>
							<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/timthumb.php?src=<?php bloginfo('template_directory'); ?>/images/thumbnail.png&w=290&h=160" alt="<?php the_title(); ?>" width='290' height='160' /></a><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
							<span class="block-meta">
								<span class="heading-author"><?php the_author(); ?></span> 
								<span class="heading-date"><?php the_time('F j, Y'); ?></span>
								<span class="heading-comments"><?php comments_popup_link('0','1','%'); ?></span>
							</span>
							<p><?php echo string_limit_words(get_the_excerpt(), 34); ?></p><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>' class="readmore"><?php _e('Read More', 'Avenue'); ?> <span class="block-arrows">&raquo;</span></a>
						</div>
						<?php endwhile; endif; ?>
						
						<?php kriesi_pagination($pages = '', $range = 2); ?>
					</div>
					
					<div class='block full' style='padding-top: 20px;'>
						<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage (Blog Style)')): ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<!-- END HOMEPAGE BLOCKS -->
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>
