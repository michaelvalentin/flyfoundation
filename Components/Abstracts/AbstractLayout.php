<?php
namespace Flyf\Components\Abstracts;

use Flyf\Components\Abstracts\AbstractController;
use Flyf\Util\HtmlString;

abstract class AbstractLayout extends AbstractController {
	protected $_positions;
	
	public function WrapContent($content){
		$this->_view->AddValue("content",new HtmlString($content));
		return $this->Render();
	}
}

?>