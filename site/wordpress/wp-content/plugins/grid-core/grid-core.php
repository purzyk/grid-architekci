<?php
/**
 * Plugin Name: GRID Core
 * Description: Content model for the GRID Architekci site — projekt/zespol/nagroda/publikacja post types and the projekt taxonomies. Kept in a plugin, not the theme, so the data model survives a theme change.
 * Version: 0.1.0
 * Update URI: false
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Generate image subsizes as WebP instead of JPEG — smaller files at the
// same visual quality, and it's fully transparent to the rest of the site
// since wp_get_attachment_image()/srcset are metadata-driven: whatever
// format the stored sizes array says, that's what gets served. The
// original uploaded file is untouched either way.
add_filter( 'image_editor_output_format', function( $formats ) {
	$formats['image/jpeg'] = 'image/webp';
	return $formats;
} );

require_once __DIR__ . '/acf-fields.php';

add_action( 'init', 'grid_core_register_post_types' );
function grid_core_register_post_types() {

	register_post_type( 'projekt', array(
		'labels'       => array(
			'name'          => 'Projekty',
			'singular_name' => 'Projekt',
			'add_new_item'  => 'Dodaj projekt',
			'edit_item'     => 'Edytuj projekt',
			'all_items'     => 'Wszystkie projekty',
		),
		'public'       => true,
		'has_archive'  => false, // the front page grid covers this; singles still need their own permalinks
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-building',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'      => array( 'slug' => 'projekt' ),
	) );

	register_post_type( 'zespol', array(
		'labels'       => array(
			'name'          => 'Zespół',
			'singular_name' => 'Członek zespołu',
			'add_new_item'  => 'Dodaj osobę',
			'edit_item'     => 'Edytuj osobę',
			'all_items'     => 'Zespół',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-groups',
		// Display order (menu_order, read in team-grid/render.php) is set
		// via Simple Custom Post Order's drag-and-drop list-table UI, not
		// 'page-attributes' — no numeric Order field needed on top of that.
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
	) );

	register_post_type( 'nagroda', array(
		'labels'       => array(
			'name'          => 'Nagrody',
			'singular_name' => 'Nagroda',
			'add_new_item'  => 'Dodaj nagrodę',
			'edit_item'     => 'Edytuj nagrodę',
			'all_items'     => 'Nagrody',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-awards',
		'supports'     => array( 'title' ),
	) );

	register_post_type( 'publikacja', array(
		'labels'       => array(
			'name'          => 'Publikacje',
			'singular_name' => 'Publikacja',
			'add_new_item'  => 'Dodaj publikację',
			'edit_item'     => 'Edytuj publikację',
			'all_items'     => 'Publikacje',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-media-document',
		'supports'     => array( 'title', 'thumbnail' ),
	) );
}

add_action( 'init', 'grid_core_register_taxonomies' );
function grid_core_register_taxonomies() {

	// publicly_queryable is deliberately false on both: nothing on the site
	// links to a /kategoria/... or /status/... archive — project-grid's
	// category/status filtering is client-side tabs on the homepage, not
	// separate pages — and there's no archive template for them either
	// (they fell through to the generic index.html fallback, which renders
	// a single stray post title with no listing, not a real archive). That
	// combination is exactly what a search engine calls thin/broken
	// content, so these are 404s now rather than orphaned pages a crawler
	// could still stumble onto by URL guessing. `public => true` is kept so
	// admin term management and the ACF/editor taxonomy UI still work.
	register_taxonomy( 'projekt_kategoria', 'projekt', array(
		'labels'              => array(
			'name'          => 'Kategorie',
			'singular_name' => 'Kategoria',
		),
		'hierarchical'        => true,
		'public'              => true,
		'publicly_queryable'  => false,
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => 'kategoria' ),
	) );

	register_taxonomy( 'projekt_status', 'projekt', array(
		'labels'              => array(
			'name'          => 'Status',
			'singular_name' => 'Status',
		),
		'hierarchical'        => true,
		'public'              => true,
		'publicly_queryable'  => false,
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => 'status' ),
	) );
}

/**
 * Default terms, matching projekt_kategoria/projekt_status split from
 * SPEC.md — the old site's flat `project_category` taxonomy conflated
 * both. Registered on activation so a fresh install/migration always has
 * these to work with.
 */
register_activation_hook( __FILE__, 'grid_core_activate' );
function grid_core_activate() {
	grid_core_register_post_types();
	grid_core_register_taxonomies();

	$kategorie = array( 'mieszkalne', 'publiczne', 'przemysłowe' );
	foreach ( $kategorie as $term ) {
		if ( ! term_exists( $term, 'projekt_kategoria' ) ) {
			wp_insert_term( $term, 'projekt_kategoria' );
		}
	}

	$statusy = array( 'zrealizowane', 'w realizacji', 'konkurs', 'koncepcja' );
	foreach ( $statusy as $term ) {
		if ( ! term_exists( $term, 'projekt_status' ) ) {
			wp_insert_term( $term, 'projekt_status' );
		}
	}

	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );

