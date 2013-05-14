<?php
	$date_range = explode(' - ', Arr::get($params, 'date_range'));
	$start_date = date('Y-m-d 00:00:00', strtotime($date_range[0]));
	$end_date = date('Y-m-d 23:59:59', strtotime($date_range[1]));
?>

<?php
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
	
	$blogs_posts = array();
	foreach ($blogs_posts_orm as $blogs_post)
	{
		$blogs_posts[$blogs_post->date_viewed_group] = (int) $blogs_post->view_count;
	}
?>
<div class="span4">
	<h5>Blog Posts Viewed by Date</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Date</th>
				<th style="text-align:right;">Views</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($blogs_posts as $date => $view_count)
			{
				echo '<tr>';
				echo '<td>'.$date.'</td>';
				echo '<td style="text-align:right;">'.number_format($view_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>

<?php
	$blogs_posts_orm = ORM::factory('Blogs_Post');
	$blogs_posts_orm->select(DB::expr('DATE_FORMAT(date_viewed, "%m-%d-%Y") AS date_viewed_group'));
	$blogs_posts_orm->select(DB::expr('COUNT(pageviews.id) AS view_count'));
	$blogs_posts_orm->join('blogs');
	$blogs_posts_orm->on('blogs_post.blog_id', '=', 'blogs.id');
	$blogs_posts_orm->join('pageviews');
	$blogs_posts_orm->on(DB::expr('CONCAT("/", blogs.permalink, "/", blogs_post.permalink)'), '=', 'pageviews.page_url');
	$blogs_posts_orm->where('date_viewed', 'BETWEEN', array($start_date, $end_date));
	$blogs_posts_orm->group_by('blogs_post.id');
	$blogs_posts_orm->order_by('view_count', 'desc');
	$blogs_posts_orm->limit(10);
	$blogs_posts_orm = $blogs_posts_orm->find_all();
	
	$blogs_posts = array();
	foreach ($blogs_posts_orm as $blogs_post)
	{
		$blogs_posts[$blogs_post->title] = (int) $blogs_post->view_count;
	}
?>
<div class="span4">
	<h5>Most Viewed Blog Posts</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Title</th>
				<th style="text-align:right;">Views</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($blogs_posts as $title => $view_count)
			{
				echo '<tr>';
				echo '<td>'.$title.'</td>';
				echo '<td style="text-align:right;">'.number_format($view_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>

<?php
	$blogs_posts_orm = ORM::factory('Blogs_Post');
	$blogs_posts_orm->select(DB::expr('DATE_FORMAT(date_posted, "%m-%d-%Y") AS date_posted_group'));
	$blogs_posts_orm->select(DB::expr('COUNT(comments_posts.id) AS comment_count'));
	$blogs_posts_orm->join('blogs');
	$blogs_posts_orm->on('blogs_post.blog_id', '=', 'blogs.id');
	$blogs_posts_orm->join('comments');
	$blogs_posts_orm->on(DB::expr('CONCAT("/", blogs.permalink, "/", blogs_post.permalink)'), '=', 'comments.page_url');
	$blogs_posts_orm->join('comments_posts');
	$blogs_posts_orm->on('comments_posts.comment_id', '=', 'comments.id');
	$blogs_posts_orm->where('date_posted', 'BETWEEN', array($start_date, $end_date));
	$blogs_posts_orm->group_by('blogs_post.id');
	$blogs_posts_orm->order_by('comment_count', 'desc');
	$blogs_posts_orm->limit(10);
	$blogs_posts_orm = $blogs_posts_orm->find_all();
	
	$blogs_posts = array();
	foreach ($blogs_posts_orm as $blogs_post)
	{
		$blogs_posts[$blogs_post->title] = (int) $blogs_post->comment_count;
	}
?>
<div class="span4">
	<h5>Most Commented Blog Posts</h5>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Title</th>
				<th style="text-align:right;">Comments</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($blogs_posts as $title => $comment_count)
			{
				echo '<tr>';
				echo '<td>'.$title.'</td>';
				echo '<td style="text-align:right;">'.number_format($comment_count).'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>