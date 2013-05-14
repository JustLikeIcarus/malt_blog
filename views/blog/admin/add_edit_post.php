<?php
    echo HTML::style('_media/core/common/css/bootstrap-image-gallery.min.css');
    echo HTML::style('_media/core/common/jquery_file_uploader/css/jquery.fileupload-ui.css');
    echo Html::style('_media/core/admin/css/asset.css');
    echo HTML::style('_media/core/common/css/bootstrap-toggle-buttons.css');
	echo HTML::style('_media/core/common/css/bootstrap-datetimepicker.min.css');
	echo HTML::style('_media/core/common/css/bootstrap-tagmanager.css');
	
    echo HTML::script('/_media/core/common/js/libs/jquery.validate.min.js');
    echo HTML::script('/_media/core/admin/js/libs/ckeditor/ckeditor.js');
?>

<div class="well">
    <div class="form medium_form">
        <?php
            echo Form::open('/admin_blog/save_post', array('id' => 'content_form', 'enctype' =>'multipart/form-data'));
            echo Form::hidden('id', $post->id);
        ?>
        <div class="form_field">
            <?php 
                echo Form::label('blog_id', 'Blog');
				echo Form::hidden('blog_id', $post->blog->id);
                echo Form::input('blog_title', $post->blog->title, array('class' => 'span6', 'readonly' => 'readonly'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('author_id', 'Author');
                echo Form::select('author_id', $authors, $post->author_id, array('class' => 'span8', 'style' => 'width:50%;'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('title', 'Title');
                echo Form::input('title', $post->title, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('featured_image', 'Featured Image');
                echo Form::file_image('featured_image', $post->featured_image, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('featured_image_size', 'Featured Image Size');
                echo Form::select('featured_image_size', $featured_image_sizes, $post->featured_image_size, array());
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('permalink', 'Permalink');
				echo $post->blog->get_permalink().'/';
				if ($post->permalink == '')
				{
					$post->permalink = $post->id;
				}
				
                echo Form::input('permalink', $post->permalink, array('class' => 'span8'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('source', 'Source');
                echo Form::input('source', $post->source, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('via', 'Via');
                echo Form::input('via', $post->via, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('content', 'Content');
                echo Form::textarea('content', $post->content, array('class' => 'span6 ckeditor'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('tags', 'Tags');
                echo Form::tags('tags', $post->tags->find_all(), array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('category', 'Category');
                echo Form::select('category', $categories, $selected_category, array('style' => 'width:20%'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('status', 'Status');
                echo Form::select('status', $statuses, $post->status, array('class' => '', 'style' => 'width:20%'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('date_published', 'Published Date');
                echo Form::datetime('date_published', $post->date_published?$post->date_published:Format::standard_datetime(), array('class' => ''));
            ?>
        </div>
		<?php
        	if ($post->date_created != '0000-00-00 00:00:00' AND $post->date_created != NULL)
			{
		?>
		<div class="form_field">
            <?php 
                echo Form::label('date_created', 'Date Created');
                echo Form::input('date_created', date('m/d/Y h:i A', strtotime($post->date_created)), array('class' => ''));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('date_modified', 'Date Modified');
                echo Form::input('date_modified', date('m/d/Y h:i A', strtotime($post->date_modified)), array('class' => '', 'readonly' => 'readonly'));
            ?>
        </div>
        <?php
			}
		?>
        <div class="buttons">
            <?php echo Form::button(NULL, 'Save', array('type' => 'submit', 'class' => 'btn btn-primary save_button')); ?>
            or
            <?php echo HTML::anchor('/admin_blog/', 'Cancel', array('class' => '')) ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>

<?php
    echo HTML::script('_media/core/common/jquery_file_uploader/js/vendor/jquery.ui.widget.js');
    echo HTML::script('_media/core/common/jquery_file_uploader/js/jquery.iframe-transport.js');
    echo HTML::script('_media/core/common/js/load-image.min.js');
    echo HTML::script('_media/core/common/js/canvas-to-blob.min.js');
    echo HTML::script('_media/core/common/jquery_file_uploader/js/locale.js');
?>