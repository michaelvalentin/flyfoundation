<?php

namespace FlyFoundation\Core;

use Aws\Common\Exception\InvalidArgumentException;
use FlyFoundation\Core\Response\ResponseHeaders;
use FlyFoundation\Core\Response\ResponseMetaData;
use FlyFoundation\Core\Response\ResponseOutputType;
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

    use Environment;

	public $headers;
	public $metaData;
    private $outputType;
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
        $this->setOutputType(ResponseOutputType::Html);
		$this->htmlDocType = '<!doctype html>';
        $this->setContentType();
        $this->headers->SetHeader("Expires","-1"); //Don't cache this browser-side...
        $this->headers->SetHeader("Cache-Control","private, max-age=0"); //Don't cache this browser-side..
	}

    public function output()
    {

        $supportedOutputs = [
            "Html"
        ];

        if(!in_array($this->outputType,$supportedOutputs)){
            throw new InvalidArgumentException("Output type '".$this->outputType."' is not currently supported");
        }

        $method = "compose".$this->outputType;

        $this->headers->Output();
        echo $this->$method();

    }

    /**
     * Get the response data as an array
     *
     * @return array
     */
    public function asArray(){
        $res = $this->getData();
        $res["headers"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->headers->GetHeaders());
        $res["metadata"] = $this->metaData->AsArray();
        $res["title"] = $this->title;
        $res["doc_type"] = $this->htmlDocType;
        $res["javascript_pre"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->javaScriptBeforeBody->AsArray());
        $res["javascript"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->javaScriptAfterBody->AsArray());
        $res["stylesheets"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->stylesheets->AsArray());
        $res["content"] = $this->content;
        $res["templates"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->templates);
        return $res;
    }

    public function composeHtml()
    {
        $m = new \Mustache_Engine();

        $contentLayers = $this->templates;
        array_unshift($contentLayers, $this->content);

        foreach($contentLayers as $c){
            $output = $m->render($c,$this->asArray());
            $this->content = $output;
        }

        return $output;
    }

    public function setOutputType($outputType)
    {
        $this->outputType = $outputType;
    }

    public function getOutputType()
    {
        return $this->outputType;
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

	public function addJavaScriptBeforeBody($path)
    {
        $this->javaScriptAfterBody->remove($path);
        $this->javaScriptBeforeBody->add($path);
	}

    public function getJavaScriptBeforeBody()
    {
        return $this->javaScriptBeforeBody->asArray();
    }

    public function addJavaScriptAfterBody($path)
    {
        if(!$this->javaScriptBeforeBody->contains($path)){
            $this->javaScriptAfterBody->add($path);
        }
    }

    public function getJavaScriptAfterBody()
    {
        return $this->javaScriptAfterBody->asArray();
    }

    public function getAllJavaScript()
    {
        return array_unique(arrray_merge($this->javaScriptAfterBody->asArray(),$this->javaScriptBeforeBody));
    }

	public function removeJavaScript($path)
    {
		$this->javaScriptAfterBody->remove($path);
        $this->javaScriptBeforeBody->remove($path);
	}

    public function addStylesheet($path)
    {
		$this->stylesheets->add($path);
	}

    public function getStylesheets()
    {
        return $this->stylesheets->asArray();
    }

    public function removeStylesheet($path)
    {
        $this->stylesheets->remove($path);
    }

    public function setDataValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getDataValue($key)
    {
        if(!isset($this->data[$key])){
            return null;
        }
        return $this->data[$key];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function wrapInTemplate($templateContents)
    {
        $this->templates[] = $templateContents;
    }

    public function wrapInTemplateFile($templateName)
    {
        $fileLoader = $this->getFactory()->load("\\FlyFoundation\\Core\\FileLoader");
        $template_file = $fileLoader->findTemplate($templateName);
        if(!file_exists($template_file)){
            throw new InvalidOperationException('The template "'.$template_file.'" does not exist!');
        }
        $this->templates[] = file_get_contents($template_file);
    }

    public function getAllTemplatesInnermostFirst(){
        return $this->templates;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle($title)
    {
        return $this->title;
    }
}