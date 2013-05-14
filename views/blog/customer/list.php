<div>
	<?php
		echo $pagination;
		foreach ($posts as $post)
		{
			$post_summary = View::factory('blog/customer/post_summary');
			$post_summary->post = $post;
			
			echo $post_summary;
		}
	?>
</div>