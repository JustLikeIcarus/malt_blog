<meta property="og:url" content="<?php echo URL::get_current_url() ?>"/>
<meta property="og:title" content="<?php echo str_replace('"', "'", $title) ?>"/>
<?php
	if ($image_url)
	{
?>
<meta property="og:image" content="<?php echo $image_url ?>"/>
<meta itemprop="image" content="<?php echo $image_url ?>" />
<?php	
	}
?>
<meta name="name" content="<?php echo str_replace('"', "'", $title) ?>" />
<meta property="og:description" content="<?php echo str_replace('"', "'", $description) ?>"/>
<meta name="description" content="<?php echo str_replace('"', "'", $description) ?>" />
<meta property="og:site_name" content="<?php echo Kohana::$config->load('website')->get('site_name') ?>"/>
<meta property="og:type" content="<?php echo $type ?>"/>