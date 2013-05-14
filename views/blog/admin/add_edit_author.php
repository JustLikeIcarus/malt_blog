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
            echo Form::open('/admin_blog/save_author', array('id' => 'content_form', 'enctype' =>'multipart/form-data'));
            echo Form::hidden('id', $author->id);
        ?>
        <div class="form_field">
            <?php 
                echo Form::label('name', 'Name');
                echo Form::input('name', $author->name, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('description', 'Description');
                echo Form::textarea('description', $author->description, array('class' => 'span6 ckeditor'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('image_id', 'Image');
                echo Form::file_image('image_id', $author->image_id, array('class' => 'span6'));
            ?>
        </div>
        <div class="form_field">
            <?php 
                echo Form::label('status', 'Status');
                echo Form::toggle('status', $author->status, array('class' => 'span8'));
            ?>
        </div>
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