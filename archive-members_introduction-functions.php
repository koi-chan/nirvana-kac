<?php 
function wp_loop($query_args) {
$loop = new WP_Query($query_args);

if ( $loop->have_posts() ) while ( $loop->have_posts() ) : $loop->the_post();
?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php cryout_post_title_hook(); ?>

<?php #					<div class="entry-content"> ?>
						<?php member_profile(true); ?>
						<?php //the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nirvana' ), 'after' => '</span></div>' ) ); ?>
<?php #					</div><!-- .entry-content --> ?>
<?php /*
<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'nirvana_author_bio_avatar_size', 80 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h2><?php echo __('About','nirvana').' '.esc_attr( get_the_author() ); ?></h2>
							<?php the_author_meta( 'description' ); ?>
							<div id="author-link">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( 'View all posts by ','nirvana').'%s <span class="meta-nav">&rarr;</span>', get_the_author() ); ?>
								</a>
							</div><!-- #author-link	-->
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>
<?php */ ?>
					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'nirvana' ), '<span class="edit-link"><i class="icon-edit icon-metas"></i> ', '</span>' ); cryout_post_footer_hook(); ?>
					</footer><!-- .entry-meta -->
				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

<?php
endwhile; // end of the loop.
wp_reset_postdata();
} // function wp_loop
?>
