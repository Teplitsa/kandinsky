<?php
/**
 * Template part for displaying posts
 *
 * @package Kandinsky
 */

global $post;

$post_index = $wp_query->current_post;
$post_type  = get_post_type( get_the_ID() );
$post_class = array( 'flex-cell flex-md-6 knd-post-item knd-entry' );
if ( 0 === $post_index || 1 === $post_index ) {
	$post_class['tpl'] = 'tpl-related-post';
} else {
	$post_class['tpl']    = 'tpl-post';
	$post_class['col-lg'] = 'flex-lg-4';
}

$post_class = apply_filters( 'knd_post_class', $post_class, $post_type, $post_index );

?>

<article <?php post_class( $post_class );?>>
	<div class="knd-post-item__inner">
		<a href="<?php the_permalink(); ?>" class="thumbnail-link">
			<div class="entry-preview"><?php echo knd_post_thumbnail( get_the_ID(), 'post-thumbnail' );?></div>
			<div class="entry-data">
				<?php the_title('<h2 class="entry-title">','</h2>'); ?>
			</div>
		</a>
		<?php if ( 'post' === get_post_type() || 'project' === get_post_type() ) { ?>
			<div class="entry-meta"><?php echo knd_posted_on( $post, array( 'cat_link' => true ) ); ?></div>
		<?php } ?>
	</div>
</article>
