<?php
namespace Flyf\Components\SigniflyComingSoon;

use Flyf\Core\Request;

use Flyf\Core\Response;

use Flyf\Components\Abstracts\AbstractController;

class SigniflyComingSoonController extends AbstractController {
	/* (non-PHPdoc)
	 * @see Flyf\Components\Abstracts.AbstractController::collectData()
	 */
	protected function collectData() {
		$this->_view->AddValues(array(
				"title"=>"Hosted by Signifly",
				"text"=>"Coming soon! :-)",
				"show"=>true,
				"advantages"=>array(
						"Fast","Secure","Reliable"
						)
				));
	}

	/* (non-PHPdoc)
	 * @see Flyf\Components\Abstracts.AbstractController::selectTemplate()
	 */
	protected function selectTemplate() {
		$this->_template = \Flyf\Util\TemplateParser::BufferTemplate(__DIR__.DS."default.phtml");
	}
	
	protected function prepare(){
		$response = Response::GetResponse();
		$request = Request::GetRequest();
		$domain = $request->GetDomain();
		$response->Title = $domain." : Hosted by Signifly";
		$response->AddCss(__DIR__.DS."css".DS."style.css");
		$response->AddCss(__DIR__.DS."css".DS."style2.css");
		\Flyf\Resources\Javascript::AddJquery();
		$response->AddJs(__DIR__.DS."js".DS."onload.js");
		$this->setLayout(new \Flyf\Components\XHtml\XHtmlController());
	}
}

?>