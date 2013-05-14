<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Blogs_Post extends ORM {
   protected $_has_many = array(
	    'tags' => array(
	        'model'   => 'Tag',
			'through' => 'blogs_posts_tags',
	    ),
	    'categories' => array(
	        'model'   => 'Category',
			'through' => 'blogs_posts_categories',
	    ),
    );
	
	protected $_belongs_to = array(
		'image' => array(
        	'model' => 'Asset',
            'foreign_key' => 'featured_image'
		),
		'author' => array(
        	'model' => 'Blog_Author',
            'foreign_key' => 'author_id'
		),
		'blog' => array(
		),
	);
	
	public function save(Validation $validation = null)
	{
		$this->date_modified = date('Y-m-d H:i:s');
		parent::save($validation);
		
		if (Arr::get(Kohana::modules(), 'search', FALSE)) 
		{
			$type = str_replace('Model_', '', get_class($this));
			$search_params = array('type' => $type);
			$search = new Model_Search($search_params);
			if ($this->status == 'published' AND $this->date_published <= date('Y-m-d H:i:s'))
			{
				$result = $search->index($this->as_array(), $type);
			}
			else
			{
				$result = $search->delete($this->id, $type);
			}
		}
	}
	
	public function get_permalink($full_url = true)
	{
		return $this->blog->get_permalink($full_url).'/'.$this->permalink;
	}
	
	public function get_comments_link()
	{
		return $this->get_permalink().'?show_comments=true';
	}

	public function get_summary($type = 'short')
	{
		$words = 25;
		switch ($type)
		{
			case 'xlong':
				$words = 80;
				break;
			case 'long':
				$words = 50;
				break;
			case 'medium':
				$words = 35;
				break;
			default:
			case 'short':
				$words = 25;
				break;
		}
		
		$content = $this->content;
		$content = strip_tags($content);
		$content = Text::limit_words($content, $words, '...');
		return $content;
	}
	
	public function get_post_views($post_id)
	{
		$blogs_posts_orm = ORM::factory('Blogs_Post');
		$blogs_posts_orm->select(DB::expr('COUNT(pageviews.id) AS view_count'));
		$blogs_posts_orm->join('blogs');
		$blogs_posts_orm->on('blogs_post.blog_id', '=', 'blogs.id');
		$blogs_posts_orm->join('pageviews');
		$blogs_posts_orm->on(DB::expr('CONCAT("/", blogs.permalink, "/", blogs_post.permalink)'), '=', 'pageviews.page_url');
		$blogs_posts_orm->where('blogs_post.id', '=', $post_id);
		$blogs_posts_orm = $blogs_posts_orm->find();
		
		return $blogs_posts_orm->view_count;
	}
}