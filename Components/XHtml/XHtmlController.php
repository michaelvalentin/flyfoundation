<?php
namespace Flyf\Components\XHtml;

use Flyf\Core\Response;
use Flyf\Util\TemplateParser;
use Flyf\Components\Abstracts\AbstractLayout;
use Flyf\Util\HtmlString;

class XHtmlController extends AbstractLayout {
	protected function collectData() {
		// TODO Auto-generated method stub
	}

	protected function prepare() {
		$response = Response::GetResponse();
		$this->_view->AddValues(array(
				"title" => $response->Title,
				"doctype" => new HtmlString($response->GetDoctype()),
				"lang_iso" => \Flyf\Language::GetCurrent()->iso,
				"metadata" => new HtmlString($response->MetaData->HtmlOutput()),
				"css" => $response->GetCss(),
				"js" => $response->GetJs()
				));
	}

	protected function selectTemplate() {
		$this->_template = TemplateParser::BufferTemplate(__DIR__.DS."xhtml.phtml");
	}
}

?>