<?php
	$blogs = ORM::factory('Blog')->find_all();
	foreach ($blogs as $blog)
	{
		Route::set('blog_'.$blog->id, $blog->permalink)->defaults(array(
	        'controller' => 'Blog',
	        'action' => 'blog',
	        'blog' => $blog->id
	    ));
		
		$posts = $blog->posts->find_all();
		foreach ($posts as $post)
		{
			Route::set('blog_'.$blog->id.'_'.$post->id, $blog->permalink.'/'.$post->permalink)->defaults(array(
	        'controller' => 'Blog',
	        'action' => 'post',
	        'blog' => $blog->id,
	        'post' => $post->id
	    ));
		}
	}