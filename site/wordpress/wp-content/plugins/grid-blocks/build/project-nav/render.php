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

global $post;
$original_post = $post;
$post = get_post( $post_id );
setup_postdata( $post );

$prev = get_previous_post();
$next = get_next_post();

wp_reset_postdata();
$post = $original_post;
?>
<nav <?php echo get_block_wrapper_attributes( array( 'class' => 'grid grid-cols-1 items-center gap-5 border-t-2 border-divider pb-10 pt-6 md:grid-cols-[1fr_auto_1fr] md:gap-8 md:pb-10 md:pt-[30px]' ) ); ?>>
	<?php if ( $prev ) : ?>
		<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="text-ink">
			<div class="mb-1.5 text-[10px] uppercase tracking-[0.16em] text-ink/50">Poprzedni</div>
			<div class="text-[19px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]"><?php echo esc_html( get_the_title( $prev ) ); ?></div>
		</a>
	<?php else : ?>
		<div></div>
	<?php endif; ?>

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-[10px] uppercase tracking-[0.16em] text-accent">Wszystkie projekty</a>

	<?php if ( $next ) : ?>
		<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="text-ink md:text-right">
			<div class="mb-1.5 text-[10px] uppercase tracking-[0.16em] text-ink/50">Następny</div>
			<div class="text-[19px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]"><?php echo esc_html( get_the_title( $next ) ); ?></div>
		</a>
	<?php else : ?>
		<div></div>
	<?php endif; ?>
</nav>
