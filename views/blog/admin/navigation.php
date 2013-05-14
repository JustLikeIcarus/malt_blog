<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Blogs</li>
        <?php
        	if (count($blogs) > 0)
			{
            	foreach ($blogs as $blog)
				{
					echo '<li>';
					echo HTML::anchor('/admin_blog/blog/'.$blog->id, $blog->title);
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
		<li class="divider"></li>
		<?php	
			echo '<li>';
			echo HTML::anchor('/admin_blog/authors/', 'Authors');
			echo '</li>';
        ?>
        <li class="divider"></li>
        <li class="nav-header">Recent Posts</li>
        <?php
        	if (count($posts) > 0)
			{
				foreach ($posts as $post)
				{
					echo '<li>';
					echo HTML::anchor('/admin_blog/edit_post/'.$post->id, $post->title);
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
        <li class="divider"></li>
        <li class="nav-header">Actions</li>
        <?php
        	echo '<li>';
        	echo HTML::anchor('/admin_blog/add_blog/', 'Create New Blog');
			if (count($blogs) > 0)
			{
				echo HTML::anchor('/admin_blog/add_post/', 'Write New Post');
			}
			echo '</li>';
        ?>
    </ul>
</div><!--/.well -->