/**
 * This is a brochure site with no blog and no discussion feature anywhere
 * in the design — comments exist only as a spam surface. Close them off at
 * every layer rather than just hiding the admin UI: options, post type
 * support, and a hard `comments_open`/`pings_open` filter so a submission
 * can't get through even via wp-comments-post.php, XML-RPC, or the REST
 * API regardless of any individual post's stored comment_status.
 */
add_action( 'init', function() {
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );

	foreach ( array( 'post', 'page', 'attachment' ) as $post_type ) {
		remove_post_type_support( $post_type, 'comments' );
		remove_post_type_support( $post_type, 'trackbacks' );
	}
} );

add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

add_action( 'admin_menu', function() {
	remove_menu_page( 'edit-comments.php' ); // Comments
	remove_menu_page( 'edit.php' );          // Posts — this site has no blog
} );

add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'new-post' );
}, 80 );

// "Quick Draft" creates a post directly from the dashboard, bypassing the
// hidden Posts menu.
add_action( 'wp_dashboard_setup', function() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
} );

add_action( 'admin_init', function() {
	if ( isset( $_GET['page'] ) ) {
		return;
	}
	global $pagenow;
	if ( in_array( $pagenow, array( 'edit-comments.php', 'comment.php', 'edit.php', 'post-new.php' ), true )
		&& empty( $_GET['post_type'] )
	) {
		wp_safe_redirect( admin_url() );
		exit;
	}
} );

// No blog, so nothing should advertise a comments feed either.
add_filter( 'feed_links_show_comments_feed', '__return_false' );

/**
 * Baseline hardening. Nothing here relies on a security plugin — these are
 * plain WordPress core hooks/constants closing off attack surface this site
 * doesn't use. CF7 spam filtering (Akismet vs. honeypot) is a separate,
 * deliberately deferred decision — handled later, on the live site.
 */

