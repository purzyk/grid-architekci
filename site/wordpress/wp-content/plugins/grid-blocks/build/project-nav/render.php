<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$post_id = $block->context['postId'] ?? get_the_ID();

if ( ! $post_id ) {
	return;
}

// The project grid is manually ordered (drag-and-drop via Simple Custom
// Post Order, menu_order ASC) rather than by date, so core's
// get_previous_post()/get_next_post() (which only understand chronological
// adjacency) can't be used here — they'd silently drift out of sync with
// whatever order the client actually dragged the projects into. Walk the
// same ordered ID list the grid itself queries by instead, so prev/next
// always matches what's actually shown there.
$ordered_ids = get_posts( array(
	'post_type'      => 'projekt',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'fields'         => 'ids',
) );

$index = array_search( $post_id, $ordered_ids, true );
$prev  = ( false !== $index && $index > 0 ) ? get_post( $ordered_ids[ $index - 1 ] ) : null;
$next  = ( false !== $index && $index < count( $ordered_ids ) - 1 ) ? get_post( $ordered_ids[ $index + 1 ] ) : null;
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'grid grid-cols-1 items-center gap-5 border-t-2 border-divider pb-10 pt-6 md:grid-cols-[1fr_auto_1fr] md:gap-8 md:pb-10 md:pt-[30px]' ) ); ?>>
	<?php if ( $prev ) : ?>
		<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="text-ink">
			<div class="mb-1.5 text-[10px] uppercase tracking-[0.16em] text-ink/50"><?php esc_html_e( 'Poprzedni', 'grid' ); ?></div>
			<div class="text-[19px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]"><?php echo esc_html( get_the_title( $prev ) ); ?></div>
		</a>
	<?php else : ?>
		<div></div>
	<?php endif; ?>

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-[10px] uppercase tracking-[0.16em] text-accent"><?php esc_html_e( 'Wszystkie projekty', 'grid' ); ?></a>

	<?php if ( $next ) : ?>
		<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="text-ink md:text-right">
			<div class="mb-1.5 text-[10px] uppercase tracking-[0.16em] text-ink/50"><?php esc_html_e( 'Następny', 'grid' ); ?></div>
			<div class="text-[19px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]"><?php echo esc_html( get_the_title( $next ) ); ?></div>
		</a>
	<?php else : ?>
		<div></div>
	<?php endif; ?>
</nav>
