<link href="<?php echo $post->blog->get_permalink().'?rss=true' ?>" rel="alternate" type="application/atom+xml" title="<title>"/>
<div class="page-header">
	<h1><?php echo $post->blog->title ?> <small><?php echo $post->blog->tagline ?></small></h1>
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

<div class="blog_post span8">
	<div>
		<div class="blog_post_date">
			<div class="blog_post_date_relative">
				Posted <?php echo Format::relative_date($post->date_published) ?>
			</div>
			<div class="blog_post_date_absolute">
				<?php echo Format::readable_datetime($post->date_published) ?>
			</div>
		</div>
	</div>
	<br/>
	<div>
		<?php
			if ($post->featured_image)
			{
				$featured_image_url = ORM::factory('Asset', $post->featured_image)->files->where('type', '=', 'image_'.$post->featured_image_size)->find()->url;
				echo HTML::image($featured_image_url, array('class' => 'pull-left thumbnail blog_post_image'));
			}
		?>
		<div class="blog_post_title"><?php echo $post->title  ?></div>
		<span class="blog_post_content"><?php echo $post->content ?></span>
		<div>
			<?php echo Share::embed(); ?>
		</div>
		<div>
			<?php echo Comments::embed() ?>
		</div>
	</div>
</div>