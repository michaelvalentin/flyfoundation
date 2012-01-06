<?php
namespace Flyf\Models\Abstracts;

/**
 * The meta value object is an instance of the 
 * value object. It consists of three properties
 * (date_created, date_modified, date_trashed), which
 * is used as meta data for models.
 * 
 * If a meta value object is attached to a model,
 * date_trashed can also be used to determine whether
 * the model should be available for use or not. 
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2012-01-06
 */

class MetaValueObject extends ValueObject {
	public $date_created;
	public $date_modified;
	public $date_trashed;

	public function __construct() {
		
	}
}
?>
