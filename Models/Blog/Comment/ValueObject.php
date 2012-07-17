<?php
namespace Flyf\Models\Blog\Comment;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
				"comment_id" => array(
					"type" => "integer",
					"required" => true,
					"reference" => \Flyf\Models\General\Comment::Create()
				),
				"post_id" => array(
					"type" => "integer",
					"required" => true,
					"reference" => \Flyf\Models\Blog\Post::Create()
				)
			)
		);
		parent::__construct();
	}
}

?>