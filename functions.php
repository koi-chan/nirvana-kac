<?php
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

# 部員紹介のカスタム投稿タイプ・カスタムタクソノミーを設定する
add_action('init', 'register_cpt_members_introduction');
function register_cpt_members_introduction() {

	$labels = array(
		'name' => __('部員紹介', 'members_introduction'),
		'singular_name' => __('部員紹介', 'members_introduction'),
		'add_new' => __('新規追加', 'members_introduction'),
		'add_new_item' => __('部員を追加する', 'members_introduction'),
		'edit_item' => __('部員紹介編集', 'members_introduction'),
		'new_item' => __('新規', 'members_introduction'),
		'view_item' => __('閲覧', 'members_introduction'),
		'search_items' => __('部員検索', 'members_introduction'),
		'not_found' => __('部員が見つかりません', 'members_introduction'),
		'not_found_in_trash' => __('ゴミ箱にはありません', 'members_introduction'),
		'parent_item_colon' => __('親メンバー', 'members_introduction'),
		'menu_name' => __('部員紹介', 'members_introduction'),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'supports' => array(
			'title',
			'thumbnail',
			'custom-fields',
			'author',
			'revisions'
		),
		'taxonomies' => array('members_introduction_taxonomy'),

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

	$args_taxonomy = array(
		'labels' => array(
			'name' => __('学年・身分', 'members_introduction_taxonomy'),
			'all_items' => __('学年・身分一覧', 'members_introduction_taxonomy'),
			'add_new_item' => __('新規学年・身分を追加', 'members_introduction_taxonomy'),
		),
		'hierarchical' => true,
		'show_admin_column' => true,
		'public' => true
	);

	register_post_type('members_introduction', $args);
	register_taxonomy('members_introduction_taxonomy', 'members_introduction', $args_taxonomy);
}

add_theme_support('post-thumbnails', array('members_introduction'));
set_post_thumbnail_size(200, 200, true);


# 部員紹介の投稿記事一覧で、カスタムタクソノミーでの絞り込み検索が出来るようにする
add_action('restrict_manage_posts', 'add_post_taxonomy_restrict_filter');
function add_post_taxonomy_restrict_filter() {
	global $post_type;
	if('members_introduction' == $post_type):

		$add_taxonomy = $_GET['members_introduction_taxonomy'];
		$terms = get_terms('members_introduction_taxonomy');
?>
<select name="members_introduction_taxonomy">
	<option value="">すべての学年・身分</option>
	<?php foreach($terms as $term): ?>
	<option value="<?= $term->slug; ?>"<?php if($add_taxonomy == $term->slug) echo ' selected="selected"'; ?>><?= $term->name; ?></option>
	<?php endforeach; ?>
</select>
<?php
	endif;
}


# $output_name [Boolean] 表に名前を出力するか
function member_profile($output_name = false) {
	global $post;
?>
<table>
<?php if($output_name): ?>
<tr>
	<th colspan="2"><?= get_the_title($post->ID); ?></th>
</tr>
<?php endif; ?>
<tr>
<?php if(get_post_meta($post->ID, 'picture', true)): ?>
	<td class="thumbnail"><img src="<?= wp_get_attachment_url(get_post_meta($post->ID, 'picture', true)) ?>" alt="<?= get_the_title($post->ID) ?>" /></td>
	<td><dl>
<?php else: ?>
	<td colspan="2"><dl>
<?php endif; ?>
<?php if ( $output_name ) : ?>
		<?php if(get_post_meta($post->ID, 'position', true) != 'なし'): ?>
		<dt>部内学年</dt>
		<dd>
		<?= strtoupper(get_the_terms($post->ID, 'members_introduction_taxonomy')[0]->name); ?>
		</dd>
		<?php endif; ?>
<?php else : ?>
		<?php if(get_post_meta($post->ID, 'position', true) != 'なし'): ?>
		<dt>役職</dt>
		<dd>
		<?= get_post_meta($post->ID, 'position', true); ?>
		</dd>
		<?php endif; ?>

		<dt>部内学年</dt>
		<dd>
		<?= strtoupper(get_the_category($post->ID)[0]->cat_name); ?>
		</dd>
<?php endif; ?>

		<dt>大学学籍</dt>
		<dd>
		<?php
			$gakubu = get_post_meta($post->ID, 'gakubu', true);
			if ( $gakubu == '自由記述' ) $gakubu = get_post_meta($post->ID, 'gakubu_freewriting', true);
			echo $gakubu;
		?>
		<?php
			$grade = get_post_meta($post->ID, 'grade', true);
			if ( $grade == 0 ) $grade = get_grade(
				get_post_meta($post->ID, 'enter_year', true),
				get_post_meta($post->ID, 'enter_semester', true),
				get_post_meta($post->ID, 'stay_count', true)
			);
			echo $grade;
		?>年
		</dd>

		<dt>趣味</dt>
		<dd>
		<?= get_post_meta($post->ID, 'hobby', true); ?>
		</dd>
		<dt>山岳部とは(一言)</dt>
		<dd>
		<?= get_post_meta($post->ID, 'whats club', true); ?>
		</dd>
		<dt>目標</dt>
		<dd>
		<?= get_post_meta($post->ID, 'aim', true); ?>
		</dd>
	</dl></td>
</tr>
</table>
<?php
}


# 入学年度と現在の日付から現在の学年を求める
# param [Integer] $year 入学年度
# param [String] $semester_text 入学季節(学期)
# param [Integer] $stay 留年・留学回数
# return [Integer]
function get_grade($year, $semester_text, $stay = 0) {
	$today = getdate();

	# 入力値チェック
	# 入学年度は数字で、かつ今年よりも小さい必要がある
	if ( !is_numeric($year) || $year > $today['year'] ) {
		return -1;
	}
	# 入学季節(学期)は '春', '秋' のみ許容する
	# 問題ない値だった場合、処理用の値にする
	switch ( $semester_text ) {
	case '春':
		$semester = true;
		break;
	case '秋':
		$semester = false;
		break;
	default:
		return -1;
	}
	# 留年・留学回数は数字で、かつ0回以上8回以下である必要がある
	if ( !is_numeric($stay) || $stay < 0 || $stay > 8 ) {
		return -1;
	}

	# 学年を計算する
	if ( $today['mon'] < 4 ) {
		# 年は越したが年度は前年のまま
		$grade = $today['year'] - $year;
	} elseif ( $today['mon'] > 8 ) {
		# 9月以降は入学学期に限らず +1 年生
		$grade = $today['year'] - $year + 1;
	} else {
		$grade = $today['year'] - $year;
		# 4～8 月は、春入学の場合 +1 する
		if ( $semester ) $grade++;
	}

	# 留年・留学して学年が遅れた場合
	return $grade - $stay;
}
?>
