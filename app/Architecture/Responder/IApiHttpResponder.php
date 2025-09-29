<?php

namespace App\Architecture\Responder;

use Symfony\Component\HttpFoundation\Response;

interface IApiHttpResponder
{
    /**
     * @param array $data
     * @param int $code
     * @return mixed
     */
    public function sendSuccess(array $data = [], int $code = Response::HTTP_OK): mixed;

    /**
     * @param string|null $message
     * @param int $code
     * @return mixed
     */
    public function sendError(string $message = null,int $code = Response::HTTP_NOT_FOUND,array $data = []): mixed;
}
