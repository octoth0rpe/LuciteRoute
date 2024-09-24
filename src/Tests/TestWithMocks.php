<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use DI\Container;
use PDO;
use PHPUnit\Framework\TestCase;
use Lucite\MockLogger\MockLogger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

class TestWithMocks extends TestCase
{
    public function setupContainer(): ContainerInterface
    {
        $container = new Container();
        $container->set('db', function () {
            $db = new PDO('sqlite::memory:');
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->exec(file_get_contents(__DIR__.'/db.sql'));
            return $db;
        });
        $container->set('logger', function () {
            return new MockLogger();
        });
        return $container;
    }

    public function createRequest(): RequestInterface
    {
        return new Request(
            'GET',
            new Uri('http', 'localhost'),
            new Headers(),
            [],
            [],
            new Stream(fopen('php://output', 'w')),
        );
    }
}
