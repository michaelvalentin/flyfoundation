<?php
namespace Flyf\Models\Core;

use Flyf\Exceptions\InvalidOperationException;

use Flyf\Models\Abstracts\Model;

class User extends Model {
	public function GetFullName(){
		return $this->firstName." ".$this->lastName;
	}
	
	public function GetPassword(){
		throw new InvalidOperationException("It's not allowed to get the password.");
	}
}

?>