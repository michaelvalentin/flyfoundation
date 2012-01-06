<?php
namespace Flyf\Models\Test\Blog\Entry;

class ValueObject extends \Flyf\Models\Abstracts\ValueObject {
	public $id;

	public $title;
	public $content;

	public function __construct() {
		$this->annotations = array(
			'title' => array(
				'translatable' => true,
				'requirements' => array(
					'length' => '5'
				)
			),
			'content' => array(
				'requirements' => array(
					'length' => '10',
					'format' => 'string'
				)
			)
		);
	}
}
?>
