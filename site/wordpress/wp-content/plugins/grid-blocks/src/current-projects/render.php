<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$label = $attributes['label'] ?? '';

// Editor-picked and ordered on the options page (Ustawienia → Aktualne
// projekty), not queried automatically — see the ACF field group in
// grid-core/acf-fields.php for why (status alone doesn't give the client
// real control over what shows here).
$project_ids = get_field( 'aktualne_projekty', 'option' );
if ( empty( $project_ids ) ) {
	return;
}

$project_ids = array_filter( $project_ids, function ( $post_id ) {
	return 'publish' === get_post_status( $post_id );
} );
if ( empty( $project_ids ) ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'mt-14 md:mt-16' ) ); ?>>
	<?php if ( $label ) : ?>
		<h2 class="mb-6 text-[22px] font-extrabold uppercase tracking-[-0.03em] md:text-[26px]"><?php echo esc_html( $label ); ?></h2>
	<?php endif; ?>
	<div class="grid grid-cols-1 gap-7 sm:grid-cols-2 md:grid-cols-3 md:gap-8">
		<?php foreach ( $project_ids as $post_id ) : ?>
			<?php
			$status_terms = get_the_terms( $post_id, 'projekt_status' );
			$status_label = ( $status_terms && ! is_wp_error( $status_terms ) ) ? $status_terms[0]->name : '';
			?>
			<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="group block text-inherit">
				<div class="relative aspect-wide w-full overflow-hidden bg-surface">
					<?php if ( has_post_thumbnail( $post_id ) ) : ?>
						<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'absolute inset-0 h-full w-full object-cover saturate-60 transition-[transform,filter] duration-700 ease-reveal group-hover:scale-[1.03] group-hover:saturate-100' ) ); ?>
					<?php endif; ?>
					<div class="absolute inset-0 bg-accent opacity-0 mix-blend-multiply transition-opacity duration-450 group-hover:opacity-28"></div>
				</div>
				<div class="mt-3 flex items-baseline justify-between gap-4 border-t-2 border-divider pt-2.5">
					<span class="text-tile font-extrabold uppercase transition-colors group-hover:text-accent"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
					<?php if ( $status_label ) : ?>
						<span class="whitespace-nowrap text-label uppercase tracking-nav text-ink/50"><?php echo esc_html( $status_label ); ?></span>
					<?php endif; ?>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</div>
