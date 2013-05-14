<?php
	$blog_post = ORM::factory('Blogs_post', $result->id);
?>
<div class="row">
	<div class="search_result span12">
		<div class="row">
			<div class="search_result_type span2">
				<div>
					<i class="icon-file-alt"></i><br/>
					Blog Post
				</div>
			</div>
			<div class="search_result_info span9">
				<div>
					<div class="search_result_title"><?php echo HTML::anchor($blog_post->get_permalink(), $blog_post->title); ?></div>
					<div class="search_result_content">
						<?php
							echo $blog_post->get_summary('short').' ';
							echo HTML::anchor($blog_post->get_permalink(), 'Read More');
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>