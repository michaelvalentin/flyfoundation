<?php
namespace Flyf\Components\Standard404;

use Flyf\Components\Abstracts\AbstractController;

class Standard404Controller extends AbstractController {
	public function Render(array $params = array()){
		return "404!";
	}
}

?>