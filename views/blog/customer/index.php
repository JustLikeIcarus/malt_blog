<div class="page-header">
	<h1>Recent Posts</h1>
</div>

<div>
	<?php
		if (count($posts) == 0)
		{
			echo '<h3>No posts found.</h3>';
		}
		else
		{
			foreach ($posts as $post)
			{
				$post_summary = View::factory('blog/customer/post_summary');
				$post_summary->post = $post;
				echo $post_summary;
			}
		}
	?>
</div>