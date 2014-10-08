<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;

interface BaseController{
    /**
     * @param Response $response
     * @return Response
     */
    public function beforeApp(Response $response);

    /**
     * @param Response $response
     * @return Response
     */
    public function beforeController(Response $response);

    /**
     * @param Response $response
     * @return Response
     */
    public function afterController(Response $response);

    /**
     * @param Response $response
     * @return Response
     */
    public function afterApp(Response $response);
}