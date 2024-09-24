<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use Slim\Psr7\Response;

final class GetRouteTest extends TestWithMocks
{
    public function testGetOneFetchesSingleResource(): void
    {
        $route = new CompanyRoute($this->setupContainer());
        $response = $route->getOne(
            $this->createRequest(),
            new Response(),
            ['id' => 1],
        );
        $respObject = json_decode($response->getBody()->__toString(), true);

        $this->assertTrue($respObject['success']);
        $this->assertEquals(0, count($respObject['warnings']));
        $this->assertEquals(1, $respObject['data']['companyId']);
        $this->assertEquals('Company1', $respObject['data']['name']);
    }

    public function testGetManyFetchesMultipleResources(): void
    {
        $route = new CompanyRoute($this->setupContainer());
        $response = $route->getMany(
            $this->createRequest(),
            new Response(),
            [],
        );
        $respObject = json_decode($response->getBody()->__toString(), true);

        $this->assertTrue($respObject['success']);
        $this->assertEquals(0, count($respObject['warnings']));
        $this->assertEquals(2, count($respObject['data']));
        $this->assertEquals('Company1', $respObject['data'][0]['name']);
        $this->assertEquals('Company2', $respObject['data'][1]['name']);
    }
}
