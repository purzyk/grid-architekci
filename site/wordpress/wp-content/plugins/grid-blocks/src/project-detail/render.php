<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$post_id = $block->context['postId'] ?? get_the_ID();

if ( ! $post_id || ! function_exists( 'get_field' ) ) {
	return;
}

$lead    = get_field( 'lead', $post_id );
$opis    = get_field( 'opis', $post_id );
$galeria = get_field( 'galeria', $post_id );
$rysunek_id = get_field( 'rysunek_zagospodarowania', $post_id );
$metryka = get_field( 'metryka', $post_id ) ?: array();

// Rok and status aren't part of the metryka group (rok is its own field,
// status is a taxonomy) but the mocks show them as metric columns too —
// merge them in so the grid matches what the design actually shows.
$rok = get_field( 'rok', $post_id );
if ( $rok ) {
	$metryka = array( 'rok' => $rok ) + $metryka;
}
$status_terms = get_the_terms( $post_id, 'projekt_status' );
if ( $status_terms && ! is_wp_error( $status_terms ) ) {
	$metryka = array( 'status' => $status_terms[0]->name ) + $metryka;
}

$metric_labels = array(
	'rok'         => 'Rok',
	'status'      => 'Status',
	'klient'      => 'Klient',
	'zakres'      => 'Zakres',
	'zespol'      => 'Zespół',
	'konstrukcja' => 'Konstrukcja',
	'instalacje'  => 'Instalacje',
	'wykonawca'   => 'Wykonawca',
	'autor_zdjec' => 'Autor zdjęć',
);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( $lead ) : ?>
		<p class="max-w-measure text-lead"><?php echo esc_html( $lead ); ?></p>
	<?php endif; ?>

	<?php if ( $opis ) : ?>
		<div class="mt-5 max-w-measure text-body text-ink/70 [&_p]:mb-4 [&_p:last-child]:mb-0"><?php echo wp_kses_post( $opis ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $galeria ) ) : ?>
		<div class="mt-10 flex flex-col gap-5">
			<?php foreach ( $galeria as $img ) : ?>
				<?php
				// ACF's gallery field normally returns full image arrays, but
				// falls back to plain attachment IDs if the field was ever
				// saved as raw IDs (e.g. via update_field() with an ID array,
				// as the migration seed script does) — handle both.
				if ( is_array( $img ) ) {
					echo wp_get_attachment_image( $img['ID'], 'large', false, array(
						'class' => 'block w-full h-auto',
						'alt'   => $img['alt'] ?? '',
					) );
				} else {
					echo wp_get_attachment_image( $img, 'large', false, array( 'class' => 'block w-full h-auto' ) );
				}
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $rysunek_id ) : ?>
		<div class="mt-10 bg-surface p-6">
			<?php echo wp_get_attachment_image( $rysunek_id, 'large', false, array( 'class' => 'block w-full h-auto' ) ); ?>
		</div>
	<?php endif; ?>

	<?php
	$has_metrics = is_array( $metryka ) && count( array_filter( $metryka ) ) > 0;
	if ( $has_metrics ) :
		?>
		<div class="mt-10 grid grid-cols-2 gap-[26px] border-t-2 border-divider px-[18px] py-[26px] sm:grid-cols-3 md:grid-cols-5 md:px-10">
			<?php foreach ( $metric_labels as $key => $label ) : ?>
				<?php if ( empty( $metryka[ $key ] ) ) continue; ?>
				<div>
					<div class="mb-1.5 h-3 text-label uppercase tracking-kicker text-ink/50"><?php echo esc_html( $label ); ?></div>
					<div class="text-body-sm leading-[1.4]"><?php echo esc_html( $metryka[ $key ] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
