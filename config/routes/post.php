<?php

$config = array (
	'admin/<brands|people>/public/add/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publicAdd',
		2 => 'admin/<brands|people>/public/add/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/public/data/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publicData',
		2 => 'admin/<brands|people>/public/data/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/public/delete/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publicDel',
		2 => 'admin/<brands|people>/public/delete/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/public/edit/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publicEdit',
		2 => 'admin/<brands|people>/public/edit/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/public/restore/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'publicRestore',
		2 => 'admin/<brands|people>/public/restore/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/subcat/add/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'subcatAdd',
		2 => 'admin/<brands|people>/subcat/add/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/subcat/delete/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'subcatDel',
		2 => 'admin/<brands|people>/subcat/delete/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/<brands|people>/subcat/edit/' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'subcatEdit',
		2 => 'admin/<brands|people>/subcat/edit/',
		'params' => array(
			0 => 'partition',
		),
		'view' => 'Json',
	),
	'admin/bot/edit/<\d*>' => array(
		0 => '\Cerceau\Controller\Admin\Bots',
		1 => 'editBot',
		2 => 'admin/bot/edit/<\d*>',
		'params' => array(
			0 => 'instagram_id',
		),
		'view' => 'Json',
	),
	'admin/charts/method/save' => array(
		0 => '\Cerceau\Controller\Admin\PeopleAndBrands',
		1 => 'saveChartsMethod',
		2 => 'admin/charts/method/save',
		'view' => 'Json',
	),
	'admin/gallery/categories/reorder/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'reorder',
		2 => 'admin/gallery/categories/reorder/',
		'view' => 'Json',
	),
	'admin/gallery/category/delete/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'deleteCategory',
		2 => 'admin/gallery/category/delete/',
		'view' => 'Json',
	),
	'admin/gallery/category/edit/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'editCategory',
		2 => 'admin/gallery/category/edit/',
		'view' => 'Json',
	),
	'admin/gallery/category/public/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publicCategory',
		2 => 'admin/gallery/category/public/',
		'view' => 'Json',
	),
	'admin/gallery/category/save/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'saveCategory',
		2 => 'admin/gallery/category/save/',
		'view' => 'Json',
	),
	'admin/gallery/order/save/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'saveOrder',
		2 => 'admin/gallery/order/save/',
		'view' => 'Json',
	),
	'admin/gallery/public/add/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publicAdd',
		2 => 'admin/gallery/public/add/',
		'view' => 'Json',
	),
	'admin/gallery/public/delete/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publicDel',
		2 => 'admin/gallery/public/delete/',
		'view' => 'Json',
	),
	'admin/gallery/public/edit/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publicEdit',
		2 => 'admin/gallery/public/edit/',
		'view' => 'Json',
	),
	'admin/gallery/public/restore/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'publicRestore',
		2 => 'admin/gallery/public/restore/',
		'view' => 'Json',
	),
	'admin/gallery/temp/upload/' => array(
		0 => '\Cerceau\Controller\Admin\Gallery',
		1 => 'uploadTempIcon',
		2 => 'admin/gallery/temp/upload/',
		'view' => 'Json',
	),
	'admin/snapshot/banner/upload' => array(
		0 => '\Cerceau\Controller\Admin\Snapshots',
		1 => 'uploadBanner',
		2 => 'admin/snapshot/banner/upload',
		'view' => 'Json',
	),
	'admin/snapshot/publish' => array(
		0 => '\Cerceau\Controller\Admin\Snapshots',
		1 => 'publish',
		2 => 'admin/snapshot/publish',
		'view' => 'Json',
	),
	'admin/snapshot/save' => array(
		0 => '\Cerceau\Controller\Admin\Snapshots',
		1 => 'save',
		2 => 'admin/snapshot/save',
		'view' => 'Json',
	),
	'admin/snapshot/tile/upload' => array(
		0 => '\Cerceau\Controller\Admin\Snapshots',
		1 => 'uploadTile',
		2 => 'admin/snapshot/tile/upload',
		'view' => 'Json',
	),
	'admin/users/' => array(
		0 => '\Cerceau\Controller\Admin\Users',
		1 => 'addUser',
		2 => 'admin/users/',
		'view' => 'Json',
	),
	'admin/users/<\d+>/privileges' => array(
		0 => '\Cerceau\Controller\Admin\Users',
		1 => 'editUserPrivileges',
		2 => 'admin/users/<\d+>/privileges',
		'params' => array(
			0 => 'admin_id',
		),
		'view' => 'Json',
	),
	'auth' => array(
		0 => '\Cerceau\Controller\Main',
		1 => 'auth',
		2 => 'auth',
		'view' => 'Json',
	),
);