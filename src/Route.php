<?php

declare(strict_types=1);

namespace Lucite\Route;

use Exception;
use Lucite\Model\Model;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Req;
use Psr\Http\Message\ResponseInterface as Resp;

abstract class Route
{
    public ContainerInterface $container;
    public static string $modelNamespace = '';
    public static string $modelClass = '';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createModel(?Req $request = null): Model
    {
        if (static::$modelClass !== '') {
            $class = static::$modelNamespace .'\\'.static::$modelClass;
            return new $class(
                $this->container->get('db'),
                $this->container->get('logger'),
            );
        }
        throw new Exception('No model specified in class');
    }

    /**
     * Handle getting a single resource.
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return Psr\Http\Message\ResponseInterface
     */
    public function getOne(Req $request, Resp $response, array $args): Resp
    {
        $JsonWriter = new JsonWriter($response);
        $model = $this->createModel();
        $result = $model->fetchOne($args['id']);
        return $JsonWriter
            ->withWarnings($model->getWarnings())
            ->success($result);
    }

    /**
     * Handle getting multiple resources.
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface$response
     * @param array $args
     * @return Psr\Http\Message\ResponseInterface
     */
    public function getMany(Req $request, Resp $response, array $args): Resp
    {
        $JsonWriter = new JsonWriter($response);
        $model = $this->createModel();
        $result = $model->fetchMany();
        return $JsonWriter
            ->withWarnings($model->getWarnings())
            ->success($result);
    }

    /**
     * Handle creating a resource.
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface$response
     * @param array $args
     * @return Psr\Http\Message\ResponseInterface
     */
    public function create(Req $request, Resp $response, array $args): Resp
    {
        $JsonWriter = new JsonWriter($response);
        $model = $this->createModel();
        $params = $request->getParsedBody();
        # TODO: validate params
        $result = $model->create($params);
        return $JsonWriter
            ->withWarnings($model->getWarnings())
            ->success($result, 201);
    }

    /**
     * Handle updating an existing resource.
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface$response
     * @param array $args
     * @return Psr\Http\Message\ResponseInterface
     */
    public function update(Req $request, Resp $response, array $args): Resp
    {
        $JsonWriter = new JsonWriter($response);
        $model = $this->createModel();
        $params = $request->getParsedBody();
        # TODO: Validate params here
        $result = $model->update($args['id'], $params);
        return $JsonWriter
            ->withWarnings($model->getWarnings())
            ->success($result, 202);
    }

    /**
     * Handle deleting an existing resource.
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface$response
     * @param array $args
     * @return Psr\Http\Message\ResponseInterface
     */
    public function delete(Req $request, Resp $response, array $args): Resp
    {
        $JsonWriter = new JsonWriter($response);
        $model = $this->createModel();
        $model->delete($args['id']);
        return $JsonWriter
            ->withWarnings($model->getWarnings())
            ->success();
    }
}
