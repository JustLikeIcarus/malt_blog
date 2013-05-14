<div class="well">
	<h4>
		Posts
		<div class="buttons pull-right">
			<div class="btn-group">
			<?php
				echo HTML::anchor('/admin_blog/add_post/?blog_id='.$blog->id, '<i class="icon-plus icon-white"></i> Add Post', array('class' => 'btn btn-success btn-small'));
			?>
			</div>
		</div>	
	</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width:50px;"></th>
                <th>Title</th>
                <th style="width:50%;">Summary</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            	if (count($posts) > 0)
				{
	                foreach ($posts as $post)
	                {
	                    echo '<tr>';
						echo '<td style="width:50px;">';
						$featured_image_asset = ORM::factory('Asset', $post->featured_image);
        				$feautred_image_url = $featured_image_asset->get_image_value('tiny_square');
						if ($feautred_image_url)
						{
							echo HTML::image($feautred_image_url, array('class' => 'thumbnail'));
						}
						echo '</td>';
						echo '<td>'.$post->title.'</td>';
	                    echo '<td>'.$post->get_summary().'</td>';
	                    
						echo '<td>';
			?>
						<div class="buttons pull-right">
							<div class="btn-group">
			<?php
				echo HTML::anchor($post->get_permalink(), 'View Post', array('class' => 'btn btn-small', 'target' => '_BLANK'));
				echo HTML::anchor('/admin_blog/edit_post/'.$post->id, '<i class="icon-pencil"></i> Edit Post', array('class' => 'btn btn-small'));
				echo HTML::anchor('/admin_blog/delete_post/'.$post->id, '<i class="icon-trash icon-white"></i> Delete Post', array('class' => 'delete btn btn-danger btn-small'));
			?>
							</div>
						</div>
			<?php
						echo '</td>';
	                    echo '</tr>';
	                }
				}
				else
				{
					echo '<tr>';
                    echo '<td colspan="2">No recent posts.</td>';
                    echo '</tr>';
				}
            ?>
        </tbody>
    </table>
    <?php echo $pagination ?>
</div>

<div class="modal hide dialog" id="delete_dialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Confirmation Required</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to delete this?</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn modal_hide">No</a>
        <a href="#" class="btn btn-primary modal_delete_yes_button">Yes</a>
    </div>
</div>