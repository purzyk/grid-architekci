<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */
?>
<?php // Re-creates the same grid/gap-4 stacking 404.html's <main> used to
	  // apply directly to these four elements as its own grid children — now
	  // that they're one block (one grid child of <main>), the same layout
	  // classes have to live on this wrapper instead. ?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'grid grid-cols-1 items-start gap-4' ) ); ?>>
	<p class="m-0 text-[10px] uppercase tracking-[0.2em] text-accent"><?php esc_html_e( 'Błąd 404', 'grid' ); ?></p>

	<h1 class="m-0 hyphens-auto break-words text-[clamp(30px,5.6vw,72px)] font-extrabold uppercase leading-[0.96] tracking-[-0.035em]"><?php esc_html_e( 'Strony nie znaleziono', 'grid' ); ?></h1>

	<p class="m-0 mt-2 max-w-[560px] text-[15px] leading-[1.6] text-ink/70"><?php esc_html_e( 'Strona, której szukasz, nie istnieje albo została przeniesiona. Sprawdź adres lub wróć na stronę główną.', 'grid' ); ?></p>

	<p class="m-0 mt-4"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="border-b-2 border-accent pb-0.5 text-[13px] font-extrabold uppercase tracking-[0.14em] text-ink hover:text-accent"><?php esc_html_e( 'Wróć na stronę główną', 'grid' ); ?></a></p>
</div>
