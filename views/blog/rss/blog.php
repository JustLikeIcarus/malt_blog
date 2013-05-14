<atom:link href="<?php echo $blog->get_permalink().'?rss=true' ?>" rel="self" type="application/rss+xml" />
<title><?php echo $blog->title ?></title>
<link><?php echo $blog->get_permalink() ?></link>
<description><?php echo $blog->tagline ?></description>
<language>en-us</language>
<lastBuildDate>
<?php
	foreach ($posts as $post)
	{
		echo date('r', strtotime($post->date_published));
	}
?>
</lastBuildDate>
<copyright>Copyright (c) <?php echo date('Y')  ?> <?php echo $site_name ?></copyright>
<?php
	
	foreach ($posts as $post)
	{
?>
		<item>
			<title><?php echo $post->title ?></title>
			<link><?php echo $post->get_permalink(); ?></link>
			<guid><?php echo $post->get_permalink(); ?></guid>
			<pubDate><?php echo date('r', strtotime($post->date_published)); ?></pubDate>
			<description><?php echo strip_tags($post->get_summary($type = 'medium')) ?></description>
		</item>
<?php
	}
?>