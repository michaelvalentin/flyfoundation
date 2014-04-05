<?php

namespace Controllers\Abstracts;

use Core\Response;

/**
 * Interface IController
 *
 * @package Controllers\Abstracts
 */
interface IController {
    /**
     * Render this intermediate response with this controller
     *
     * @param Response $response
     * @return Response
     */
    public function Render(Response $response);

    /**
     * Explicitly decide to render with the get-method of this controller
     *
     * @param \Core\Response $response
     * @return \Core\Response
     */
    public function RenderGet(Response $response);

    /**
     * Explicitly decide to render with the post-method of this controller
     *
     * @param Response $response
     * @return \Core\Response
     */
    public function RenderPost(Response $response);
} 