<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$limit = $attributes['limit'] ?? 3;

$query = new WP_Query( array(
	'post_type'      => 'nagroda',
	'post_status'    => 'publish',
	'posts_per_page' => $limit,
	'meta_key'       => 'rok',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'   => 'wyrozniona',
			'value' => '1',
		),
	),
) );

$variant_classes = array(
	'accent'  => 'bg-accent text-paper',
	'ink'     => 'bg-ink text-paper',
	'surface' => 'bg-surface text-ink',
);
$variants = array_keys( $variant_classes );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid grid-cols-1 gap-0.5 border-t-2 border-divider sm:grid-cols-3' ) ); ?>>
	<?php
	$index = 0;
	while ( $query->have_posts() ) :
		$query->the_post();
		$post_id = get_the_ID();
		$rok     = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
		$konkurs = function_exists( 'get_field' ) ? get_field( 'konkurs', $post_id ) : get_the_title();
		$wynik   = function_exists( 'get_field' ) ? get_field( 'wynik', $post_id ) : '';
		$opis    = function_exists( 'get_field' ) ? get_field( 'opis', $post_id ) : '';
		$related = function_exists( 'get_field' ) ? get_field( 'projekt_powiazany', $post_id ) : null;
		$related_id = is_array( $related ) && ! empty( $related ) ? $related[0] : null;
		$variant = $variants[ $index % count( $variants ) ];
		// Only wrap in a real link when there's a related project — no
		// pointer cursor, hover tint, or arrow when there's nothing to go to.
		$tag = $related_id ? 'a' : 'div';
		?>
		<<?php echo $tag; ?>
			<?php if ( $related_id ) : ?>href="<?php echo esc_url( get_permalink( $related_id ) ); ?>"<?php endif; ?>
			class="<?php echo $related_id ? 'group ' : ''; ?>relative block overflow-hidden px-6 pb-8 pt-7 md:px-7 md:pb-[34px] md:pt-[30px] <?php echo esc_attr( $variant_classes[ $variant ] ); ?>"
		>
			<div class="text-[38px] font-extrabold leading-none tracking-[-0.04em] md:text-[46px]"><?php echo esc_html( $rok ); ?></div>
			<?php if ( $wynik ) : ?><div class="mb-3.5 mt-3 text-[10px] uppercase tracking-[0.18em] opacity-75"><?php echo esc_html( $wynik ); ?></div><?php endif; ?>
			<div class="text-[20px] font-extrabold uppercase leading-[1.1] tracking-[-0.02em] md:text-[22px]"><?php echo esc_html( $konkurs ); ?></div>
			<?php if ( $opis ) : ?><p class="m-0 mt-2 text-[13px] leading-[1.5] opacity-80"><?php echo esc_html( $opis ); ?></p><?php endif; ?>
			<?php if ( $related_id ) : ?>
				<span aria-hidden="true" class="pointer-events-none absolute bottom-[26px] right-6 -translate-x-2 text-[22px] font-extrabold leading-none opacity-0 transition-[opacity,transform] duration-450 ease-reveal group-hover:translate-x-0 group-hover:opacity-90">→</span>
				<span aria-hidden="true" class="pointer-events-none absolute inset-0 bg-current opacity-0 transition-opacity duration-300 group-hover:opacity-[0.07]"></span>
			<?php endif; ?>
		</<?php echo $tag; ?>>
		<?php
		$index++;
	endwhile;
	wp_reset_postdata();
	?>
</div>
