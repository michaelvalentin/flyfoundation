<?php
namespace Flyf\Models\Abstracts;

class MetaValueObject extends ValueObject {
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
