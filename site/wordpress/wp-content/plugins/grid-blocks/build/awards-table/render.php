<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$query = new WP_Query( array(
	'post_type'      => 'nagroda',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="mt-12 hidden grid-cols-[58px_minmax(0,1fr)_minmax(0,1fr)_156px] gap-5 border-b-2 border-divider pb-[7px] text-[10px] uppercase tracking-[0.16em] text-ink/50 md:grid">
		<span>Rok</span>
		<span>Konkurs / nagroda</span>
		<span>Projekt</span>
		<span>Wynik</span>
	</div>
	<div class="mt-8 border-t-2 border-divider md:mt-0 md:border-t-0">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php
			$post_id      = get_the_ID();
			$rok          = function_exists( 'get_field' ) ? get_field( 'rok', $post_id ) : '';
			$konkurs      = function_exists( 'get_field' ) ? get_field( 'konkurs', $post_id ) : get_the_title();
			$wynik        = function_exists( 'get_field' ) ? get_field( 'wynik', $post_id ) : '';
			$top          = function_exists( 'get_field' ) ? get_field( 'top', $post_id ) : false;
			$projekt_nazwa = function_exists( 'get_field' ) ? get_field( 'projekt_nazwa', $post_id ) : '';
			$related      = function_exists( 'get_field' ) ? get_field( 'projekt_powiazany', $post_id ) : null;
			$related_id   = is_array( $related ) && ! empty( $related ) ? $related[0] : null;
			$href         = $related_id ? get_permalink( $related_id ) : '';
			$tag          = $href ? 'a' : 'div';
			$wynik_class  = $top ? 'text-accent-700' : 'text-ink/55';
			?>
			<<?php echo esc_html( $tag ); ?>
				<?php if ( $href ) : ?>href="<?php echo esc_url( $href ); ?>"<?php endif; ?>
				class="grid grid-cols-[46px_minmax(0,1fr)] gap-x-4 gap-y-0.5 border-b border-hairline py-2.5 text-ink transition-colors hover:bg-accent/[0.07] md:grid-cols-[58px_minmax(0,1fr)_minmax(0,1fr)_156px] md:items-baseline md:gap-5 md:py-2"
			>
				<span class="row-span-3 text-[14px] font-extrabold tracking-[-0.01em] md:row-span-1"><?php echo esc_html( $rok ); ?></span>
				<span class="text-[13px] leading-[1.35]"><?php echo esc_html( $konkurs ); ?></span>
				<span class="text-[13px] leading-[1.35] text-ink/60"><?php echo esc_html( $related_id ? get_the_title( $related_id ) : $projekt_nazwa ); ?></span>
				<span class="text-[10px] font-extrabold uppercase tracking-[0.1em] <?php echo esc_attr( $wynik_class ); ?>"><?php echo esc_html( $wynik ); ?></span>
			</<?php echo esc_html( $tag ); ?>>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
