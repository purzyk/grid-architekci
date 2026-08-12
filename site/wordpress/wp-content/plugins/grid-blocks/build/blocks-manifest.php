<?php
// This file is generated. Do not modify it manually.
return array(
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
	)
);
