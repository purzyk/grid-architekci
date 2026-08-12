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
		'style' => 'file:./style-index.css',
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
		'style' => 'file:./style-index.css',
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
			'html' => false,
			'layout' => array(
				'default' => array(
					'type' => 'flex',
					'flexWrap' => 'wrap'
				)
			)
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
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
		'style' => 'file:./style-index.css',
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
			'html' => false,
			'layout' => array(
				'default' => array(
					'type' => 'flex',
					'flexWrap' => 'nowrap'
				)
			)
		),
		'textdomain' => 'grid',
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css'
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
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	)
);
