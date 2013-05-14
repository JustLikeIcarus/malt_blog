<?php
	$this_blog = $blog;
?>
<div class="span12">
	<div class="row">
		<div class="span3 blog_navigation">
			<ul class="nav nav-list">
	            <li class="nav-header">Blogs</li>
            	<li>
            		<ul class="nav nav-list">
	            <?php
	            	if (count($blogs) > 0)
					{
		            	foreach ($blogs as $blog)
						{
							$li_class = '';
							
							if ($this_blog->id == $blog->id)
							{
								$li_class = 'active';
							}
							echo '<li class="'.$li_class.'">';
							echo HTML::anchor($blog->get_permalink(), $blog->title);
							echo '</li>';
						}
					}
					else
					{
						echo '<li>';
						echo 'No blogs exist.';
						echo '</li>';
					}
	            ?>
	            	</ul>
            	</li>
	            <li class="divider"></li>
	            <li class="nav-header">Recent Posts</li>
	            <li>
            		<ul class="nav nav-list">
	            <?php
	            	if (count($posts) > 0)
					{
						foreach ($posts as $post)
						{
							echo '<li>';
							echo HTML::anchor($post->get_permalink(), $post->title);
							echo '</li>';
						}
					}
					else
					{
						echo '<li>';
						echo 'No recent posts.';
						echo '</li>';
					}
	            ?>
	            	</ul>
            	</li>
	            <li class="divider"></li>
	            <li class="nav-header">Archive</li>
	            <li>
            		<ul class="nav nav-list">
    			<?php
	            	if (count($archives) > 0)
					{
						foreach ($archives as $archive)
						{
							$query_array = array(
								'date' => $archive
							);
							if ($this_blog->id > 0)
							{
								$query_array['blog'] = $this_blog->id;
							}
							echo '<li>';
							echo HTML::anchor('/blog/archive?'.http_build_str($query_array), date('F Y', strtotime($archive)));
							echo '</li>';
						}
					}
					else
					{
						echo '<li>';
						echo 'No archives.';
						echo '</li>';
					}
	            ?>
	            	</ul>
            	</li>
	            <li class="divider"></li>
	            <li class="nav-header">Tags</li>
	            <li>
            		<ul class="nav nav-list">
	            <?php
	            	foreach ($tags as $tag => $count)
					{
						$query_array = array(
							'tag' => $tag
						);
						if ($this_blog->id > 0)
						{
							$query_array['blog'] = $this_blog->id;
						}
						echo '<li>'.HTML::anchor('/blog/search?'.http_build_str($query_array), $tag).'</li>';
					}
	            ?>
	            	</ul>
            	</li>
	        </ul>
		</div>
		<div class="span9 blog_content">
			<?php echo $blog_item ?>
		</div>
	</div>
</div>