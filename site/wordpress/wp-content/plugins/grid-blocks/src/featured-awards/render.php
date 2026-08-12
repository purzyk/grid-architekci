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

$variants = array( 'accent', 'ink', 'surface' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid-featured-awards' ) ); ?>>
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
		$href    = $related_id ? get_permalink( $related_id ) : '#';
		$variant = $variants[ $index % count( $variants ) ];
		?>
		<a href="<?php echo esc_url( $href ); ?>" class="grid-featured-awards__plate is-variant-<?php echo esc_attr( $variant ); ?>">
			<div class="grid-featured-awards__year"><?php echo esc_html( $rok ); ?></div>
			<?php if ( $wynik ) : ?><div class="grid-featured-awards__kicker"><?php echo esc_html( $wynik ); ?></div><?php endif; ?>
			<div class="grid-featured-awards__name"><?php echo esc_html( $konkurs ); ?></div>
			<?php if ( $opis ) : ?><p class="grid-featured-awards__description"><?php echo esc_html( $opis ); ?></p><?php endif; ?>
			<span class="grid-featured-awards__arrow" aria-hidden="true">→</span>
			<span class="grid-featured-awards__mask" aria-hidden="true"></span>
		</a>
		<?php
		$index++;
	endwhile;
	wp_reset_postdata();
	?>
</div>
