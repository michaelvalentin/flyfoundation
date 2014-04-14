<?php

namespace FlyFoundation\Core;

use FlyFoundation\Core\Response\ResponseHeaders;
use FlyFoundation\Core\Response\ResponseMetaData;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\ArrayHelper;
use FlyFoundation\Util\Set as Set;

/**
 * Class Response
 *
 * A response from the system, capable of outputting itself as an html document or other types of responses
 *
 * @package Core
 */
class Response {
	public $Headers;
	public $MetaData;
	public $Title;
	public $CompressOutput = true;
	private $_doctype;
	private $_javascript;
    private $_javascript_pre;
	private $_stylesheets;
    private $_data;
    private $_content;
    private $_templates;

	
	/**
	 * Initiate a new response with default values
	 */
	public function __construct(){
		//Initialize
        $this->Headers = new ResponseHeaders();
		$this->MetaData = new ResponseMetaData();
		$this->_javascript = new Set();
        $this->_javascript_pre = new Set();
		$this->_stylesheets = new Set();
        $this->_data = array();
        $this->_templates = array();

        //Defaults
		$this->_doctype = '<!doctype html>';
        $this->SetContentType();
        $this->Headers->SetHeader("Expires","-1"); //Don't cache this browser-side...
        $this->Headers->SetHeader("Cache-Control","private, max-age=0"); //Don't cache this browser-side..
	}
	
	/**
	 * Set the content type in both headers and metadata
	 * 
	 * @param string $type (The type eg. text/html)
	 * @param string $charset (The charset eg. utf-8)
	 */
	public function SetContentType($type="text/html",$charset="utf-8"){
		$this->Headers->SetHeader("Content-Type",$type."; ".$charset);
		$this->MetaData->Set("content-type",$type."; charset=".strtoupper($charset));
	}

	/**
	 * Add this script to the response
	 * 
	 * @param string $path
     * @param boolean $frontload
	 */
	public function AddJs($path, $frontload=false) {
        if($frontload){
            $this->_javascript_pre-> Add($path);
            return;
        }
		$this->_javascript->Add($path);
	}
	
	/**
	 * Remove this script from the response
	 * 
	 * @param string $path
	 */
	public function RemoveJs($path) {
		$this->_javascript->Remove($path);
        $this->_javascript_pre->Remove($path);
	}

	/**
	 * Add this stylesheet to the response
	 * 
	 * @param string $css
	 */
	public function AddCss($css) {
		$this->_stylesheets->Add($css);
	}

    /**
     * Remove this stylesheet from the response
     *
     * @param $css
     */
	public function RemoveCss($css){
		$this->_stylesheets->Remove($css);
	}

    /**
     * Get an array of all javascript files used
     *
     * @return array
     */
    public function GetJs() {
		return array_merge($this->_javascript->AsArray(),$this->_javascript_pre->AsArray());
	}

    /**
     * Get an array of css files used
     *
     * @return array
     */
    public function GetCss() {
		return $this->_stylesheets->AsArray();
	}

    /**
     * Get the doctype declaration for this response
     *
     * @return string
     */
    public function GetDoctype(){
		return $this->_doctype;
	}

    /**
     * Set the doctype declaration for this response
     *
     * @param $doctype
     */
    public function SetDoctype($doctype){
		$this->_doctype = $doctype;
	}

    /**
     * Get the current content of this response
     *
     * @return mixed
     */
    public function GetContent(){
        return $this->_content;
    }

    /**
     * Set the content of this response
     *
     * @param $content
     */
    public function SetContent($content){
        $this->_content = $content;
    }

    /**
     * Set a given field in the data to a given value, based on it's key
     *
     * @param $key
     * @param $value
     */
    public function SetData($key, $value){
        $this->_data[$key] = $value;
    }

    /**
     * Add this data, overriding existing values if they exist
     *
     * @param $array
     */
    public function AddData($array){
        $this->_data = array_merge($this->_data,$array);
    }

    /**
     * Get a given field in the data based on it's key
     *
     * @param $key
     * @return mixed
     */
    public function GetData($key){
        return $this->_data[$key];
    }

    /**
     * Get all data as an array
     *
     * @return array
     */
    public function GetAllData(){
        return $this->_data;
    }

    /**
     * Wrap the content in this template
     *
     * @param $template_content
     */
    public function WrapInTemplate($template_content){
        $this->_templates[] = $template_content;
    }

    /**
     * Get an array of all templates, with the innermost template first and outermost last
     *
     * @return array
     */
    public function GetTemplates(){
        return $this->_templates;
    }

    /**
     * Get the response data as an array
     *
     * @return array
     */
    public function AsArray(){
        $res = $this->GetAllData();
        $res["headers"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->Headers->GetHeaders());
        $res["metadata"] = $this->MetaData->AsArray();
        $res["title"] = $this->Title;
        $res["doctype"] = $this->_doctype;
        $res["javascript_pre"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->_javascript_pre->AsArray());
        $res["javascript"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->_javascript->AsArray());
        $res["stylesheets"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->_stylesheets->AsArray());
        $res["content"] = $this->_content;
        $res["templates"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->_templates);
        return $res;
    }

	/**
	 * Output all current contents and send the response
	 */
	public function output($ResponseType = ResponseType::Html){
        $method_name = "Output".$ResponseType;
        if(method_exists($this,$method_name)){
            $output = $this->$method_name();
        }else{
            throw new InvalidArgumentException('"'.$ResponseType.'" is not a known response type.
            Use fx: \\Core\\ResponseType::Html');
        }

		$this->Headers->Output();
		echo $output;
	}

    /**
     * Return html representation of the response
     *
     * @throws \FlyFoundation\Exceptions\InvalidOperationException
     * @return string
     */
    private function OutputHtml(){
        //Import mustache
        $m = new \Mustache_Engine();

        //Collect the output by looping over the templates from inside out
        $output = "";
        $content = array();
        $content[] = $this->_content;
        foreach($this->_templates as $t){
            $template_file = BASEDIR.DS."..".DS."templates".DS.strtolower($t).".phtml";
            if(!file_exists($template_file)){
                throw new InvalidOperationException('The template "'.$template_file.'" does not exist!');
            }
            $content[] = file_get_contents($template_file);
        }
        foreach($content as $c){
            $output = $m->render($c,$this->AsArray());
            $this->_content = $output;
        }

        //Return the final output
        return $output;
    }

    /**
     * Return data only from response as a JSON object (string)
     *
     * @return string
     */
    private function OutputJsonData(){
        return json_encode($this->GetAllData());
    }

    /**
     * Return all response data as a JSON object (string)
     *
     * @return string
     */
    private function OutputJsonAll(){
        return json_encode($this->AsArray());
    }
}

/**
 * Class ResponseType
 *
 * Types of response to be used with response output (Enumerable equivalent)
 *
 * @package Core
 */
class ResponseType {
    const Html = "Html";
    const JsonData = "JsonData";
    const JsonAll = "JsonAll";
}