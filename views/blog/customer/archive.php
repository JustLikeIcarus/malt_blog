<div class="page-header">
	<h1>Archives <small><?php echo $blog->title.' '.date('F Y', strtotime($date)) ?></small></h1>
</div>

<?php
	$list_view = View::factory('blog/customer/list');
	$list_view->posts = $posts;
	$list_view->pagination = $pagination;
	echo $list_view;
?>