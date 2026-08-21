<?php
/**
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 */

$policy_url = home_url( '/polityka-prywatnosci-i-plikow-cookies-oraz-regulamin/' );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php
	/*
	 * Starts hidden and stays that way — WP Super Cache serves this exact
	 * markup to every anonymous visitor of a given cached page, so baking
	 * visibility in here from a server-side cookie check would show (or
	 * hide) the banner based on whichever visitor's cookie happened to
	 * trigger that page's cache generation, not the browser actually
	 * looking at it. view.js unhides it on load only when *that browser's*
	 * document.cookie has no grid_consent choice yet.
	 */
	?>
	<div
		id="grid-cookie-banner"
		class="fixed inset-x-0 bottom-0 z-50 hidden border-t-2 border-divider bg-paper"
	>
		<div class="mx-auto flex max-w-page flex-col items-start gap-3 px-[18px] py-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6 sm:px-7 md:px-10">
			<p class="m-0 text-[13px] leading-[1.5] text-ink/70">
				Używamy plików cookie do analizy ruchu na stronie (Google Analytics). Możesz zaakceptować albo odrzucić — wybór możesz zmienić w każdej chwili w stopce strony. Szczegóły w
				<a class="text-ink underline hover:text-accent" href="<?php echo esc_url( $policy_url ); ?>">polityce prywatności i plików cookie</a>.
			</p>
			<div class="flex shrink-0 gap-4">
				<button type="button" data-cookie-action="denied" class="inline-flex cursor-pointer items-center justify-center text-[14px] font-extrabold text-ink/70 hover:text-ink">Odrzucam</button>
				<button type="button" data-cookie-action="granted" class="inline-flex cursor-pointer items-center justify-center gap-1.5 bg-accent px-[14.4px] py-2 text-[14px] font-extrabold text-paper hover:bg-accent-600 active:bg-accent-700">Akceptuję</button>
			</div>
		</div>
	</div>
</div>
