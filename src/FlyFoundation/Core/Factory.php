<?php


namespace FlyFoundation\Core;


interface Factory {
    public function load($className);

    public function loadView($viewName);

    public function loadController($controllerName);

    public function loadModel($modelName);

    public function loadEntityForm($modelName);

    public function loadEntityListing($modelName);

    public function loadDataMapper($modelName);

    public function loadDataFinder($modelName);

    public function loadDataQueryObject($dataQueryObjectName);
} 