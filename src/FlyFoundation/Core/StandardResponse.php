<?php

namespace FlyFoundation\Core;

use FlyFoundation\Core\Response\ResponseHeaders;
use FlyFoundation\Core\Response\ResponseMetaData;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\ArrayHelper;
use FlyFoundation\Util\Set;

/**
 * Class Response
 *
 * A response from the system, capable of outputting itself as an html document or other types of responses
 *
 * @package Core
 */
class StandardResponse implements Response{
	public $headers;
	public $metaData;
	private $title;
	private $javaScriptAfterBody;
    private $javaScriptBeforeBody;
	private $stylesheets;
    private $data;
    private $content;
    private $templates;

	
	/**
	 * Initiate a new response with default values
	 */
	public function __construct(){
		//Initialize
        $this->headers = new ResponseHeaders();
		$this->metaData = new ResponseMetaData();
		$this->javaScriptAfterBody = new Set();
        $this->javaScriptBeforeBody = new Set();
		$this->stylesheets = new Set();
        $this->data = array();
        $this->templates = array();

        //Defaults
		$this->htmlDocType = '<!doctype html>';
        $this->setContentType();
        $this->headers->SetHeader("Expires","-1"); //Don't cache this browser-side...
        $this->headers->SetHeader("Cache-Control","private, max-age=0"); //Don't cache this browser-side..
	}
	
	/**
	 * Set the content type in both headers and metadata
	 * 
	 * @param string $type (The type eg. text/html)
	 * @param string $charset (The charset eg. utf-8)
	 */
	public function setContentType($type="text/html",$charset="utf-8"){
		$this->headers->SetHeader("Content-Type",$type."; ".$charset);
		$this->metaData->Set("content-type",$type."; charset=".strtoupper($charset));
	}

	/**
	 * Add this script to the response
	 * 
	 * @param string $path
     * @param boolean $frontload
	 */
	public function addJs($path, $frontload=false) {
        if($frontload){
            $this->javaScriptBeforeBody->add($path);
            return;
        }
		$this->javaScriptAfterBody->add($path);
	}
	
	/**
	 * Remove this script from the response
	 * 
	 * @param string $path
	 */
	public function removeJs($path) {
		$this->javaScriptAfterBody->remove($path);
        $this->javaScriptBeforeBody->remove($path);
	}

	/**
	 * Add this stylesheet to the response
	 * 
	 * @param string $css
	 */
	public function addCss($css) {
		$this->stylesheets->add($css);
	}

    /**
     * Remove this stylesheet from the response
     *
     * @param $css
     */
	public function removeCss($css){
		$this->stylesheets->Remove($css);
	}

    /**
     * Get an array of all javascript files used
     *
     * @return array
     */
    public function getJs() {
		return array_merge($this->javaScriptAfterBody->AsArray(),$this->javaScriptBeforeBody->AsArray());
	}

    /**
     * Get an array of css files used
     *
     * @return array
     */
    public function getCss() {
		return $this->stylesheets->AsArray();
	}

    /**
     * Get the doctype declaration for this response
     *
     * @return string
     */
    public function getHtmlDocType(){
		return $this->htmlDocType;
	}

    /**
     * Set the doctype declaration for this response
     *
     * @param $doctype
     */
    public function setHtmlDocType($doctype){
		$this->htmlDocType = $doctype;
	}

    /**
     * Get the current content of this response
     *
     * @return mixed
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * Set the content of this response
     *
     * @param $content
     */
    public function setContent($content){
        $this->content = $content;
    }

    /**
     * Set a given field in the data to a given value, based on it's key
     *
     * @param $key
     * @param $value
     */
    public function setData($key, $value){
        $this->data[$key] = $value;
    }

    /**
     * Add this data, overriding existing values if they exist
     *
     * @param $array
     */
    public function addData($array){
        $this->data = array_merge($this->data,$array);
    }

    /**
     * Get a given field in the data based on it's key
     *
     * @param $key
     * @return mixed
     */
    public function getData($key){
        return $this->data[$key];
    }

    /**
     * Get all data as an array
     *
     * @return array
     */
    public function getAllData(){
        return $this->data;
    }

    /**
     * Wrap the content in this template
     *
     * @param $template_content
     */
    public function wrapInTemplate($template_content){
        $this->templates[] = $template_content;
    }

    /**
     * Get an array of all templates, with the innermost template first and outermost last
     *
     * @return array
     */
    public function getTemplates(){
        return $this->templates;
    }

    /**
     * Get the response data as an array
     *
     * @return array
     */
    public function asArray(){
        $res = $this->getAllData();
        $res["headers"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->headers->GetHeaders());
        $res["metadata"] = $this->metaData->AsArray();
        $res["title"] = $this->title;
        $res["doctype"] = $this->htmlDocType;
        $res["javascript_pre"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->javaScriptBeforeBody->AsArray());
        $res["javascript"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->javaScriptAfterBody->AsArray());
        $res["stylesheets"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->stylesheets->AsArray());
        $res["content"] = $this->content;
        $res["templates"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->templates);
        return $res;
    }

    /**
     * Return html representation of the response
     *
     * @throws \FlyFoundation\Exceptions\InvalidOperationException
     * @return string
     */
    public function outputHtml(){
        $this->headers->Output();

        //Import mustache
        $m = new \Mustache_Engine();

        //Collect the output by looping over the templates from inside out
        $output = "";
        $content = array();
        $content[] = $this->content;
        foreach($this->templates as $t){
            $template_file = BASEDIR.DS."..".DS."templates".DS.strtolower($t).".phtml";
            if(!file_exists($template_file)){
                throw new InvalidOperationException('The template "'.$template_file.'" does not exist!');
            }
            $content[] = file_get_contents($template_file);
        }
        foreach($content as $c){
            $output = $m->render($c,$this->asArray());
            $this->content = $output;
        }

        //Return the final output
        return $output;
    }

    /**
     * Return data only from response as a JSON object (string)
     *
     * @return string
     */
    private function outputJsonData(){
        return json_encode($this->getAllData());
    }

    /**
     * Return all response data as a JSON object (string)
     *
     * @return string
     */
    private function outputJsonAll(){
        return json_encode($this->asArray());
    }
}