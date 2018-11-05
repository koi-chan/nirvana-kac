<?php 
function wp_loop($query_args) {
$loop = new WP_Query($query_args);

if ( $loop->have_posts() ) :
	if ( $query_args['meta_value'] == 'なし' ) {
		echo "<h2>".strtoupper($query_args['tax_query'][0]['terms'])."</h2>";
	}
while ( $loop->have_posts() ) : $loop->the_post();
?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php cryout_post_title_hook(); ?>

<?php #					<div class="entry-content"> ?>
						<?php member_profile(true); ?>
						<?php //the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'nirvana' ), 'after' => '</span></div>' ) ); ?>
<?php #					</div><!-- .entry-content --> ?>
					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'nirvana' ), '<span class="edit-link"><i class="icon-edit icon-metas"></i> ', '</span>' ); cryout_post_footer_hook(); ?>
					</footer><!-- .entry-meta -->
				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

<?php
endwhile; // end of the loop.
endif;
wp_reset_postdata();
} // function wp_loop
?>
