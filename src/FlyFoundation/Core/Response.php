<?php


namespace FlyFoundation\Core;


interface Response {
    public function output();

    public function setOutputType($outputType);

    public function getOutputType();

    public function asArray();

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