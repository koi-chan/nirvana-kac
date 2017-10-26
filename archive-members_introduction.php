<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Cryout Creations
 * @subpackage nirvana
 * @since nirvana 0.5
 */
require_once 'archive-members_introduction-functions.php';

get_header();?>

		<section id="container" class="<?php echo nirvana_get_layout_class(); ?>">
			<div id="content" role="main">
			<?php cryout_before_content_hook(); ?>
			<h1 class="entry-title">部員紹介</h1>
			<div class="entry-content">
			
<?php
$query_args = array(
	'nopaging' => true,
	'posts_per_page' => -1,
	'post_type' => 'members_introduction',
	'meta_key' => 'position',
);
foreach(array('主将', '副将', '主務', '副務') as $value) {
	echo "<h2>".$value."</h2>";
	$query_args['meta_value'] = $value;
	wp_loop($query_args);
}

$query_args['meta_value'] = 'なし';
$query_args['tax_query'] = array(
	array(
		'taxonomy' => 'category',
		'field' => 'name',
	)
);
foreach(array('r4', 'r3', 'r2', 'r1') as $value) {
/*	echo "<h2>".strtoupper($value)."</h2>"; */
	$query_args['tax_query'][0]['terms'] = $value;
	wp_loop($query_args);
}
?>
			</div><!-- div.entry_content -->

			<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
			<?php nirvana_get_sidebar(); ?>
		</section><!-- #container -->

<?php get_footer(); ?>
