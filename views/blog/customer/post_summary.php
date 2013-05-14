<div class="blog_post_summary span8">
	<div class="blog_post_date">
		<div class="blog_post_date_relative">
			Posted <?php echo Format::relative_date($post->date_published) ?>
		</div>
		<div class="blog_post_date_absolute">
			<?php echo Format::readable_datetime($post->date_published) ?>
		</div>
	</div>
	<?php
		if ($post->featured_image)
		{
			$featured_image_url = ORM::factory('Asset', $post->featured_image)->files->where('type', '=', 'image_tiny_square')->find()->url;
			echo HTML::anchor($post->get_permalink(), HTML::image($featured_image_url, array('class' => 'pull-left thumbnail blog_post_image')));
		}
	?>
	<div class="blog_post_title">
		<?php echo HTML::anchor($post->get_permalink(), $post->title) ?>
	</div>
	<?php
		if ($post->author->loaded())
		{
	?>
	<div class="blog_post_author">
		By <?php echo $post->author->name ?>
	</div>
	<?php
		}
	?>
	<div class="blog_post_content">
		<?php echo $post->get_summary('medium').' '.HTML::anchor($post->get_permalink(), 'Read More') ?>
	</div>
	<div class="blog_post_stats">
		<div class="blog_post_share_count">
			<?php echo Share::get_share_count_by_url($post->get_permalink(false)); ?>
		</div>
		<div class="blog_post_comment_count">
			<?php echo Comments::get_comment_count_by_url($post->get_permalink(false)) ?>
		</div>
		<div class="blog_post_view_count">
			<?php echo Pageview::get_view_count_by_url($post->get_permalink(false)) ?>
		</div>
	</div>
</div>