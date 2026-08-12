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
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-project-grid' ) ); ?>
	data-wp-interactive="grid/project-grid"
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<div class="grid-project-grid__filters">
		<div class="grid-project-grid__filter-buttons">
			<button
				type="button"
				class="grid-project-grid__filter-btn"
				data-wp-on--click="actions.setCategory"
				data-wp-bind--aria-pressed="state.isPressed"
				<?php echo wp_interactivity_data_wp_context( array( 'filterValue' => 'all' ) ); ?>
			>Wszystkie</button>
			<?php foreach ( $categories as $term ) : ?>
				<button
					type="button"
					class="grid-project-grid__filter-btn"
					data-wp-on--click="actions.setCategory"
					data-wp-bind--aria-pressed="state.isPressed"
					<?php echo wp_interactivity_data_wp_context( array( 'filterValue' => $term->slug ) ); ?>
				><?php echo esc_html( ucfirst( $term->name ) ); ?></button>
			<?php endforeach; ?>
		</div>
		<span class="grid-project-grid__count" data-wp-text="state.countLabel"><?php echo esc_html( min( $initial_visible, $total ) . " / {$total}" ); ?></span>
	</div>

	<div class="grid-project-grid__grid">
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
				class="grid-project-grid__tile<?php echo $is_wide ? ' grid-project-grid__tile--wide' : ''; ?>"
				data-wp-class--is-hidden="state.isTileHidden"
				<?php echo wp_interactivity_data_wp_context( array( 'itemCategory' => $kategoria_slug, 'itemIndex' => $index ) ); ?>
			>
				<div class="grid-project-grid__thumb">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'grid-project-grid__img' ) ); ?>
					<?php endif; ?>
					<div class="grid-project-grid__mask"></div>
				</div>
				<div class="grid-project-grid__meta-row">
					<span class="grid-project-grid__title"><?php the_title(); ?></span>
					<?php if ( $rok ) : ?><span class="grid-project-grid__year"><?php echo esc_html( $rok ); ?></span><?php endif; ?>
				</div>
				<?php if ( $kategoria_label || $status_label ) : ?>
					<div class="grid-project-grid__meta-sub">
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
				class="grid-project-grid__show-more"
				data-wp-on--click="actions.toggleShowMore"
				data-wp-bind--hidden="state.isShowMoreHidden"
			><span data-wp-text="state.showMoreLabel">Pokaż więcej projektów</span></button>
		<?php endif; ?>
	</div>
</div>
