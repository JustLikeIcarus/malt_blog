<link href="<?php echo $blog->get_permalink().'?rss=true' ?>" rel="alternate" type="application/atom+xml" title="<title>"/>
<div class="page-header">
	<h1><?php echo $blog->title ?> <small><?php echo $blog->tagline ?></small></h1>
</div>

<?php
	$list_view = View::factory('blog/customer/list');
	$list_view->posts = $posts;
	$list_view->pagination = $pagination;
	echo $list_view;
?>