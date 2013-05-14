<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Blog extends ORM {
   protected $_has_many = array(
	    'posts' => array(
	        'model'   => 'Blogs_Post'
	    )
    );
	
	public function save(Validation $validation = null)
	{
		$this->date_modified = date('Y-m-d H:i:s');
		parent::save($validation);
	}
	
	public function get_permalink($full_url = true)
	{
		$permalink = '';
		if ($full_url)
		{
			$permalink = 'http://'.Kohana::$config->load('website')->get('url');
		}
		$permalink.= '/'.$this->permalink;
		
		return $permalink;
	}
	
	public function get_posts($params = array())
	{
		$this_blog = Arr::get($params, 'this_blog', true);
		$page_limit = Arr::get($params, 'page_limit', 10);
		$tag = Arr::get($params, 'tag', false);
		$category = Arr::get($params, 'category_id', false);
		$q = Arr::get($params, 'q', false);
		$page = Arr::get($params, 'page', 1);
		$order_by = Arr::get($params, 'order_by', 'date_published');
		$order_direction = Arr::get($params, 'order_direction', 'desc');
		$status = Arr::get($params, 'status', 'published');
		
		$posts = array();
		if ($this_blog AND $this->loaded())
		{
			$posts = $this->posts;
		}
		else
		{
			$posts = ORM::factory('Blogs_Post');
		}
		if ($q)
		{
			$posts->where_open();
			$posts->where('title', 'LIKE', '%'.$q.'%');
			$posts->where('content', 'LIKE', '%'.$q.'%');
			$posts->where_close();
		}
		if ($tag)
		{
			$tag = ORM::factory('Tag')->where('name', '=', $tag)->find();
			$posts->join('blogs_posts_tags');
			$posts->on('blogs_post.id', '=', 'blogs_posts_tags.blogs_post_id');
			$posts->where('blogs_posts_tags.tag_id', '=', $tag->id);
		}
		if ($category)
		{
			$category = ORM::factory('Category')->where('id', '=', $category)->find();
			$posts->join('blogs_posts_categories');
			$posts->on('blogs_post.id', '=', 'blogs_posts_categories.blogs_post_id');
			$posts->where('blogs_posts_categories.category_id', '=', $category->id);
		}
		if ($status)
		{
			$posts->where('status', '=', $status);
			$posts->where('date_published', '<', date('Y-m-d H:i:s'));
		}
		$posts_count = clone $posts;
		$total_items = $posts_count->count_all();
		
		$posts = $posts->order_by($order_by, $order_direction)
			->limit($page_limit)
			->offset($page_limit*($page-1))
			->find_all();
		$pagination = Pagination::factory(array(
            'items_per_page' => $page_limit,
            'total_items' => $total_items
        ));
		
		$return = new stdClass;
        $return->pagination = $pagination;
        $return->posts = $posts;
		
		return $return;
	}
	
	public function get_posts_count($this_blog = true)
	{
		$posts = array();
		if ($this_blog)
		{
			$posts = $this->posts;
		}
		else
		{
			$posts = ORM::factory('Blogs_Post');
		}
		$count = $posts->where('status', '=', 'published')
			->where('date_published', '<', date('Y-m-d H:i:s'))
			->count_all();
		return $count;
	}
	
	public function get_tags($this_blog = true, $limit = 10)
	{
		$tags = array();
		if ($this_blog)
		{
			$blog_posts = $this->posts;
		}
		else
		{
			$blog_posts = ORM::factory('Blogs_Post');
		}
		$blog_posts = $blog_posts->where('status', '=', 'published')
			->where('date_published', '<', date('Y-m-d H:i:s'))
			->find_all();
		
		foreach ($blog_posts as $blog_post)
		{
			$blog_post_tags = $blog_post->tags->find_all();
			
			foreach ($blog_post_tags as $blog_post_tag)
			{
				$tags[$blog_post_tag->name] = Arr::get($tags, $blog_post_tag->name, 0)+1;
			}
		}
		arsort($tags);
		
		$count = 1;
		foreach ($tags as $key => $tag)
		{
			if ($count > $limit)
			{
				unset($tags[$key]);
			}
			$count++;
		}
		
		return $tags;
	}
	
	public function get_archives($this_blog = true, $limit = 10, $page = 1)
	{
		$archives = array();
		if ($this_blog)
		{
			$posts = $this->posts;
		}
		else
		{
			$posts = ORM::factory('Blogs_Post');
		}
		$posts = $posts->where('status', '=', 'published')
			->order_by('date_published', 'DESC')
			->where('date_published', '<', date('Y-m-d H:i:s'))
			->limit($limit)
			->offset($limit*($page-1))
			->find_all();
		
		foreach ($posts as $post)
		{
			$month_date = date('Y-m', strtotime($post->date_published));
			$archives[$month_date] = $month_date;
		}
		arsort($archives);
		
		$count = 1;
		foreach ($archives as $key => $archive)
		{
			if ($count > $limit)
			{
				unset($archives[$key]);
			}
			$count++;
		}
		
		return $archives;
	}
	
	public function get_posts_by_date($date, $this_blog = true, $limit = 10, $page = 1)
	{
		if ($this_blog)
		{
			$posts = $this->posts;
		}
		else
		{
			$posts = ORM::factory('Blogs_Post');
		}
		$posts = $posts->where('date_published', '>', date('Y-m-d H:i:s', strtotime($date.'-01 00:00:00')))
			->where('date_published', '<', date('Y-m-d H:i:s', strtotime($date.'-01'.' +1 month')))
			->where('date_published', '<', date('Y-m-d H:i:s'))
			->where('status', '=', 'published')
			->order_by('date_published', 'DESC')
			->limit($limit)
			->offset($limit*($page-1))
			->find_all();
		
		return $posts;
	}
	
	public function get_posts_by_date_count($date, $this_blog = true)
	{
		if ($this_blog)
		{
			$posts = $this->posts;
		}
		else
		{
			$posts = ORM::factory('Blogs_Post');
		}
		$count = $posts->where('date_published', '>', date('Y-m-d H:i:s', strtotime($date.'-01 00:00:00')))
			->where('date_published', '<', date('Y-m-d H:i:s', strtotime($date.'-01'.' +1 month')))
			->where('status', '=', 'published')
			->where('date_published', '<', date('Y-m-d H:i:s'))
			->count_all();
		return $count;
	}
	
	public function get_posts_by_tag($params = array())
	{
		$this_blog = Arr::get($params, 'this_blog', true);
		$page_limit = Arr::get($params, 'page_limit', 10);
		$page = Arr::get($params, 'page', 1);
		$order_by = Arr::get($params, 'order_by', 'date_published');
		$order_direction = Arr::get($params, 'order_direction', 'desc');
		$status = Arr::get($params, 'status', 'published');
		$q = Arr::get($params, 'q', '');
		
		$posts = array();
		
		$tags_orm = ORM::factory('Tag');
		$tags = $tags_orm->where('name', 'LIKE','%'.$q.'%' )->find_all();
		foreach ($tags as $tag)
		{
			$found_posts = ORM::factory('Blogs_Posts_Tag')->where('tag_id', '=', $tag->id)->find_all();
			foreach ($found_posts as $fp)
			{
				$tags_posts = ORM::factory('Blogs_Post')->where('id', '=', $fp->blogs_post_id);
				$posts = $tags_posts;
			}	
		}
		
		$posts_count = clone $posts;
		$total_items = $posts_count->count_all();
		
		$posts = $posts->order_by($order_by, $order_direction)
			->limit($page_limit)
			->offset($page_limit*($page-1))
			->find_all();
		
		$pagination = Pagination::factory(array(
            'items_per_page' => $page_limit,
            'total_items' => $total_items
        ));
		
		$return = new stdClass;
        $return->pagination = $pagination;
        $return->posts = $posts;
		
		return $return;
	}

	public function get_top_viewed_posts($params = array())
	{
		$date_range = explode(' - ', Arr::get($params, 'date_range'));
		$start_date = date('Y-m-d 00:00:00', strtotime($date_range[0]));
		$end_date = date('Y-m-d 23:59:59', strtotime($date_range[1]));
	
		$blogs_posts_orm = ORM::factory('Blogs_Post');
		$blogs_posts_orm->select(DB::expr('DATE_FORMAT(date_viewed, "%m-%d-%Y") AS date_viewed_group'));
		$blogs_posts_orm->select(DB::expr('COUNT(pageviews.id) AS view_count'));
		$blogs_posts_orm->join('blogs');
		$blogs_posts_orm->on('blogs_post.blog_id', '=', 'blogs.id');
		$blogs_posts_orm->join('pageviews');
		$blogs_posts_orm->on(DB::expr('CONCAT("/", blogs.permalink, "/", blogs_post.permalink)'), '=', 'pageviews.page_url');
		$blogs_posts_orm->where('date_viewed', 'BETWEEN', array($start_date, $end_date));
		$blogs_posts_orm->group_by(DB::expr('YEAR(date_viewed)'));
		$blogs_posts_orm->group_by(DB::expr('MONTH(date_viewed)'));
		$blogs_posts_orm->group_by(DB::expr('DAY(date_viewed)'));
		$blogs_posts_orm = $blogs_posts_orm->find_all();
		
		return $blogs_posts_orm;
	}
}