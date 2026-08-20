<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$label = $attributes['label'] ?? '';
$note  = $attributes['note'] ?? '';

$query = new WP_Query( array(
	'post_type'      => 'publikacja',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'meta_key'       => 'rok',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
) );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( $label || $note ) : ?>
		<div class="mt-16 grid grid-cols-1 items-end gap-6 border-b-2 border-divider pb-5 md:mt-[76px] md:gap-10 lg:grid-cols-[minmax(0,1fr)_420px] lg:gap-16">
			<?php if ( $label ) : ?><h2 class="m-0 hyphens-auto break-words text-[clamp(32px,4.4vw,56px)] font-extrabold uppercase leading-[0.96] tracking-[-0.04em]"><?php echo esc_html( $label ); ?></h2><?php endif; ?>
			<?php if ( $note ) : ?><p class="m-0 text-[14px] leading-[1.6] text-ink/70 lg:mb-1.5"><?php echo esc_html( $note ); ?></p><?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="mt-8 grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-4 md:grid-cols-6 md:gap-x-6 md:gap-y-[30px]">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$post_id = get_the_ID();
			$typ     = function_exists( 'get_field' ) ? get_field( 'typ', $post_id ) : '';
			$rok     = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
			$link    = function_exists( 'get_field' ) ? get_field( 'link', $post_id ) : '';
			$cover   = get_the_post_thumbnail_url( $post_id, 'medium' );
			?>
			<?php
			// Only wrap in a real link when there's somewhere to go — an
			// <a href="#"> still gets a pointer cursor and hover affordance
			// even though clicking it does nothing.
			$tag = $link ? 'a' : 'div';
			?>
			<<?php echo $tag; ?>
				<?php if ( $link ) : ?>href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"<?php endif; ?>
				class="<?php echo $link ? 'group ' : ''; ?>block text-ink"
			>
				<div class="aspect-cover w-full overflow-hidden bg-surface">
					<?php if ( $cover ) : ?>
						<div class="h-full w-full bg-contain bg-center bg-no-repeat saturate-55 transition-[transform,filter] duration-600 ease-reveal group-hover:scale-[1.06] group-hover:saturate-100" style="background-image:url(<?php echo esc_url( $cover ); ?>)"></div>
					<?php endif; ?>
				</div>
				<div class="mt-2.5 border-t-2 border-divider pt-2 text-[13px] font-extrabold leading-[1.25] tracking-[-0.01em]"><?php the_title(); ?></div>
				<?php if ( $typ || $rok ) : ?>
					<div class="mt-1 flex justify-between gap-2 text-[10px] uppercase tracking-[0.08em] text-ink/50">
						<?php if ( $typ ) : ?><span><?php echo esc_html( $typ ); ?></span><?php endif; ?>
						<?php if ( $rok ) : ?><span><?php echo esc_html( $rok ); ?></span><?php endif; ?>
					</div>
				<?php endif; ?>
			</<?php echo $tag; ?>>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
