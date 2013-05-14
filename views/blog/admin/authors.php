<div class="well">
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
            	if (count($authors) > 0)
				{
	                foreach ($authors as $author)
	                {
	                    echo '<tr>';
						echo '<td style="width:50px;">';
        				$feautred_image_url = $author->image->get_image_value('tiny_square');
						if ($feautred_image_url)
						{
							echo HTML::image($feautred_image_url, array('class' => 'thumbnail'));
						}
						echo '</td>';
						echo '<td>'.$author->name.'</td>';
	                    echo '<td>'.Text::limit_chars($author->description, 100).'</td>';
	                    
						echo '<td>';
			?>
						<div class="buttons pull-right">
							<div class="btn-group">
			<?php
				echo HTML::anchor('/admin_blog/edit_author/'.$author->id, '<i class="icon-pencil"></i> Edit', array('class' => 'btn btn-small'));
				echo HTML::anchor('/admin_blog/delete_author/'.$author->id, '<i class="icon-trash icon-white"></i> Delete', array('class' => 'delete btn btn-danger btn-small'));
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
                    echo '<td colspan="2">No authors found.</td>';
                    echo '</tr>';
				}
            ?>
        </tbody>
    </table>
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