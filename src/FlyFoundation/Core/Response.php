<?php


namespace FlyFoundation\Core;


use FlyFoundation\Core\Response\ResponseHeaders;
use FlyFoundation\Core\Response\ResponseMetaData;

interface Response {

    public function output();

    public function asArray();

    /**
     * @return ResponseHeaders
     */
    public function getHeaders();

    /**
     * @return ResponseMetaData
     */
    public function getMetaData();

    public function setOutputType($outputType);

    public function getOutputType();

    public function setContentType($type,$charset);

    public function setTitle($title);

    public function getTitle($title);

    public function addJavaScriptBeforeBody($path);

    public function getJavaScriptBeforeBody();

    public function addJavaScriptAfterBody($path);

    public function getJavaScriptAfterBody();

    public function getAllJavaScript();

    public function removeJavaScript($path);

    public function addStylesheet($path);

    public function getStylesheets();

    public function removeStylesheet($path);

    public function setDataValue($key, $value);

    public function setData(array $data);

    public function getDataValue($key);

    public function getData();

    public function setContent($content);

    public function getContent();

    public function wrapInTemplate($templateContent);

    public function wrapInTemplateFile($path);

    public function getAllTemplatesInnermostFirst();
}