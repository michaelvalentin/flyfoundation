<?php
namespace Flyf\Core;

/**
 * A simple factory for creating various classes
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
class Factory {
	/**
	 * Get an empty instance of the image class used for this project
	 * 
	 * @return \Flyf\Models\Core\Image
	 */
	public static function Image(){
		return \Flyf\Models\Core\Image::Create();
	}
	
	/**
	 * Get an empty instance of the file class used for this project
	 * 
	 * @return \Flyf\Models\Core\File
	 */
	public static function File(){
		return \Flyf\Models\Core\File::Create();
	}
}

?>