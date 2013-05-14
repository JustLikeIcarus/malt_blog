<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'admin' => array(
		'content' => array(
			'title' => 'Content',
			'url' => '/admin_content',
			'controller' => 'Content',
			'permission' => 'admin',
			'submenu' => array(
				'blog' => array(
					'title' => 'Blog',
					'url' => '/admin_blog',
					'controller' => 'Blog',
					'permission' => 'blog',
					'icon' => 'icon-align-left'
				),
			)
		)
	)
);
