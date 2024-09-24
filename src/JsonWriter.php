<?php

declare(strict_types=1);

namespace Lucite\Route;

use Psr\Http\Message\ResponseInterface;

class JsonWriter
{
    public array $warnings = [];
    public ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function withWarnings(array $newWarnings): JsonWriter
    {
        $next = clone $this;
        $next->warnings = array_merge($next->warnings, $newWarnings);
        return $next;
    }

    public function withoutWarnings(): JsonWriter
    {
        $next = clone $this;
        $next->warnings = [];
        return $next;
    }

    public function success(array | null $data = null, int $statusCode = 200): ResponseInterface
    {
        $json = ["success" => true, "warnings" => $this->warnings];
        if ($data !== null) {
            $json['data'] = $data;
        }
        $this->response->getBody()->write(json_encode($json));
        return $this->response
            ->withHeader('content-type', 'application/json')
            ->withStatus($statusCode);
    }

    public function failure(array $errors, int $code = 422): ResponseInterface
    {
        $this->response->getBody()->write(json_encode([
            "success" => false,
            "warnings" => $this->warnings,
            "errors" => $errors,
        ]));
        return $this->response
            ->withStatus($code)
            ->withHeader('content-type', 'application/json');
    }
}
