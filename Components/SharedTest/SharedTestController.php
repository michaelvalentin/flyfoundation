<?php
namespace Flyf\Components\SharedTest;

use Flyf\AbstractController;

class SharedTestController extends AbstractController{
	public function Process(){
		parent::Process();
		#\Flyf\Language\Writer::Load(__DIR__);
	}
	
	/* (non-PHPdoc)
	 * @see Flyf.AbstractController::Render()
	 */
	public function Render() {
		return 'hello';
		#return '<p>Hello world from Flyf Shared</p><p>'.\Flyf\Language\Writer::_("Test 2").'</p>';
	}


}

?>
