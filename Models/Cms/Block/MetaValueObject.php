<?php
class Cms_Block_Meta_ValueObject extends Flyf_Model_Meta_ValueObject {
	public $date_something;

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
