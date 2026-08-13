<?php
// This file is generated. Do not modify it manually.
return array(
	'awards-table' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/awards-table',
		'title' => 'Tabela nagród',
		'category' => 'grid-blocks',
		'icon' => 'list-view',
		'description' => 'Pełna lista nagród — dane pobierane z wpisów typu Nagroda, wiersze linkują do powiązanego projektu.',
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'featured-awards' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/featured-awards',
		'title' => 'Wyróżnione nagrody',
		'category' => 'grid-blocks',
		'icon' => 'awards',
		'description' => 'Trzy płyty wyróżnionych nagród u góry strony Osiągnięcia — pobierane z wpisów typu Nagroda oznaczonych „Wyróżniona”, nie wpisywane ręcznie.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'limit' => array(
				'type' => 'number',
				'default' => 3
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'hero' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/hero',
		'title' => 'Hero',
		'category' => 'grid-blocks',
		'icon' => 'cover-image',
		'description' => 'Nagłówek strony głównej — duży tytuł po lewej, wstęp i pasek statystyk po prawej. Jedna kolumna poniżej 900px.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'heading' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-hero__heading',
				'default' => 'Dobra przestrzeń, niezależnie od skali'
			),
			'intro' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-hero__intro',
				'default' => ''
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'highlight-plate' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/highlight-plate',
		'title' => 'Płyta wyróżnienia',
		'category' => 'grid-blocks',
		'icon' => 'awards',
		'description' => 'Płyta nagrody — rok, wynik, tytuł, opis. Trzy warianty wypełnienia: accent / ink / surface.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'year' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-highlight-plate__year',
				'default' => '2003'
			),
			'kicker' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-highlight-plate__kicker',
				'default' => 'I nagroda'
			),
			'title' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-highlight-plate__title',
				'default' => 'Nazwa konkursu'
			),
			'description' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-highlight-plate__description',
				'default' => ''
			),
			'url' => array(
				'type' => 'string',
				'default' => ''
			),
			'variant' => array(
				'type' => 'string',
				'default' => 'accent'
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'manifesto' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/manifesto',
		'title' => 'Manifest',
		'category' => 'grid-blocks',
		'icon' => 'quote',
		'description' => 'Stwierdzenie + akapit obok, np. otwarcie strony „O nas”. Mniejszy i nie kapitalikowy, w odróżnieniu od nagłówka Hero.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'statement' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-manifesto__statement',
				'default' => ''
			),
			'body' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-manifesto__body',
				'default' => ''
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'process-step' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/process-step',
		'title' => 'Krok procesu',
		'category' => 'grid-blocks',
		'icon' => 'editor-ol',
		'description' => 'Pojedynczy ponumerowany krok w bloku Kroki procesu.',
		'parent' => array(
			'grid/process-steps'
		),
		'supports' => array(
			'html' => false,
			'reusable' => false
		),
		'attributes' => array(
			'n' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-process-step__n',
				'default' => '01'
			),
			'title' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-process-step__title',
				'default' => 'Nazwa kroku'
			),
			'description' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-process-step__description',
				'default' => ''
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'process-steps' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/process-steps',
		'title' => 'Kroki procesu',
		'category' => 'grid-blocks',
		'icon' => 'list-view',
		'description' => 'Ponumerowane kroki procesu, np. „Jak pracujemy”. 1 kolumna na telefonie, 5 na szerokim ekranie.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'label' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-process-steps__label',
				'default' => 'Jak pracujemy'
			),
			'note' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-process-steps__note',
				'default' => ''
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'project-detail' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/project-detail',
		'title' => 'Szczegóły projektu',
		'category' => 'grid-blocks',
		'icon' => 'media-default',
		'description' => 'Lead, opis, galeria pełnej szerokości, rysunek zagospodarowania i metryka — dla bieżącego wpisu typu Projekt. Do użycia w szablonie pojedynczego projektu.',
		'supports' => array(
			'html' => false
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'project-grid' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/project-grid',
		'title' => 'Siatka projektów',
		'category' => 'grid-blocks',
		'icon' => 'layout',
		'description' => 'Filtrowana siatka projektów z „Pokaż więcej” — dane pobierane z wpisów typu Projekt, filtrowanie po stronie klienta bez przeładowania.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'postsPerPage' => array(
				'type' => 'number',
				'default' => 12
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	),
	'project-header' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/project-header',
		'title' => 'Nagłówek projektu',
		'category' => 'grid-blocks',
		'icon' => 'editor-kb',
		'description' => 'Kategoria · status · rok, tytuł i lead — dokładnie jak w makiecie pojedynczego projektu. Do użycia w szablonie pojedynczego projektu, przed grid/project-detail.',
		'supports' => array(
			'html' => false
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'project-nav' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/project-nav',
		'title' => 'Nawigacja projektu',
		'category' => 'grid-blocks',
		'icon' => 'editor-kb',
		'description' => 'Poprzedni / Wszystkie projekty / Następny — pasek nawigacji na dole strony pojedynczego projektu, dokładnie jak w makiecie.',
		'supports' => array(
			'html' => false
		),
		'usesContext' => array(
			'postId'
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'publications-grid' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/publications-grid',
		'title' => 'Siatka publikacji',
		'category' => 'grid-blocks',
		'icon' => 'media-document',
		'description' => 'Okładki publikacji, 2–6 na wiersz — dane pobierane z wpisów typu Publikacja.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'label' => array(
				'type' => 'string',
				'default' => 'Publikacje'
			),
			'note' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'site-header' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/site-header',
		'title' => 'Nagłówek strony',
		'category' => 'grid-blocks',
		'icon' => 'menu',
		'description' => 'Logo + nawigacja z hamburger menu na telefonie, dokładnie jak w makiecie (checkbox-driven, bez core/navigation) — aktywna strona ma podkreślenie accent.',
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'stat-bar' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/stat-bar',
		'title' => 'Pasek statystyk',
		'category' => 'grid-blocks',
		'icon' => 'chart-bar',
		'description' => 'Rząd statystyk oddzielony górną linią, np. „+20 lat pracowni”.',
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'stat-item' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/stat-item',
		'title' => 'Statystyka',
		'category' => 'grid-blocks',
		'icon' => 'info',
		'description' => 'Pojedyncza liczba i etykieta w pasku statystyk.',
		'parent' => array(
			'grid/stat-bar'
		),
		'supports' => array(
			'html' => false,
			'reusable' => false
		),
		'attributes' => array(
			'value' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-stat-item__value',
				'default' => '+20'
			),
			'label' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => '.grid-stat-item__label',
				'default' => 'etykieta'
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js'
	),
	'team-grid' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'grid/team-grid',
		'title' => 'Siatka zespołu',
		'category' => 'grid-blocks',
		'icon' => 'groups',
		'description' => 'Zdjęcia, role i bio zespołu — pobierane z wpisów typu Zespół, nie wpisywane ręcznie.',
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'label' => array(
				'type' => 'string',
				'default' => 'Zespół'
			),
			'note' => array(
				'type' => 'string',
				'default' => 'Pracownia we Wrocławiu'
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	)
);
