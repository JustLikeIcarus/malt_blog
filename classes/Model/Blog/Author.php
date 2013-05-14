<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Blog_Author extends ORM {
		
	protected $_belongs_to = array(
		'image' => array(
        	'model' => 'Asset',
            'foreign_key' => 'image_id'
		),
	);
	
	public function save(Validation $validation = null)
	{
		if ( ! $this->id OR $this->id == null)
		{
			$this->date_created = Format::database_datetime();
		}
		$this->date_modified = Format::database_datetime();
		parent::save($validation);
	}
}