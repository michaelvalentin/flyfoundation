<?php
namespace Flyf\Components\SharedTest;

use Flyf\AbstractController;

class SharedTestController extends AbstractController{
	/* (non-PHPdoc)
	 * @see Flyf.AbstractController::Render()
	 */
	public function Render() {
		return '<p>Hello world from Flyf Shared</p>';
	}


}

?>