<?php
	echo HTML::style('_media/core/common/css/bootstrap-toggle-buttons.css');
?>
<div class="well">
    <div class="form medium_form">
        <?php
            echo Form::open('/admin_blog/save_blog');
            echo Form::hidden('blog[id]', $blog->id);
        ?>
        <div class="form_field">
            <?php 
                echo Form::label('blog[title]', 'Title');
                echo Form::input('blog[title]', $blog->title, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('blog[tagline]', 'Tagline');
                echo Form::textarea('blog[tagline]', $blog->tagline, array('class' => 'span8'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('blog[default_author]', 'Default Author');
                echo Form::select('blog[default_author]', $authors, $blog->default_author, array('class' => 'span8', 'style' => 'width:50%;'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('blog[permalink]', 'Permalink');
				echo 'http://'.Kohana::$config->load('website')->get('url').'/';
				if ($blog->permalink == '')
				{
					$blog->permalink = $blog->id;
				}
				
                echo Form::input('blog[permalink]', $blog->permalink, array('class' => 'span8'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('blog[status]', 'Status');
                echo Form::toggle('blog[status]', $blog->status, array('class' => 'span8'));
            ?>
        </div>
        <div class="buttons">
            <?php echo Form::button(NULL, 'Save', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
            or
            <?php echo HTML::anchor('/admin_blog/', 'Cancel', array('class' => '')) ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>