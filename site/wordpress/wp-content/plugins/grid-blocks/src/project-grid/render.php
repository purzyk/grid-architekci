<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$query = new WP_Query( array(
	'post_type'      => 'projekt',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
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

// How many tiles show before "Pokaż więcej projektów" — counted within the
// active category, not across the whole list. Everything past it is still
// rendered and merely hidden, so the links stay in the markup for crawlers,
// and since those images are lazy-loaded the tiles nobody expands cost no
// image traffic.
$limit = 12;

$context = array(
	'filterCategory' => 'all',
	'categoryCounts' => $category_counts,
	'showAll'        => false,
	'limit'          => $limit,
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
		<span class="text-meta text-ink/45" data-wp-text="state.countLabel"><?php echo esc_html( min( $limit, $total ) . ' / ' . $total ); ?></span>
	</div>

	<div class="grid grid-flow-dense grid-cols-1 items-start gap-[30px] sm:grid-cols-2 sm:gap-x-5 sm:gap-y-8 md:grid-cols-3 md:gap-x-8 md:gap-y-10">
		<?php
		$index          = 0;
		$category_index = array();
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id         = get_the_ID();
			$kategoria_terms = get_the_terms( $post_id, 'projekt_kategoria' );
			$status_terms    = get_the_terms( $post_id, 'projekt_status' );
			$kategoria_slug  = ( $kategoria_terms && ! is_wp_error( $kategoria_terms ) ) ? $kategoria_terms[0]->slug : '';
			$kategoria_label = ( $kategoria_terms && ! is_wp_error( $kategoria_terms ) ) ? $kategoria_terms[0]->name : '';
			$status_label    = ( $status_terms && ! is_wp_error( $status_terms ) ) ? $status_terms[0]->name : '';
			$rok             = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';

			if ( ! isset( $category_index[ $kategoria_slug ] ) ) {
				$category_index[ $kategoria_slug ] = 0;
			}
			$index_category = $category_index[ $kategoria_slug ]++;

			// What's rendered here is the state the page opens in — every
			// category, collapsed. view.js recomputes both flags off the
			// filtered index as soon as a category is picked; matching that
			// starting point server-side keeps the first paint reflow-free.
			$is_wide   = ( $index % 7 ) === 0;
			$is_hidden = $index >= $limit;
			?>
			<a
				href="<?php the_permalink(); ?>"
				class="group block text-inherit sm:[&.is-wide]:col-span-2<?php echo $is_wide ? ' is-wide' : ''; ?><?php echo $is_hidden ? ' hidden' : ''; ?>"
				data-wp-class--hidden="state.isTileHidden"
				data-wp-class--is-wide="state.isTileWide"
				<?php
				echo wp_interactivity_data_wp_context( array(
					'itemCategory'  => $kategoria_slug,
					'indexAll'      => $index,
					'indexCategory' => $index_category,
				) );
				?>
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
						// WordPress's default `sizes` guess ("100vw" below 1024px) assumes
						// the image fills the viewport — wrong here, since the real box is
						// one grid-cols-3 tile (or two, for the wide tile every 7th item).
						// Without an accurate hint the first few tiles (which render eager,
						// before WP's native sizes="auto" self-correction can apply to
						// lazy-loaded images) fetch a much bigger file than they display.
						//
						// Filtering can move a tile between the wide and narrow slot and
						// this hint doesn't follow it, which is fine: `sizes` only counts
						// at fetch time, and anything already on screen when a filter is
						// clicked has been fetched already.
						$thumb_attrs['sizes'] = $is_wide
							? '(min-width: 1360px) 843px, (min-width: 900px) calc(66.67vw - 64px), (min-width: 620px) calc(100vw - 56px), calc(100vw - 36px)'
							: '(min-width: 1360px) 405px, (min-width: 900px) calc(33.33vw - 48px), (min-width: 620px) calc(50vw - 38px), calc(100vw - 36px)';
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
					<div class="mt-1 flex flex-wrap items-baseline gap-x-2 text-meta uppercase tracking-[0.1em] text-ink/75">
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

		<?php // A grid cell of its own, like the mock — it sits in the flow after
			  // the last tile rather than being centred under the grid. ?>
		<button
			type="button"
			class="flex w-full cursor-pointer items-end pt-1 text-left sm:aspect-tile sm:pt-0<?php echo $total <= $limit ? ' hidden' : ''; ?>"
			data-wp-on--click="actions.toggleShowAll"
			data-wp-class--hidden="state.isMoreHidden"
		>
			<span class="border-b-2 border-accent pb-1 text-meta font-extrabold uppercase tracking-kicker text-accent sm:translate-y-3.5" data-wp-text="state.moreLabel">Pokaż więcej projektów</span>
		</button>
	</div>
</div>
