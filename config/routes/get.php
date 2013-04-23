<?php

$config = array (
	'' => array(
		0 => '\Cerceau\Controller\Main',
		1 => 'main',
		2 => '',
	),
	'admin/' => array(
		0 => '\Cerceau\Controller\Main',
		1 => 'admin',
		2 => 'admin/',
	),
	'admin/<brands|people>/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'categories',
		2 => 'admin/<brands|people>/',
		'params' => array(
			0 => 'partition',
		),
	),
	'admin/<brands|people>/<\d+>/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publics',
		2 => 'admin/<brands|people>/<\d+>/',
		'params' => array(
			0 => 'partition',
			1 => 'subcat_id',
		),
	),
	'admin/<brands|people>/category/<\d+>/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'categoryPublics',
		2 => 'admin/<brands|people>/category/<\d+>/',
		'params' => array(
			0 => 'partition',
			1 => 'cat_id',
		),
	),
	'admin/bots/<\d*>' => array(
		0 => '\Cerceau\Controller\Admin\Bots',
		1 => 'bots',
		2 => 'admin/bots/<\d*>',
		'params' => array(
			0 => 'type',
		),
	),
	'admin/bots/auth/' => array(
		0 => '\Cerceau\Controller\Admin\Bots',
		1 => 'authorizeBot',
		2 => 'admin/bots/auth/',
		'view' => 'Redirect',
	),
	'admin/charts/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'defaultCharts',
		2 => 'admin/charts/',
	),
	'admin/charts/<\d>' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'charts',
		2 => 'admin/charts/<\d>',
		'params' => array(
			0 => 'use_method',
		),
	),
	'admin/gallery/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'main',
		2 => 'admin/gallery/',
	),
	'admin/gallery/<\d+>/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publics',
		2 => 'admin/gallery/<\d+>/',
		'params' => array(
			0 => 'gallery_id',
		),
	),
	'admin/snapshot' => array(
		0 => '\Cerceau\Controller\Admin\Snapshots',
		1 => 'main',
		2 => 'admin/snapshot',
	),
	'admin/users/' => array(
		0 => '\Cerceau\Controller\Admin\Users',
		1 => 'users',
		2 => 'admin/users/',
	),
	'admin/users/<\d+>/privileges' => array(
		0 => '\Cerceau\Controller\Admin\Users',
		1 => 'userPrivileges',
		2 => 'admin/users/<\d+>/privileges',
		'params' => array(
			0 => 'admin_id',
		),
		'view' => 'Json',
	),
	'api/auth/error/' => array(
		0 => '\Cerceau\Controller\User\Auth',
		1 => 'error',
		2 => 'api/auth/error/',
		'view' => 'Redirect',
	),
	'api/auth/instagram/' => array(
		0 => '\Cerceau\Controller\User\Auth',
		1 => 'response',
		2 => 'api/auth/instagram/',
		'view' => 'Redirect',
	),
	'api/auth/success/' => array(
		0 => '\Cerceau\Controller\User\Auth',
		1 => 'success',
		2 => 'api/auth/success/',
		'view' => 'Json',
	),
	'api/v1/<people|brands>/categories/' => array(
		0 => '\Cerceau\Controller\Api\PeopleAndBrands',
		1 => 'categories',
		2 => 'api/v1/<people|brands>/categories/',
		'params' => array(
			0 => 'partition',
		),
	),
	'api/v1/<people|brands>/categories/<\d+>/' => array(
		0 => '\Cerceau\Controller\Api\PeopleAndBrands',
		1 => 'categoryPublics',
		2 => 'api/v1/<people|brands>/categories/<\d+>/',
		'params' => array(
			0 => 'partition',
			1 => 'cat_id',
		),
	),
	'api/v1/<people|brands>/categories/<\d+>/<\d+>/' => array(
		0 => '\Cerceau\Controller\Api\PeopleAndBrands',
		1 => 'subcategoryPublics',
		2 => 'api/v1/<people|brands>/categories/<\d+>/<\d+>/',
		'params' => array(
			0 => 'partition',
			1 => 'cat_id',
			2 => 'subcat_id',
		),
	),
	'api/v1/<people|brands>/search/' => array(
		0 => '\Cerceau\Controller\Api\PeopleAndBrands',
		1 => 'search',
		2 => 'api/v1/<people|brands>/search/',
		'params' => array(
			0 => 'partition',
		),
	),
	'api/v1/auth/instagram/' => array(
		0 => '\Cerceau\Controller\User\Auth',
		1 => 'login',
		2 => 'api/v1/auth/instagram/',
		'view' => 'Redirect',
	),
	'api/v1/galleries/' => array(
		0 => '\Cerceau\Controller\Api\Gallery',
		1 => 'galleries',
		2 => 'api/v1/galleries/',
	),
	'api/v1/galleries/<\d+>/' => array(
		0 => '\Cerceau\Controller\Api\Gallery',
		1 => 'media',
		2 => 'api/v1/galleries/<\d+>/',
		'params' => array(
			0 => 'gallery_id',
		),
	),
	'api/v1/gallery/<\d+>/publics/' => array(
		0 => '\Cerceau\Controller\Api\Gallery',
		1 => 'publics',
		2 => 'api/v1/gallery/<\d+>/publics/',
		'params' => array(
			0 => 'gallery_id',
		),
	),
	'api/v1/main/' => array(
		0 => '\Cerceau\Controller\Api\Snapshot',
		1 => 'snapshot',
		2 => 'api/v1/main/',
	),
	'api/v1/people/charts/' => array(
		0 => '\Cerceau\Controller\Api\PeopleAndBrands',
		1 => 'charts',
		2 => 'api/v1/people/charts/',
	),
	'api/version/apk/' => array(
		0 => '\Cerceau\Controller\Api\Version',
		1 => 'apkVersion',
		2 => 'api/version/apk/',
	),
	'banner<\w+>/' => array(
		0 => '\Cerceau\Controller\Main',
		1 => 'main2',
		2 => 'banner<\w+>/',
		'params' => array(
			0 => 'banner',
		),
	),
	'logout' => array(
		0 => '\Cerceau\Controller\Main',
		1 => 'logout',
		2 => 'logout',
		'view' => 'Redirect',
	),
	'o' => 'Json',
);