<?php


namespace FlyFoundation\Core;


use FlyFoundation\Core\Response\ResponseHeaders;
use FlyFoundation\Core\Response\ResponseMetaData;
use FlyFoundation\Core\Response\ResponseOutputType;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Util\ArrayHelper;
use FlyFoundation\Util\Map;
use FlyFoundation\Util\Set;

class Response {


    use AppContext, AppConfig;

    /** @var \FlyFoundation\Core\Response\ResponseHeaders */
    public $headers;
    /** @var \FlyFoundation\Core\Response\ResponseMetaData */
    public $metaData;
    /** @var string */
    public $outputType;
    /** @var string */
    public $title;
    /** @var \FlyFoundation\Util\Set */
    public $javaScript;
    /** @var \FlyFoundation\Util\Set */
    private $stylesheets;
    /** @var \FlyFoundation\Util\Map */
    public $data;
    /** @var string */
    public $content;
    /** @var string */
    public $htmlDocType;
    /** @var array */
    private $templates;


    /**
     * Initiate a new response with default values
     */
    public function __construct(){
        //Initialize
        $this->headers = new ResponseHeaders();
        $this->metaData = new ResponseMetaData();
        $this->outputType = "Html";
        $this->javaScript= new Set();
        $this->stylesheets = new Set();
        $this->data = new Map();
        $this->content = "";
        $this->templates = [];

        //Defaults
        $this->setOutputType(ResponseOutputType::Html);
        $this->htmlDocType = '<!doctype html>';
        $this->setContentType();
        $this->headers->SetHeader("Expires","-1"); //Don't cache this browser-side...
        $this->headers->SetHeader("Cache-Control","private, max-age=0"); //Don't cache this browser-side..
    }

    public function output()
    {

        $this->outputType = ucfirst(strtolower($this->outputType));

        $supportedOutputs = [
            "Html",
            "Json"
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
        $res = $this->data->asArray();
        $res["content"] = $this->content;
        $res["title"] = $this->title;
        $res["headers"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->headers->GetHeaders());
        $res["metadata"] = $this->metaData->AsArray();
        $res["doc_type"] = $this->htmlDocType;
        $res["javascript"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->javaScript->AsArray());
        $res["stylesheets"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->stylesheets->AsArray());
        $res["templates"] = ArrayHelper::AssociativeArrayToObjectStyleArray($this->templates);
        return $res;
    }

    public function composeHtml()
    {
        $m = new \Mustache_Engine();

        $contentLayers = $this->templates;
        array_unshift($contentLayers, $this->content);
        $output = "";

        $outputData = $this->asArray();

        foreach($contentLayers as $c){
            $output = $m->render($c,$outputData);
            $outputData["content"] = $output;
        }

        return $outputData["content"];
    }

    public function composeJson()
    {
        return json_encode($this->asArray());
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
        $this->headers->SetHeader("Content-Type",$type."; charset=".$charset);
        $this->metaData->Set("content-type",$type."; charset=".strtoupper($charset));
    }



    public function wrapInTemplate($templateContents)
    {
        $this->templates[] = $templateContents;
    }

    public function wrapInTemplateFile($templateName)
    {
        $fileLoader = Factory::load("\\FlyFoundation\\Core\\StandardFileLoader");
        $template_file = $fileLoader->findTemplate($templateName);
        if(!file_exists($template_file)){
            throw new InvalidOperationException('The template "'.$template_file.'" does not exist!');
        }
        $this->templates[] = file_get_contents($template_file);
    }

    public function getAllTemplatesInnermostFirst(){
        return $this->templates;
    }
}