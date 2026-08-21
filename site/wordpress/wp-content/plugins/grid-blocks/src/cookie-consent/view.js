/**
 * RODO/PKE cookie-consent banner.
 *
 * Plain viewScript, not the Interactivity API — same reasoning as
 * site-header/view.js: a handful of DOM writes, not worth pulling the
 * interactivity runtime onto every page for.
 *
 * Also owns the "Ustawienia cookies" reopen link in the footer
 * ([data-cookie-settings]) — RODO requires withdrawing consent to be as
 * easy as giving it, so that link has to be able to bring the banner back
 * regardless of any prior choice.
 */
( function () {
	var COOKIE = 'grid_consent';
	var DAYS = 180;

	function setChoice( value ) {
		var maxAge = DAYS * 24 * 60 * 60;
		var secure = 'https:' === window.location.protocol ? '; Secure' : '';
		document.cookie = COOKIE + '=' + value + '; path=/; max-age=' + maxAge + '; SameSite=Lax' + secure;
	}

	// The PHP-side enqueue in grid-core already decided "no consent yet" for
	// *this* page load before the banner was even clicked — accepting has to
	// activate GA4 client-side itself rather than waiting for a reload.
	function bootstrapGa4( id ) {
		if ( ! id || window.gtag ) {
			return;
		}
		window.dataLayer = window.dataLayer || [];
		window.gtag = function () {
			window.dataLayer.push( arguments );
		};
		window.gtag( 'js', new Date() );
		window.gtag( 'config', id );

		var script = document.createElement( 'script' );
		script.async = true;
		script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent( id );
		document.head.appendChild( script );
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
					var banner = document.getElementById( 'grid-cookie-banner' );
					var choice = n.getAttribute( 'data-cookie-action' );
					setChoice( choice );
					if ( banner ) {
						banner.classList.add( 'hidden' );
						if ( 'granted' === choice ) {
							bootstrapGa4( banner.getAttribute( 'data-ga-id' ) );
						}
					}
					return;
				}

				if ( n.hasAttribute( 'data-cookie-settings' ) ) {
					var toReopen = document.getElementById( 'grid-cookie-banner' );
					if ( toReopen ) {
						toReopen.classList.remove( 'hidden' );
					}
					return;
				}
			}
		},
		true
	);
} )();
