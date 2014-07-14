<?php
return array(
	'params' => array(
		'menu_bar_items' => array(
			'reports' => array(
				'title' => 'Reports',
				'uri' => 'Reports/default/index',
				'position' => 40,
			),
		),
	),
	'components' => array(
		'clientScript' => array(
			'packages' => array(
				'reports' => array(
					'js' => array(
						'js/d3.js',
						'js/d3.chart.js',
						'js/underscore.js',
						'js/oe_bulletgraph.js'
					),
					'css' => array(
						'css/module.css'
					),
					'basePath' => 'application.modules.Reports.assets',
					'depends' => array('core')
				)
			),
		)
	)
);