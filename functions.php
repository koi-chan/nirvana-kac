<?php
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}


add_action( 'init', 'register_cpt_members_introduction' );

function register_cpt_members_introduction() {

	$labels = array(
		'name' => __( '部員紹介', 'members_introduction' ),
		'singular_name' => __( '部員紹介', 'members_introduction' ),
		'add_new' => __( '新規追加', 'members_introduction' ),
		'add_new_item' => __( '部員を追加する', 'members_introduction' ),
		'edit_item' => __( '部員紹介編集', 'members_introduction' ),
		'new_item' => __( '新規', 'members_introduction' ),
		'view_item' => __( '閲覧', 'members_introduction' ),
		'search_items' => __( '部員検索', 'members_introduction' ),
		'not_found' => __( '部員が見つかりません', 'members_introduction' ),
		'not_found_in_trash' => __( 'ゴミ箱にはありません', 'members_introduction' ),
		'parent_item_colon' => __( '親メンバー', 'members_introduction' ),
		'menu_name' => __( '部員紹介', 'members_introduction' ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'supports' => array('title', 'thumbnail', 'custom-fields' ),
		'taxonomies' => array( 'category' ),

		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'members_introduction', $args );
}

add_theme_support('post-thumbnails', array('members_introduction'));
set_post_thumbnail_size(200, 200, true);

# $output_name [Boolean] 表に名前を出力するか
function member_profile($output_name = false) {
	global $post;
?>
<table>
<?php if($output_name): ?>
<tr>
	<th colspan="2"><?php echo get_the_title($post->ID); ?></th>
</tr>
<?php endif; ?>
<tr>
<?php /*	<td><?php the_post_thumbnail(); ?><img src="" /></td> */ ?>
	<td colspan="2"><dl>
		<?php if(get_post_meta($post->ID, 'position', true) != 'なし'): ?><dt>役職</dt>
		<dd><?php echo get_post_meta($post->ID, 'position', true); ?></dd><?php endif; ?>

		<dt>学部・学年</dt>
		<dd>
		<?php echo get_post_meta($post->ID, 'gakubu', true); ?>
		<?php echo get_post_meta($post->ID, 'grade', true); ?>年
		</dd>

		<dt>趣味</dt>
		<dd>
		<?php echo get_post_meta($post->ID, 'hobby', true); ?>
		</dd>
		<dt>山岳部とは(一言)</dt>
		<dd>
		<?php echo get_post_meta($post->ID, 'whats club', true); ?>
		</dd>
		<dt>目標</dt>
		<dd>
		<?php echo get_post_meta($post->ID, 'aim', true); ?>
		</dd>
	</dl></td>
</tr>
</table>
<?php
}
?>
