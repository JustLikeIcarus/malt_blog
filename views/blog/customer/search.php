<div class="page-header">
	<h1>Search <small><?php echo $tag ?></small></h1>
</div>

<?php
	$list_view = View::factory('blog/customer/list');
	$list_view->posts = $posts;
	$list_view->pagination = $pagination;
	echo $list_view;
?>