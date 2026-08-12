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
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-project-detail' ) ); ?>>
	<?php if ( $lead ) : ?>
		<p class="grid-project-detail__lead"><?php echo esc_html( $lead ); ?></p>
	<?php endif; ?>

	<?php if ( $opis ) : ?>
		<div class="grid-project-detail__body"><?php echo wp_kses_post( $opis ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $galeria ) ) : ?>
		<div class="grid-project-detail__gallery">
			<?php foreach ( $galeria as $img ) : ?>
				<img
					class="grid-project-detail__photo"
					src="<?php echo esc_url( $img['sizes']['large'] ?? $img['url'] ); ?>"
					alt="<?php echo esc_attr( $img['alt'] ?? '' ); ?>"
					<?php if ( ! empty( $img['caption'] ) ) : ?>title="<?php echo esc_attr( $img['caption'] ); ?>"<?php endif; ?>
				/>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $rysunek_id ) : ?>
		<div class="grid-project-detail__siteplan">
			<?php echo wp_get_attachment_image( $rysunek_id, 'large', false, array( 'class' => 'grid-project-detail__siteplan-img' ) ); ?>
		</div>
	<?php endif; ?>

	<?php
	$has_metrics = is_array( $metryka ) && count( array_filter( $metryka ) ) > 0;
	if ( $has_metrics ) :
		?>
		<div class="grid-project-detail__metrics">
			<?php foreach ( $metric_labels as $key => $label ) : ?>
				<?php if ( empty( $metryka[ $key ] ) ) continue; ?>
				<div class="grid-project-detail__metric">
					<div class="grid-project-detail__metric-label"><?php echo esc_html( $label ); ?></div>
					<div class="grid-project-detail__metric-value"><?php echo esc_html( $metryka[ $key ] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
