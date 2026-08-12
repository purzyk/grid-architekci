<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$label = $attributes['label'] ?? '';
$note  = $attributes['note'] ?? '';

$query = new WP_Query( array(
	'post_type'      => 'zespol',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
) );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="flex flex-wrap items-baseline justify-between gap-2 border-b-2 border-divider pb-3.5">
		<?php if ( $label ) : ?>
			<h2 class="text-[10px] uppercase tracking-[0.2em] text-ink/50"><?php echo esc_html( $label ); ?></h2>
		<?php endif; ?>
		<?php if ( $note ) : ?>
			<span class="text-[11px] text-ink/45"><?php echo esc_html( $note ); ?></span>
		<?php endif; ?>
	</div>
	<div class="grid grid-cols-1 gap-8 pt-7 sm:grid-cols-2 md:grid-cols-3">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$stanowisko = function_exists( 'get_field' ) ? get_field( 'stanowisko' ) : '';
			$funkcja    = function_exists( 'get_field' ) ? get_field( 'funkcja' ) : '';
			$bio        = function_exists( 'get_field' ) ? get_field( 'bio' ) : '';
			$role       = trim( implode( ' · ', array_filter( array( $stanowisko, $funkcja ) ) ) );
			$photo_url  = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
			?>
			<div>
				<div
					class="aspect-portrait w-full bg-surface bg-cover bg-center saturate-60"
					<?php if ( $photo_url ) : ?>style="background-image:url(<?php echo esc_url( $photo_url ); ?>)"<?php endif; ?>
				></div>
				<div class="mt-3.5 border-t-2 border-divider pt-2.5 text-[20px] font-extrabold tracking-[-0.02em] md:text-[22px]"><?php the_title(); ?></div>
				<?php if ( $role ) : ?>
					<div class="mt-1 text-[12px] uppercase tracking-[0.06em] text-accent"><?php echo esc_html( $role ); ?></div>
				<?php endif; ?>
				<?php if ( $bio ) : ?>
					<p class="m-0 mt-2.5 text-[14px] leading-[1.6] text-ink/70"><?php echo esc_html( $bio ); ?></p>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<?php wp_reset_postdata(); ?>
