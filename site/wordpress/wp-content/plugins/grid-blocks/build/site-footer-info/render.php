<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$policy_url = grid_translated_url( 'polityka-prywatnosci-i-plikow-cookies-oraz-regulamin' );
?>
<footer <?php echo get_block_wrapper_attributes( array( 'class' => 'mt-14 grid grid-cols-1 items-end gap-7 border-t-2 border-divider pt-6 md:mt-16 md:grid-cols-[1fr_auto] md:gap-16' ) ); ?>>
	<div>
		<div class="mb-2.5 flex flex-wrap gap-x-6 gap-y-3">
			<p class="m-0"><a class="border-b-2 border-accent pb-0.5 text-[18px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]" href="https://www.instagram.com/gridarchitekci" target="_blank" rel="noopener">Instagram</a></p>
			<p class="m-0"><a class="border-b-2 border-accent pb-0.5 text-[18px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]" href="https://www.facebook.com/gridarchitekci/" target="_blank" rel="noopener">Facebook</a></p>
			<p class="m-0"><a class="border-b-2 border-accent pb-0.5 text-[18px] font-extrabold uppercase tracking-[-0.02em] md:text-[22px]" href="https://www.linkedin.com/company/grid-architekci/" target="_blank" rel="noopener">LinkedIn</a></p>
		</div>
		<p class="m-0 text-[12px] text-ink/55"><?php esc_html_e( 'Kulisy naszej pracy, postępy na budowach i świeże realizacje.', 'grid' ); ?></p>
	</div>

	<address class="text-left text-[13px] not-italic leading-[1.5] text-ink/70 md:text-right">
		<p class="m-0">ul. Jarocińska 59, 51-011 Wrocław<br><a class="text-ink/70 hover:text-ink" href="&#116;&#101;&#108;&#58;&#43;&#52;&#56;&#55;&#49;&#51;&#54;&#53;&#54;&#57;&#57;&#56;">&#43;&#52;&#56;&#32;&#55;&#49;&#32;&#51;&#54;&#53;&#32;&#54;&#57;&#32;&#57;&#56;</a><br><a class="text-ink/70 hover:text-ink" href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#105;&#110;&#102;&#111;&#64;&#103;&#114;&#105;&#100;&#46;&#110;&#101;&#116;&#46;&#112;&#108;">&#105;&#110;&#102;&#111;&#64;&#103;&#114;&#105;&#100;&#46;&#110;&#101;&#116;&#46;&#112;&#108;</a></p>
	</address>

	<div class="mt-2 flex flex-col gap-2 border-t border-divider pt-4 text-[13px] not-italic leading-[1.5] text-ink/70 sm:flex-row sm:items-center sm:justify-between md:col-span-2">
		<p class="m-0">
			<?php
			printf(
				/* translators: 1: link to the privacy/cookie policy page, 2: "cookie settings" button. */
				esc_html__( 'Copyright: Grid architekci 2026 %1$s %2$s', 'grid' ),
				'&middot; <a class="text-ink/70 hover:text-ink" href="' . esc_url( $policy_url ) . '">' . esc_html__( 'Polityka prywatności i regulamin', 'grid' ) . '</a>',
				'&middot; <button type="button" data-cookie-settings="true" class="cursor-pointer text-ink/70 hover:text-ink">' . esc_html__( 'Ustawienia cookies', 'grid' ) . '</button>'
			);
			?>
		</p>
		<p class="m-0">
			<?php
			printf(
				/* translators: %s is a link to the developer's own site. */
				esc_html__( 'Wdrożenie: %s', 'grid' ),
				'<a class="text-ink/70 hover:text-ink" href="https://purzycki.pl/" target="_blank" rel="noopener">purzycki.pl</a>'
			);
			?>
		</p>
	</div>
</footer>
