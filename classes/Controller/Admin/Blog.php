<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Blog extends Controller_Admin_Website {
	
	public function before()
	{
		parent::before();
		
		$this->template->content_title = 'Blog';
	}
	
	public function after()
    {
    	$sidebar_navigation_view = View::factory('blog/admin/navigation');
		
		$request = Request::initial();
		$requested_controller = str_replace('Admin_', '', $request->controller());
		$sidebar_navigation_view->requested_controller = $requested_controller;
		$requested_action = $request->action();
		
		$blogs = ORM::factory('Blog')->find_all();
		$sidebar_navigation_view->blogs = $blogs;
		
		$posts = ORM::factory('Blogs_Post')->order_by('date_modified', 'DESC')->limit(5)->find_all();
		$sidebar_navigation_view->posts = $posts;
        
        $this->template->sidebar_navigation = $sidebar_navigation_view;
		
        parent::after();
    }
    
    public function action_index()
    {
    	$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array();
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
        $view = View::factory('blog/admin/index');
		
		$params = array(
			'group_by' => 'day',
			'date_range' => date('Y-m-01', strtotime('now')).' - '.date('Y-m-d', strtotime('now'))
		);
		$pages = array();
		$blogs = ORM::factory('Blog')->find_all();
		foreach ($blogs as $blog)
		{
			$pages[] = $blog->get_permalink(false);
		}
		
		$blog_posts = ORM::factory('Blogs_Post')->find_all();
		foreach ($blog_posts as $blog_post)
		{
			$pages[] = $blog_post->get_permalink(false);
		}
		$params['pages'] = $params;
		
		$pageviews_stats = ORM::factory('Pageview')->get_pageviews($params);
		
		$views_stats = array();
		foreach ($pageviews_stats as $stat)
		{
			$views_stats[$stat->date_viewed_group] = (int) $stat->view_count;
		}
		$views_chart = View::factory('admin/stats/bar_graph');
		$views_chart->chart_title = 'Page Views - This Month';
		$views_chart->stats = $views_stats;
		$view->views_chart = $views_chart;
		
        $this->template->body = $view;
    }
	
	public function action_add_blog()
	{
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/add_blog' => 'Add Blog',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_blog');
		$content_title = 'Add Blog';
		$view->content_title = $content_title;
		
		$blog = ORM::factory('Blog');
		$view->blog = $blog;
		
		$authors_orm = ORM::factory('Blog_Author')->where('status', '=', 1)->find_all();
		$authors = array(0 => 'No Default Author');
		foreach ($authors_orm as $author)
		{
			$authors[$author->id] = $author->name;
		}
		$view->authors = $authors;
		
        $this->template->body = $view;
	}
	
	public function action_edit_blog()
	{
		$blog_id = $this->request->param('id');
		
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/edit_blog/'.$blog_id => 'Edit Blog',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_blog');
		$content_title = 'Edit Blog';
		$view->content_title = $content_title;
		
		$blog = ORM::factory('Blog', $blog_id);
		
		$content_header_navigation = HTML::anchor($blog->get_permalink(), 'View Blog', array('class' => 'btn btn-small', 'target' => '_BLANK'));
		$this->template->content_header_navigation = $content_header_navigation;
		
		$view->blog = $blog;
		
		$authors_orm = ORM::factory('Blog_Author')->where('status', '=', 1)->find_all();
		$authors = array(0 => 'No Default Author');
		foreach ($authors_orm as $author)
		{
			$authors[$author->id] = $author->name;
		}
		$view->authors = $authors;
		
        $this->template->body = $view;
	}
	
	public function action_save_blog()
	{
		$post = Arr::get($_POST, 'blog', false);
		
		if ($post)
		{
			foreach ($post as $key => $value)
	        {
	            switch ($key)
	            {
	                case 'id':
	                    if ($value == 0)
	                    {
	                        $blog = ORM::factory('Blog');
	                    }
	                    else
	                    {
	                        $blog = ORM::factory('Blog', $value);
	                    }
	                    break;
	                default:
	                    $blog->$key = $value;
	                    break;
	            }
	        }
			$blog->status = Arr::get($post, 'status', 0);
			$blog->save();
			
			Notice::add(Notice::SUCCESS, 'Blog Saved.');
        	$this->redirect('/admin_blog/blog/'.$blog->id);
		}
	}
	
	public function action_blog()
	{
		$blog_id = $this->request->param('id');
		
		$view = View::factory('blog/admin/blog');
		
		$blog = ORM::factory('Blog', $blog_id);
		
		$content_header_navigation = HTML::anchor($blog->get_permalink(), 'View Blog', array('class' => 'btn btn-small', 'target' => '_BLANK'));
		$content_header_navigation.= HTML::anchor('/admin_blog/edit_blog/'.$blog->id, '<i class="icon-pencil"></i> Edit Blog', array('class' => 'btn btn-small'));
		$this->template->content_header_navigation = $content_header_navigation;
		
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/blog/'.$blog->id => $blog->title,
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view->blog = $blog;
		
		$params = array(
			'page' => Arr::get($_GET, 'page', 1),
			'status' => false
		);
		
		$posts = $blog->get_posts($params);
		$view->posts = $posts->posts;
		$view->pagination = $posts->pagination;
		
		$content_title = 'Blog '.$blog->title;
		$view->content_title = $content_title;
		
        $this->template->body = $view;
	}

	public function action_authors()
	{
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/authors/' => 'Authors',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$content_header_navigation = HTML::anchor('/admin_blog/add_author', '<i class="icon icon-plus"></i> Add Author', array('class' => 'btn btn-small btn-success'));
		$this->template->content_header_navigation = $content_header_navigation;
		
		$view = View::factory('blog/admin/authors');
		$authors = ORM::factory('Blog_Author')->find_all();
		$view->authors = $authors;
		
		$this->template->body = $view;
	}
	
	public function action_add_author()
	{
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/authors' => 'Authors',
			'/admin_blog/add_author' => 'Add Author',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_author');
		$author = ORM::factory('Blog_Author');
		$view->author = $author;
		
		$this->template->body = $view;
	}
	
	public function action_edit_author()
	{
		$author_id = $this->request->param('id');
		
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/authors' => 'Authors',
			'/admin_blog/add_author/'.$author_id => 'Edit Author',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_author');
		$author = ORM::factory('Blog_Author', $author_id);
		$view->author = $author;
		
		$this->template->body = $view;
	}
	
	public function action_save_author()
	{
		$post = $_POST;
		
		$author_id = Arr::get($post, 'id', false);
		if ($author_id)
		{
			$author = ORM::factory('Blog_Author', $author_id);
		}
		else
		{
			$author = ORM::factory('Blog_Author');
		}
		$author->name = Arr::get($post, 'name');
		$author->description = Arr::get($post, 'description');
		$author->image_id = Arr::get($post, 'image_id', 0);
		$author->status = Arr::get($post, 'status', 0);
		$author->save();
		
		Notice::add(NOTICE::SUCCESS, 'Author saved.');
		$this->redirect('/admin_blog/authors');
	}
	
	public function action_add_post()
	{
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/add_post/' => 'Add Post',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_post');
		$content_title = 'Add Post';
		$view->content_title = $content_title;
		
		$statuses = array(
			'draft' => 'Draft',
			'review' => 'Review',
			'published' => 'Published',
		);
		$view->statuses = $statuses;
		
		$featured_image_sizes = array(
			'tiny' => 'Tiny Original',
			'tiny_square' => 'Tiny Square',
			'tiny_wide' => 'Tiny 16x9',
			'small' => 'Small Original',
			'small_square' => 'Small Square',
			'small_wide' => 'Small 16x9',
			'medium' => 'Medium',
		);
		$view->featured_image_sizes = $featured_image_sizes;
		
		$blog_id = Arr::get($_GET, 'blog_id', 0);
		if ($blog_id == 0)
		{
			$blog = ORM::factory('Blog')->find();
		}
		else
		{
			$blog = ORM::factory('Blog', $blog_id);
		}
		
		$authors_orm = ORM::factory('Blog_Author')->where('status', '=', 1)->find_all();
		$authors = array(0 => 'No Author');
		foreach ($authors_orm as $author)
		{
			if ($author->id == $blog->default_author)
			{
				$author->name.=' (Default)';
			}
			$authors[$author->id] = $author->name;
		}
		$view->authors = $authors;
		
		$categories = ORM::factory('Category')->get_all_categories();
		$view->categories = $categories;
		$view->selected_category = '';
		
		$post = ORM::factory('Blogs_Post');
		$post->author_id = $blog->default_author;
		$post->blog = $blog;
		$view->post = $post;
		
        $this->template->body = $view;
	}
	
	public function action_edit_post()
	{
		$post_id = $this->request->param('id');
		
		$breadcrumb = View::factory('blog/admin/breadcrumb');
		$breadcrumb_items = array(
			'/admin_blog/edit_post/'.$post_id => 'Edit Post',
		);
		$breadcrumb->items = $breadcrumb_items;
		$this->template->breadcrumb = $breadcrumb;
		
		$view = View::factory('blog/admin/add_edit_post');
		$content_title = 'Edit Post';
		$view->content_title = $content_title;
		
		$statuses = array(
			'draft' => 'Draft',
			'review' => 'Review',
			'published' => 'Published',
		);
		$view->statuses = $statuses;
		
		$featured_image_sizes = array(
			'tiny' => 'Tiny Original',
			'tiny_square' => 'Tiny Square',
			'tiny_wide' => 'Tiny 16x9',
			'small' => 'Small Original',
			'small_square' => 'Small Square',
			'small_wide' => 'Small 16x9',
			'medium' => 'Medium',
		);
		$view->featured_image_sizes = $featured_image_sizes;
		
		$authors_orm = ORM::factory('Blog_Author')->where('status', '=', 0)->find_all();
		$authors = array();
		foreach ($authors_orm as $author)
		{
			$authors[$author->id] = $author->name;
		}
		$view->authors = $authors;
		
		$post = ORM::factory('Blogs_Post', $post_id);
		$view->post = $post;
		
		$this->template->content_header_navigation = HTML::anchor($post->get_permalink(), 'View Post', array('class' => 'btn', 'target' => '_blank'));
		
		$authors_orm = ORM::factory('Blog_Author')->where('status', '=', 1)->find_all();
		$authors = array(0 => 'No Author');
		foreach ($authors_orm as $author)
		{
			if ($author->id == $post->blog->default_author)
			{
				$author->name.=' (Default)';
			}
			$authors[$author->id] = $author->name;
		}
		$view->authors = $authors;
		
		$selected_category = $post->categories->find();
		$view->selected_category = $selected_category->id;
		
		$categories = ORM::factory('Category')->get_all_categories();
		$view->categories = $categories;
		
        $this->template->body = $view;
	}
	
	public function action_save_post()
	{
		$post = $_POST;
		
		$id = Arr::get($post, 'id', false);
		if ($id)
        {
        	$blog_post = ORM::factory('Blogs_Post', $id);
        }
        else
        {
            $blog_post = ORM::factory('Blogs_Post');
			$blog_post->date_created = date('Y-m-d H:i:s');
        }
        $blog_post->blog_id = Arr::get($post, 'blog_id', 0);
		$blog_post->author_id = Arr::get($post, 'author_id', 0);
		$blog_post->title = Arr::get($post, 'title', '');
		$blog_post->content = Arr::get($post, 'content', '');
		$blog_post->featured_image = Arr::get($post, 'featured_image', 0);
		$blog_post->featured_image_size = Arr::get($post, 'featured_image_size', 'small');
		$blog_post->permalink = Arr::get($post, 'permalink', '');
        $blog_post->source = Arr::get($post, 'source', '');
        $blog_post->via = Arr::get($post, 'via', '');
		$blog_post->date_modified = date('Y-m-d H:i:s');
		$blog_post->status = Arr::get($post, 'status', 'draft');
		
		$blog_post->date_published = Format::database_datetime(Arr::get($post, 'date_published'));
		
		if (Arr::get($post, 'date_created', false))
		{
			$blog_post->date_created = Format::database_datetime(Arr::get($post, 'date_created'));
		}
		$blog_post->save();
		
		$tags = Arr::get($post, 'hidden-tags', false);
		
		if ($tags AND $tags != '')
		{
			$tags = explode(',', $tags);
			$tag_model = new Model_Tag;
			$tag_model->add_tags($tags, $blog_post);
		}
		
		$category = Arr::get($post, 'category', false);
		
		if ($category)
		{
			$ORM_category = ORM::factory('Category', $category);
			$blog_post->add('categories', $category);
		}
		
		Notice::add(Notice::SUCCESS, 'Post Saved.');
    	$this->redirect('/admin_blog/blog/'.$blog_post->blog->id);
	}

	public function action_delete_post()
	{
		$post_id = $this->request->param('id');
		
		$post = ORM::factory('Blogs_Post', $post_id);
		$blog_id = $post->blog->id;
		foreach ($post->tags->find_all() as $tag)
		{
			$post->remove('tags', $tag);
		}
		foreach ($post->categories->find_all() as $category)
		{
			$post->remove('categories', $category);
		}
		$post->delete();
		
		Notice::add(Notice::SUCCESS, 'Post Deleted.');
    	$this->redirect('/admin_blog/blog/'.$blog_id);
	}

	public function action_check_permalink()
	{
		$routes = Route::all();
		echo Debug::vars($routes);
		die();
	}
	
	public function action_search_index_posts()
	{
		if (Arr::get(Kohana::modules(), 'search', FALSE)) 
		{
			$blogs_posts = ORM::factory('Blogs_Post')->find_all();
			
			foreach ($blogs_posts as $blog_post)
			{
				$type = str_replace('Model_', '', get_class($blog_post));
				$search_params = array('type' => $type);
				$search = new Model_Search($search_params);
				if ($blog_post->status == 'published' AND $blog_post->date_published <= date('Y-m-d H:i:s'))
				{
					$result = $search->index($blog_post->as_array(), $type);
				}
				else
				{
					$result = $search->delete($blog_post->id, $type);
				}
			}
		}
		die();
	}
}