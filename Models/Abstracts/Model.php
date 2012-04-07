<?php
namespace Flyf\Models\Abstracts;

use Flyf\Models\Abstracts\SimpleModel;

/**
 * The standard model supports a mandatory primary-index id, created-time, modified time
 * and the ability to trash (delete with the ability to restore...) models. The model is
 * simple and lightweight but still relatively powerfull.  
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class Model extends SimpleModel {

}

?>