<?php
namespace Flyf\Components\XHtml;

use Flyf\Core\Response;

use Flyf\Util\TemplateParser;

use Flyf\Components\Abstracts\AbstractLayout;

class XHtmlController extends AbstractLayout {
	protected function collectData() {
		// TODO Auto-generated method stub
	}

	protected function prepare() {
		$response = Response::GetResponse();
		$this->_view->AddValue("title", $response->Title);
	}

	protected function selectTemplate() {
		$this->_template = TemplateParser::BufferTemplate(__DIR__.DS."default.phtml");
	}
}

?>