<?php
$config = array(
	'general' => array(
		'version' => '0.1',
		'site_name' => 'open source system monitoring'
	),
	'attachments' => array(
		'allowedExtensions' => array('doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'pdf', 'ppt', 'pptx'),
		'path' => APP . 'webroot/files/attachments/'
	),
	'ckeditor' => array(
		'allowedExtensions' => array('png', 'jpg', 'jpeg'),
		'path' => APP . 'webroot/files/images/'
	),
	'languages' => array(
		'en-us' => 'english'
	),
	'paths' => array(
		'lessc' => array(
			Environments::DEVELOPMENT  => 'lessc',
			Environments::STAGING => '/root/node_modules/less/bin/lessc',
			Environments::PRODUCTION => '/root/node_modules/less/bin/lessc'
		)
	),
);