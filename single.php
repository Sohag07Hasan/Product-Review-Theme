<?php get_header(); ?>

			<!-- BEGIN MAIN -->
			<div id="main">
			
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php if(get_option('pyre_posts_navigation') == 'On'): ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_post('%', '&larr; Previous Post', 'no'); ?></div>
					<div class="alignright"><?php next_post('%', 'Next Post &rarr;', 'no'); ?></div>
				</div>
				<?php endif; ?>
				
				<div class="post-heading">
					<h1><?php the_title(); ?></h1>
					<span class="heading-author"><?php the_author(); ?></span> 
					<span class="heading-date"><?php the_date(); ?></span>
					<span class="heading-comments"><?php comments_popup_link('0','1','%'); ?></span>
				</div>
								
				
				<div class="post-entry">
					<?php if(
					(
						get_post_meta($post->ID, 'pyre_overall_score', true) ||
						(get_post_meta($post->ID, 'pyre_critera_1', true) && get_post_meta($post->ID, 'pyre_critera_1_score', true)) ||
						(get_post_meta($post->ID, 'pyre_critera_2', true) && get_post_meta($post->ID, 'pyre_critera_2_score', true)) ||
						(get_post_meta($post->ID, 'pyre_critera_3', true) && get_post_meta($post->ID, 'pyre_critera_3_score', true)) ||
						(get_post_meta($post->ID, 'pyre_critera_4', true) && get_post_meta($post->ID, 'pyre_critera_4_score', true)) ||
						(get_post_meta($post->ID, 'pyre_critera_5', true) && get_post_meta($post->ID, 'pyre_critera_5_score', true))
					)
					): ?>
					<div class="post-review">
						<?php if(get_post_meta($post->ID, 'pyre_overall_score', true)): ?>
						<div class="overall-score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/big_<?php echo get_post_meta($post->ID, 'pyre_overall_score', true); ?>.png" alt="" /></div>
						<?php endif; ?>
						<ul>
							<?php if(get_post_meta($post->ID, 'pyre_critera_1', true)): ?>
							<li><?php echo get_post_meta($post->ID, 'pyre_critera_1', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'pyre_critera_1_score', true); ?>.png" alt="" /></span></li>
							<?php endif; ?>
							<?php if(get_post_meta($post->ID, 'pyre_critera_2', true)): ?>
							<li><?php echo get_post_meta($post->ID, 'pyre_critera_2', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'pyre_critera_2_score', true); ?>.png" alt="" /></span></li>
							<?php endif; ?>
							<?php if(get_post_meta($post->ID, 'pyre_critera_3', true)): ?>
							<li><?php echo get_post_meta($post->ID, 'pyre_critera_3', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'pyre_critera_3_score', true); ?>.png" alt="" /></span></li>
							<?php endif; ?>
							<?php if(get_post_meta($post->ID, 'pyre_critera_4', true)): ?>
							<li><?php echo get_post_meta($post->ID, 'pyre_critera_4', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'pyre_critera_4_score', true); ?>.png" alt="" /></span></li>
							<?php endif; ?>
							<?php if(get_post_meta($post->ID, 'pyre_critera_5', true)): ?>
							<li><?php echo get_post_meta($post->ID, 'pyre_critera_5', true); ?> <span class="score"><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta($post->ID, 'pyre_critera_5_score', true); ?>.png" alt="" /></span></li>
							<?php endif; ?>
						</ul>
					</div>
					<?php endif; ?>
					
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					
					<div class='clear'></div>
				</div>
				
				<?php if(
					get_option('pyre_twitter') == 'On' ||
					get_option('pyre_facebook') == 'On' ||
					get_option('pyre_digg') == 'On' ||
					get_option('pyre_stumbleupon') == 'On' ||
					get_option('pyre_reddit') == 'On' ||
					get_option('pyre_tumblr') == 'On' ||
					get_option('pyre_email') == 'On' ||
					get_option('pyre_google') == 'On'
				): ?>
				<div class='post-share'>
					<?php if(get_option('pyre_twitter') == 'On'): ?>
					<div class='twitter-share share-widget'>
						<a href="http://twitter.com/share" class="twitter-share-button" data-text='<?php the_title(); ?>' data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_facebook') == 'On'): ?>
					<div class='facebook-share share-widget'>
						<iframe src="http://www.facebook.com/plugins/like.php?app_id=149766198425277&amp;href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:60px;" allowTransparency="true"></iframe>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_digg') == 'On'): ?>
					<div class='digg-share share-widget'>
						<script type="text/javascript">
						(function() {
						var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
						s.type = 'text/javascript';
						s.async = true;
						s.src = 'http://widgets.digg.com/buttons.js';
						s1.parentNode.insertBefore(s, s1);
						})();
						</script>
						<a class="DiggThisButton DiggMedium" href="http://digg.com/submit?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>"></a>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_stumbleupon') == 'On'): ?>
					<div class='stumbleupon-share share-widget'>
						<script src="http://www.stumbleupon.com/hostedbadge.php?s=5"></script>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_reddit') == 'On'): ?>
					<div class='reddit-share share-widget'>
						<script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_tumblr') == 'On'): ?>
					<div class='tumblr-share share-widget'>
						<a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:62px; height:20px; background:url('http://platform.tumblr.com/v1/share_2.png') top left no-repeat transparent;">Share on Tumblr</a>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_email') == 'On'): ?>
					<div class='email-share share-widget'>
						<a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><img src='<?php bloginfo('template_url'); ?>/images/email-share.png' alt='Email Share' /></a>
					</div>
					<?php endif; ?>
					<?php if(get_option('pyre_google') == 'On'): ?>
					<div class='google-share share-widget'>
						<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
						<g:plusone size="tall"></g:plusone>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<div class="post-meta">
					<?php if(get_option('pyre_tags') == 'On'): ?> 
					<div class="post-meta-tags"><?php the_tags('<strong>' . __("TAGS", 'Avenue') . '</strong> <span class="meta-arrow">&raquo;</span> ',', '); ?></div>
					<?php endif; ?>
					
					<?php if(get_option('pyre_categories') == 'On'): ?> 
					<div class="post-meta-cats"><strong><?php _e('POSTED IN', 'Avenue'); ?></strong> <span class="meta-arrow">&raquo;</span> <?php the_category(', ') ?></div>
					<?php endif; ?>	
				</div>
				
				<?php if(get_option('pyre_author') == 'On'): ?> 
				<div class="post-author">
					<?php echo get_avatar(get_the_author_meta('email'), '70'); ?>
					<h5><em><?php _e('About the author:', 'Avenue'); ?></em> <?php the_author_link(); ?> <small><?php _e('View all posts by'); ?> <?php the_author_posts_link(); ?></small></h5>
					<div class="author-descrip"><?php the_author_meta("description"); ?></div>
					<?php if(get_the_author_meta('twitter') || get_the_author_meta('facebook')): ?>
					<p>
						<br />
						<?php if(get_the_author_meta('twitter')): ?>
						<a href='http://twitter.com/<?php echo get_the_author_meta('twitter'); ?>'>Twitter</a>
						<?php endif; ?>
						
						<?php if(get_the_author_meta('twitter') || get_the_author_meta('facebook')): ?>
						-
						<?php endif; ?>
						
						<?php if(get_the_author_meta('facebook')): ?>
						<a href='http://facebook.com/<?php echo get_the_author_meta('facebook'); ?>'>Facebook</a>
						<?php endif; ?>
					</p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
							
				<?php 
					if(function_exists('related_posts')){
						global $yarpp;
						$related = $yarpp->display_related(null, array('post_type'=>'post'), false, true);
															
					}
					else{
						$tags = get_the_tags();
						if($tags){
							$related = get_related_posts($post->ID, $tags);
						}			
					}
										
				?>
				
				
				<?php if(!empty($related)) : ?>
												
				<div class='homepage-widget'>
					<div class="block full">
						
						<h3>Related <span class="arrows">&raquo;</span></h3>
							
							<?php while($related->have_posts()): $related->the_post(); ?>
								<?php if(pr_has_post_thumbnail()): ?>
								<?php
								if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
									$icon = '<span class="' . get_post_format($post->ID) . '-icon"></span>';
								} else {
									$icon = '';
								}
								?>
								<?php $image = pr_get_post_thumbnail_src(1, $post->ID); ?>
								<div class="related-item">
								<?php echo $icon; ?><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" width='134' height='90' /></a>
								<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
								</div>
								<?php else: ?>
								<div class="related-item">
								<?php echo $icon; ?><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php pr_bloginfo('template_directory'); ?>/timthumb.php?src=<?php pr_bloginfo('template_directory'); ?>/images/thumbnail.png&w=134&h=90" alt="<?php the_title(); ?>" width='134' height='90' /></a>
								<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
								</div>
								<?php endif; ?>
							<?php endwhile; ?>
					
					</div>
				</div>
				<?php endif; ?>
							
				
				<?php wp_reset_query(); ?>
				
				<div id="comments">
				
					<?php comments_template(); ?>
					
				</div>
				
				<?php endwhile; endif; ?>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>
