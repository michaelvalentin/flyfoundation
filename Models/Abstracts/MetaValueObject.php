<?php
class Flyf_Model_Meta_ValueObject extends Flyf_Abstract_Model_ValueObject {
	public $date_created;
	public $date_modified;
	public $date_trashed;

	public function __construct() {
		parent::__construct();

		// TODO 
		$this->annotations = array(
			'date_created' => array(
				'requirements' => array(
					'length' => 10,
				)
			),
			'date_modified' => array(
				'requirements' => array(
					'length' => 10
				)
			)
		);
	}
}
?>
