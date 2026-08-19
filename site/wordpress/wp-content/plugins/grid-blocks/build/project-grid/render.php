<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$initial_visible = $attributes['postsPerPage'] ?? 12;

$query = new WP_Query( array(
	'post_type'      => 'projekt',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$categories = get_terms( array(
	'taxonomy'   => 'projekt_kategoria',
	'hide_empty' => true,
) );
if ( is_wp_error( $categories ) ) {
	$categories = array();
}

$total = (int) $query->post_count;

// term->count reflects published projekt posts only, since projekt_kategoria
// is registered exclusively for the projekt post type.
$category_counts = array( 'all' => $total );
foreach ( $categories as $term ) {
	$category_counts[ $term->slug ] = (int) $term->count;
}

$context = array(
	'filterCategory'      => 'all',
	'visibleCount'        => $initial_visible,
	'initialVisibleCount' => $initial_visible,
	'categoryCounts'      => $category_counts,
);
?>
<div <?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="grid/project-grid"
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<div class="mb-[26px] flex flex-col items-start gap-3 border-t-2 border-divider pt-3.5 sm:flex-row sm:items-baseline sm:justify-between">
		<div class="flex flex-wrap gap-x-[22px] gap-y-2">
			<button
				type="button"
				class="border-b-2 border-transparent pb-[3px] text-meta font-extrabold uppercase tracking-nav text-ink/50 aria-pressed:border-accent aria-pressed:text-accent"
				data-wp-on--click="actions.setCategory"
				data-wp-bind--aria-pressed="state.isPressed"
				<?php echo wp_interactivity_data_wp_context( array( 'filterValue' => 'all' ) ); ?>
			>Wszystkie</button>
			<?php foreach ( $categories as $term ) : ?>
				<button
					type="button"
					class="border-b-2 border-transparent pb-[3px] text-meta font-extrabold uppercase tracking-nav text-ink/50 aria-pressed:border-accent aria-pressed:text-accent"
					data-wp-on--click="actions.setCategory"
					data-wp-bind--aria-pressed="state.isPressed"
					<?php echo wp_interactivity_data_wp_context( array( 'filterValue' => $term->slug ) ); ?>
				><?php echo esc_html( ucfirst( $term->name ) ); ?></button>
			<?php endforeach; ?>
		</div>
		<span class="text-meta text-ink/45" data-wp-text="state.countLabel"><?php echo esc_html( min( $initial_visible, $total ) . " / {$total}" ); ?></span>
	</div>

	<div class="grid grid-flow-dense grid-cols-1 items-start gap-[30px] sm:grid-cols-2 sm:gap-x-5 sm:gap-y-8 md:grid-cols-3 md:gap-x-8 md:gap-y-10">
		<?php
		$index = 0;
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id         = get_the_ID();
			$kategoria_terms = get_the_terms( $post_id, 'projekt_kategoria' );
			$status_terms    = get_the_terms( $post_id, 'projekt_status' );
			$kategoria_slug  = ( $kategoria_terms && ! is_wp_error( $kategoria_terms ) ) ? $kategoria_terms[0]->slug : '';
			$kategoria_label = ( $kategoria_terms && ! is_wp_error( $kategoria_terms ) ) ? $kategoria_terms[0]->name : '';
			$status_label    = ( $status_terms && ! is_wp_error( $status_terms ) ) ? $status_terms[0]->name : '';
			$rok             = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
			$is_wide         = ( $index % 7 ) === 6;
			?>
			<a
				href="<?php the_permalink(); ?>"
				class="group block text-inherit<?php echo $is_wide ? ' sm:col-span-2' : ''; ?>"
				data-wp-class--hidden="state.isTileHidden"
				<?php echo wp_interactivity_data_wp_context( array( 'itemCategory' => $kategoria_slug, 'itemIndex' => $index ) ); ?>
			>
				<div class="relative aspect-tile w-full overflow-hidden bg-surface">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
						$thumb_attrs = array( 'class' => 'absolute inset-0 h-full w-full object-cover saturate-60 transition-[transform,filter] duration-700 ease-reveal group-hover:scale-[1.03] group-hover:saturate-100' );
						// The first tile is the LCP candidate on this page — hint the
						// browser to prioritize fetching it over everything else.
						if ( 0 === $index ) {
							$thumb_attrs['fetchpriority'] = 'high';
						}
						the_post_thumbnail( 'large', $thumb_attrs );
						?>
					<?php endif; ?>
					<div class="absolute inset-0 bg-accent opacity-0 mix-blend-multiply transition-opacity duration-450 group-hover:opacity-28"></div>
				</div>
				<div class="mt-3 flex items-baseline justify-between gap-4 border-t-2 border-divider pt-2.5">
					<span class="text-tile font-extrabold uppercase transition-colors group-hover:text-accent"><?php the_title(); ?></span>
					<?php if ( $rok ) : ?><span class="whitespace-nowrap text-label uppercase tracking-nav text-ink/50"><?php echo esc_html( $rok ); ?></span><?php endif; ?>
				</div>
				<?php if ( $kategoria_label || $status_label ) : ?>
					<div class="mt-1 flex flex-wrap items-baseline gap-x-2 text-meta uppercase tracking-[0.1em] text-ink/45">
						<?php if ( $kategoria_label ) : ?><span><?php echo esc_html( $kategoria_label ); ?></span><?php endif; ?>
						<?php if ( $status_label ) : ?><span><?php echo esc_html( $status_label ); ?></span><?php endif; ?>
					</div>
				<?php endif; ?>
			</a>
			<?php
			$index++;
		endwhile;
		wp_reset_postdata();
		?>
		<?php if ( $total > $initial_visible ) : ?>
			<button
				type="button"
				class="flex w-full items-end pt-1.5 sm:aspect-tile sm:pt-0"
				data-wp-on--click="actions.toggleShowMore"
				data-wp-class--hidden="state.isShowMoreHidden"
			><span class="border-b-2 border-accent pb-1 text-meta font-extrabold uppercase tracking-[0.16em] text-accent sm:translate-y-3.5" data-wp-text="state.showMoreLabel">Pokaż więcej projektów</span></button>
		<?php endif; ?>
	</div>
</div>
