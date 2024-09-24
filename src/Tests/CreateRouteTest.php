<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use Slim\Psr7\Response;

final class CreateRouteTest extends TestWithMocks
{
    public function testCreate(): void
    {
        $data = ['name' => 'Company3'];
        $container = $this->setupContainer();
        $db = $container->get('db');

        # Check that the table doesn't already contain a row with this name
        $statement = $db->query("SELECT count(name) as mycount FROM companies WHERE name='Company3'");
        $beforeInsert = $statement->fetch();
        $this->assertEquals(0, $beforeInsert['mycount']);

        $route = new CompanyRoute($container);
        $response = $route->create(
            $this->createRequest()->withParsedBody($data),
            new Response(),
            [],
        );

        # Verify the json in the response contains the inserted value and no warnings
        $respObject = json_decode($response->getBody()->__toString(), true);
        $this->assertTrue($respObject['success']);
        $this->assertEquals(0, count($respObject['warnings']));
        $this->assertEquals(3, $respObject['data']['companyId']);
        $this->assertEquals($data['name'], $respObject['data']['name']);

        # Check that the table does contain a row with this name
        $statement = $db->query("SELECT count(name) as mycount FROM companies WHERE name='Company3'");
        $afterInsert = $statement->fetch();
        $this->assertEquals(1, $afterInsert['mycount']);
    }

}
