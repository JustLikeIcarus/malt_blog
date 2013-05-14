<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Malt_Blog extends Controller_Website {
	
    public function action_index()
    {
    	$view = View::factory('blog/customer/blogs');
		
		$blog = ORM::factory('Blog');
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5));
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags(false, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives(false, 10);
		$view->archives = $archives;
		
		$blog_view = View::factory('blog/customer/index');
		$blog_view->blog = $blog;
		
		$posts = $blog->get_posts(array('page_limit' => 10));
		$blog_view->posts = $posts->posts;
		
		$view->blog_item = $blog_view;
		
		$this->template->body = $view;
	}
	
	public function action_blog()
	{
		$blog_id = $this->request->param('blog');
		
		$page = Arr::get($_GET, 'page', 1);
		$rss = Arr::get($_GET, 'rss', false);
		
		if($rss === false)
		{
			$view = View::factory('blog/customer/blogs');
		}
		else
		{
			$view = View::factory('blog/rss/blog');
		}
		$blog = ORM::factory('Blog', $blog_id);
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5));
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags(true, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives(true, 10);
		$view->archives = $archives;
		
		$blog_view = View::factory('blog/customer/blog');
		$blog_view->blog = $blog;
		
		$params = array(
			'page' => Arr::get($_GET, 'page', 1),
		);
		
		$posts = $blog->get_posts($params);
		$blog_view->posts = $posts->posts;
		$blog_view->pagination = $posts->pagination;
		
		$view->blog_item = $blog_view;
		
		// Set Meta headers
		$header_vars_view = View::factory('blog/meta/blog');
		$header_vars_view->title = $blog->title;
		$header_vars_view->description = $blog->tagline;
		$header_vars_view->type = 'blog';
		$this->header_vars = $header_vars_view;
		
		$this->template->body = $view;
	}
	
	public function action_post()
	{
		$blog_id = $this->request->param('blog');
		$post_id = $this->request->param('post');
		
		$view = View::factory('blog/customer/blogs');
		
		$blog = ORM::factory('Blog', $blog_id);
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5));
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags(true, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives(true, 10);
		$view->archives = $archives;
		
		$post_view = View::factory('blog/customer/post');
		
		$post = ORM::factory('Blogs_Post', $post_id);
		
		$post_view->post = $post;
		$view->blog_item = $post_view;
		
		// Set Meta headers
		$header_vars_view = View::factory('blog/meta/post');
		$header_vars_view->author = $post->author;
		$header_vars_view->title = $post->title;
		$header_vars_view->image_url = ORM::factory('Asset', $post->featured_image)->files->where('type', '=', 'image_large')->find()->url;
		$header_vars_view->description = $post->get_summary($type = 'short');
		$header_vars_view->type = 'blog';
		$this->header_vars = $header_vars_view;
		
		$this->template->body = $view;
	}
	
	public function action_archive()
	{
		$date = Arr::get($_GET, 'date', date('Y-m'));
		$blog_id = Arr::get($_GET, 'blog', false);
		$page = Arr::get($_GET, 'page', 1);
		
		$view = View::factory('blog/customer/blogs');
		
		if ($blog_id)
		{
			$blog = ORM::factory('Blog', $blog_id);
			$this_blog = true;
		}
		else
		{
			$blog = ORM::factory('Blog');
			$this_blog = false;
		}
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5));
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags($this_blog, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives($this_blog, 10);
		$view->archives = $archives;
		
		$blog_view = View::factory('blog/customer/archive');
		$blog_view->date = $date;
		$blog_view->blog = $blog;
		
		$limit = 10;
		$posts_count = $blog->get_posts_by_date_count($date, $this_blog);
		$posts = $blog->get_posts_by_date($date, $this_blog, $limit, $page);
		$blog_view->posts = $posts;
		
		$pagination = Pagination::factory(array(
            'items_per_page' => $limit,
            'total_items' => $posts_count
        ));
		$blog_view->pagination = $pagination;
		
		$view->blog_item = $blog_view;
		
		$this->template->body = $view;
	}
	
	public function action_search()
	{
		$q = Arr::get($_GET, 'q', '');
		$tag = Arr::get($_GET, 'tag', '');
		$blog_id = Arr::get($_GET, 'blog', false);
		$page = Arr::get($_GET, 'page', 1);
		
		$view = View::factory('blog/customer/blogs');
		
		if ($blog_id)
		{
			$blog = ORM::factory('Blog', $blog_id);
			$this_blog = true;
		}
		else
		{
			$blog = ORM::factory('Blog');
			$this_blog = false;
		}
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$params = array(
			'q' => $q,
			'tag' => $tag,
			'page_limit' => 5,
			'page' => $page
		);
		
		$posts = $blog->get_posts($params);
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags($this_blog, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives($this_blog, 10);
		$view->archives = $archives;
		
		$blog_view = View::factory('blog/customer/search');
		$blog_view->blog = $blog;
		$blog_view->tag = $tag;
		
		$limit = 10;
		$posts_count = $blog->get_posts_count($this_blog);
		$blog_view->posts = $posts->posts;
		
		$pagination = $posts->pagination;
		$blog_view->pagination = $pagination;
		
		$view->blog_item = $blog_view;
		
		$this->template->body = $view;
	}

	public function action_author()
    {
    	$author_id = $this->request->param('id');
		
    	$view = View::factory('blog/customer/blogs');
		
		$blog = ORM::factory('Blog');
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5));
		$view->posts = $posts->posts;
		
		$tags = $blog->get_tags(false, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives(false, 10);
		$view->archives = $archives;
		
		$posts = ORM::factory('Blogs_Post')->where('author_id', '=', $author_id)->count_all();
		
		if ($posts)
		{
			$author = ORM::factory('User', $author_id);
			$blog_view = View::factory('blog/customer/author');
			$blog_view->author = $author;
			$view->blog_item = $blog_view;
			
			// Set Meta headers
			$header_vars_view = View::factory('blog/meta/author');
			$header_vars_view->author = $author;
			$this->header_vars = $header_vars_view;
			
			$this->template->body = $view;
		}
		else
		{
			die();
		}
	}

	public function action_category()
	{
		$blog_id = Arr::get($_GET, 'blog', false);
		$page = Arr::get($_GET, 'page', 1);
		$category_id = Arr::get($_GET, 'category_id');
		
		$view = View::factory('blog/customer/blogs');
		
		if ($blog_id)
		{
			$blog = ORM::factory('Blog', $blog_id);
			$this_blog = true;
		}
		else
		{
			$blog = ORM::factory('Blog');
			$this_blog = false;
		}
		$view->blog = $blog;
		
		$blogs = ORM::factory('Blog')->where('status', '=', 1)->find_all();
		$view->blogs = $blogs;
		
		$posts = $blog->get_posts(array('page_limit' => 5,'category_id' => $category_id));
		$view->posts = $posts->posts;
		
		$category = ORM::factory('Category', $category_id);
		
		$tags = $blog->get_tags(false, 5);
		$view->tags = $tags;
		
		$archives = $blog->get_archives(false, 10);
		$view->archives = $archives;
		
		$blog_view = View::factory('blog/customer/index_sorted');
		$blog_view->blog = $blog;
		
		$blog_view->sorted = $category->name;
		$posts = $blog->get_posts(array('page_limit' => 5,'category_id' => $category_id));
		$blog_view->posts = $posts->posts;
		
		$view->blog_item = $blog_view;
		
		$this->template->body = $view;
	}
}