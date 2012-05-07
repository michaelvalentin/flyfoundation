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
				"css" => "CssCompiler/in=".urlencode(base64_encode(implode(",",$response->GetCss()))),
				"js" => "JsCompiler/in=".urlencode(base64_encode(implode(",",$response->GetJs())))
				));
	}

	protected function selectTemplate() {
		$this->_template = TemplateParser::BufferTemplate(__DIR__.DS."xhtml.phtml");
	}
}

?>