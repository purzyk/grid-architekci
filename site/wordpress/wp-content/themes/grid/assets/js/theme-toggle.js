/* GRID — theme toggle.
   Applies the saved (or system) theme, then flips it on any click of a
   [data-theme-toggle] control. Enqueued blocking in <head> (see
   functions.php) so it runs before body paints — no flash of the wrong
   theme. Ported from the mocks' own theme-toggle.js. */
(function () {
	var KEY = 'grid-theme';

	function saved() {
		try { return localStorage.getItem( KEY ); } catch ( e ) { return null; }
	}
	function store( v ) {
		try { localStorage.setItem( KEY, v ); } catch ( e ) {}
	}
	function prefersDark() {
		return !! ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches );
	}

	var pref = saved();
	document.documentElement.classList.toggle( 'dark', pref ? pref === 'dark' : prefersDark() );

	document.addEventListener( 'click', function ( e ) {
		var path = e.composedPath ? e.composedPath() : [ e.target ];
		for ( var i = 0; i < path.length; i++ ) {
			var n = path[ i ];
			if ( n && n.nodeType === 1 && n.hasAttribute && n.hasAttribute( 'data-theme-toggle' ) ) {
				var on = document.documentElement.classList.toggle( 'dark' );
				store( on ? 'dark' : 'light' );
				return;
			}
		}
	}, true );
})();