// XML-RPC: brute-force amplification / pingback DDoS vector, unused here
// (no Jetpack, no mobile app posting).
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
add_filter( 'wp_headers', function( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

// Don't hand out the exact WordPress version — makes it trivial to match
// against known CVEs for that release.
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Same version leak, different door: every core-enqueued script/style URL
// carries ?ver=<wp version> unless we strip it.
function grid_strip_version_query( $src ) {
	global $wp_version;
	$version_query = '?ver=' . $wp_version;
	$offset        = strlen( $src ) - strlen( $version_query );
	if ( $offset >= 0 && strpos( $src, $version_query, $offset ) !== false ) {
		return substr( $src, 0, $offset );
	}
	return $src;
}
add_filter( 'script_loader_src', 'grid_strip_version_query' );
add_filter( 'style_loader_src', 'grid_strip_version_query' );

// The REST API and the old ?author=N query var both leak real usernames
// (confirmed live: /wp-json/wp/v2/users returned "admin" outright, and
// ?author=1 redirected straight to /author/admin/) — halves the work for
// anyone brute-forcing login, since they no longer have to guess the
// username too.
add_filter( 'rest_endpoints', function( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );
add_action( 'init', function() {
	if ( ! is_admin() && isset( $_GET['author'] ) && is_numeric( $_GET['author'] ) ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
} );

// No editing PHP through the dashboard — if an attacker ever got any
// admin-level foothold, this is the difference between "can view things"
// and "can write arbitrary code to disk." Defined here (not wp-config.php)
// so it's version-controlled and travels with the codebase automatically.
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Lock out an IP after 5 failed logins within 15 minutes. WordPress core
 * has no login throttling at all by default. Uses REMOTE_ADDR rather than
 * X-Forwarded-For — the latter is trivially spoofable unless you know your
 * specific proxy/CDN setup validates it, so if this ever sits behind one,
 * this will need adjusting to read the real client IP correctly.
 */
function grid_login_throttle_key() {
	return 'grid_login_fails_' . md5( $_SERVER['REMOTE_ADDR'] ?? '' );
}

add_filter( 'authenticate', function( $user, $username, $password ) {
	if ( empty( $username ) && empty( $password ) ) {
		return $user;
	}
	if ( (int) get_transient( grid_login_throttle_key() ) >= 5 ) {
		return new WP_Error( 'grid_too_many_attempts', __( 'Zbyt wiele nieudanych prób logowania. Spróbuj ponownie za 15 minut.' ) );
	}
	return $user;
}, 30, 3 );

add_action( 'wp_login_failed', function() {
	$key   = grid_login_throttle_key();
	$count = (int) get_transient( $key );
	set_transient( $key, $count + 1, 15 * MINUTE_IN_SECONDS );
} );

add_action( 'wp_login', function() {
	delete_transient( grid_login_throttle_key() );
} );

/**
 * Contact Form 7 enqueues its CSS/JS site-wide by default — the form only
 * lives on Kontakt, so every other page was shipping ~3 unused requests
 * (dead weight contributing to PageSpeed's render-blocking-requests flag).
 */
add_action( 'wp_enqueue_scripts', function() {
	if ( is_singular() && has_shortcode( get_post()->post_content, 'contact-form-7' ) ) {
		return;
	}
	wp_dequeue_style( 'contact-form-7' );
	wp_dequeue_script( 'contact-form-7' );
	wp_dequeue_script( 'swv' );
}, 20 );

/**
 * Honeypot for the Kontakt form — see the your-website field in
 * seed-contact-form.php for the hidden field itself. Real visitors never
 * see or fill it; a filled value means whatever submitted the form didn't
 * render the CSS, i.e. almost certainly a bot.
 */
add_filter( 'wpcf7_spam', function( $spam, $submission ) {
	if ( $spam ) {
		return $spam;
	}
	$data = $submission->get_posted_data();
	return ! empty( $data['your-website'] );
}, 10, 2 );

/**
 * Google Analytics 4.
 *
 * Host/editor guards, both deliberate and both safe to decide in PHP:
 *
 * - Never reports from the dev host. Until the site moves to the client's
 *   real domain, every hit here — including automated browser checks —
 *   would land in their property and skew the numbers from day one, and
 *   bad baseline data is worse than none. Moving to the production domain
 *   is all it takes to switch this on; there is nothing else to remember.
 * - Never reports for signed-in editors, so the people working on the site
 *   don't count as its audience. WP Super Cache doesn't cache logged-in
 *   requests at all, so this one stays request-accurate even under caching.
 *
 * RODO/PKE consent is deliberately NOT gated here in PHP. WP Super Cache
 * serves one static HTML file per URL to every anonymous visitor — a
 * server-side `if ($_COOKIE['grid_consent'] === 'granted')` bakes in
 * whichever visitor's cookie state happened to trigger that file's
 * generation and then serves it to everyone else regardless of *their*
 * own cookie (this shipped once, and un-shipped after a live test: accept
 * on the homepage, navigate away, navigate back — cached page, consent
 * banner reappears, cookie ignored). The actual consent check has to run
 * in the browser: window.__gridGa4Boot(), defined below and called by the
 * cookie-consent block's view.js on acceptance (immediately, since the
 * page's own markup is cache-frozen at whatever consent state existed
 * when it was generated) and on every page load where the cookie already
 * reads "granted".
 */
const GRID_GA4_MEASUREMENT_ID = 'G-VL2L8YWMX7';

function grid_ga4_eligible() {
	$dev_hosts = array( 'grid.purzyk.usermd.net', 'localhost' );
	$host      = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );

	return ! in_array( $host, $dev_hosts, true )
		&& ! ( is_user_logged_in() && current_user_can( 'edit_posts' ) );
}

add_action( 'wp_enqueue_scripts', function() {
	if ( ! grid_ga4_eligible() ) {
		return;
	}

	// No src: this is the loader, not gtag.js itself. Identical markup for
	// every visitor of a given cached page — the only thing that varies is
	// each browser's own document.cookie read, at execution time, which is
	// exactly what a server-rendered/cached page can't do per-visitor.
	wp_register_script( 'grid-ga4-loader', false, array(), null );
	wp_enqueue_script( 'grid-ga4-loader' );
	wp_add_inline_script(
		'grid-ga4-loader',
		sprintf(
			'window.__gridGa4Boot = function () {' .
				'if ( window.gtag ) { return; }' .
				'window.dataLayer = window.dataLayer || [];' .
				'window.gtag = function () { window.dataLayer.push( arguments ); };' .
				'window.gtag( "js", new Date() );' .
				'window.gtag( "config", %1$s );' .
				'var s = document.createElement( "script" );' .
				's.async = true;' .
				's.src = "https://www.googletagmanager.com/gtag/js?id=" + encodeURIComponent( %1$s );' .
				'document.head.appendChild( s );' .
			'};' .
			'if ( /(?:^|; )grid_consent=granted(?:;|$)/.test( document.cookie ) ) { window.__gridGa4Boot(); }',
			wp_json_encode( GRID_GA4_MEASUREMENT_ID )
		)
	);
} );
