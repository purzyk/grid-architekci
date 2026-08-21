/**
 * RODO/PKE cookie-consent banner.
 *
 * Plain viewScript, not the Interactivity API — same reasoning as
 * site-header/view.js: a handful of DOM writes, not worth pulling the
 * interactivity runtime onto every page for.
 *
 * Deliberately does no server-side visibility check (see render.php):
 * this site is served through WP Super Cache, which hands the same
 * static HTML to every anonymous visitor of a given URL — a PHP-side
 * `if ($_COOKIE[...])` would bake in whichever visitor's cookie state
 * happened to trigger that page's cache generation. The only place a
 * per-visitor cookie can actually be read per-visitor is here, in the
 * browser, on every load.
 *
 * Also owns the "Ustawienia cookies" reopen link in the footer
 * ([data-cookie-settings]) — RODO requires withdrawing consent to be as
 * easy as giving it, so that link has to be able to bring the banner back
 * regardless of any prior choice.
 */
( function () {
	var COOKIE = 'grid_consent';
	var DAYS = 180;
	var banner = document.getElementById( 'grid-cookie-banner' );

	function currentChoice() {
		var match = document.cookie.match( /(?:^|; )grid_consent=(granted|denied)(?:;|$)/ );
		return match ? match[ 1 ] : '';
	}

	function setChoice( value ) {
		var maxAge = DAYS * 24 * 60 * 60;
		var secure = 'https:' === window.location.protocol ? '; Secure' : '';
		document.cookie = COOKIE + '=' + value + '; path=/; max-age=' + maxAge + '; SameSite=Lax' + secure;
	}

	if ( banner && '' === currentChoice() ) {
		banner.classList.remove( 'hidden' );
	}

	// composedPath(), so the control is still found if the page renders in a
	// shadow root — same guard theme-toggle.js uses.
	document.addEventListener(
		'click',
		function ( e ) {
			var path = e.composedPath ? e.composedPath() : [ e.target ];

			for ( var i = 0; i < path.length; i++ ) {
				var n = path[ i ];
				if ( ! n || n.nodeType !== 1 || ! n.hasAttribute ) {
					continue;
				}

				if ( n.hasAttribute( 'data-cookie-action' ) ) {
					var choice = n.getAttribute( 'data-cookie-action' );
					setChoice( choice );
					if ( banner ) {
						banner.classList.add( 'hidden' );
					}
					// grid-core only defines this when GA4 is eligible at
					// all (real domain, not a signed-in editor) — absent
					// otherwise, so this is a safe no-op on the dev host.
					if ( 'granted' === choice && 'function' === typeof window.__gridGa4Boot ) {
						window.__gridGa4Boot();
					}
					return;
				}

				if ( n.hasAttribute( 'data-cookie-settings' ) ) {
					if ( banner ) {
						banner.classList.remove( 'hidden' );
					}
					return;
				}
			}
		},
		true
	);
} )();
