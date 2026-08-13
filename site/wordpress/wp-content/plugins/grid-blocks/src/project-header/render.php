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

$kategoria_terms = get_the_terms( $post_id, 'projekt_kategoria' );
$status_terms    = get_the_terms( $post_id, 'projekt_status' );
$rok             = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
$lead            = function_exists( 'get_field' ) ? get_field( 'lead', $post_id ) : '';

$kicker_parts = array();
if ( $kategoria_terms && ! is_wp_error( $kategoria_terms ) ) {
	$kicker_parts[] = $kategoria_terms[0]->name;
}
if ( $status_terms && ! is_wp_error( $status_terms ) ) {
	$kicker_parts[] = $status_terms[0]->name;
}
if ( $rok ) {
	$kicker_parts[] = $rok;
}
$kicker = implode( ' · ', $kicker_parts );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid grid-cols-1 items-end gap-6 pb-5 pt-9 sm:pt-10 md:gap-10 md:pb-[22px] md:pt-[52px] lg:grid-cols-[minmax(0,1fr)_440px] lg:gap-14' ) ); ?>>
	<div>
		<?php if ( $kicker ) : ?>
			<div class="mb-3.5 text-[10px] uppercase tracking-[0.2em] text-accent"><?php echo esc_html( $kicker ); ?></div>
		<?php endif; ?>
		<h1 class="m-0 hyphens-auto break-words text-[clamp(30px,5.6vw,72px)] font-extrabold uppercase leading-[0.96] tracking-[-0.035em]"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
	</div>
	<?php if ( $lead ) : ?>
		<p class="m-0 text-[16px] leading-[1.45] md:text-[17px] lg:mb-1.5"><?php echo esc_html( $lead ); ?></p>
	<?php endif; ?>
</div>
