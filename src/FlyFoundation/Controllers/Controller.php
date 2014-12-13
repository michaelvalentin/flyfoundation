<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

interface Controller {

    /**
     * @param Model $model
     */
    public function setModel(Model $model);

    /**
     * @param View $view
     * @param string $action
     */
    public function setView(View $view, $action);

    /**
     * @param string $templateName
     * @param string $action
     */
    public function setTemplate($templateName, $action);

    /**
     * @param DataMapper $dataMapper
     */
    public function setDataMapper(DataMapper $dataMapper);

    /**
     * @param DataFinder $dataFinder
     */
    public function setDataFinder(DataFinder $dataFinder);

    /**
     * @param $action
     * @param array $arguments
     */
    public function render($action, array $arguments = []);

    /**
     * @param $action
     * @param array $arguments
     * @return bool
     */
    public function respondsTo($action, array $arguments = []);
} 