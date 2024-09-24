<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use Slim\Psr7\Response;

final class UpdateRouteTest extends TestWithMocks
{
    public function testUpdate(): void
    {
        $data = ['name' => 'Company1-updated'];
        $container = $this->setupContainer();
        $db = $container->get('db');

        # Check that the name isn't already the updated value
        $statement = $db->query('SELECT name FROM companies WHERE "companyId"=1');
        $beforeUpdate = $statement->fetch();
        $this->assertNotEquals($data['name'], $beforeUpdate['name']);

        $route = new CompanyRoute($container);
        $response = $route->update(
            $this->createRequest()->withParsedBody($data),
            new Response(),
            ['id' => 1],
        );

        # Verify the json in the response contains the updated value and no warnings
        $respObject = json_decode($response->getBody()->__toString(), true);
        $this->assertTrue($respObject['success']);
        $this->assertEquals(0, count($respObject['warnings']));
        $this->assertEquals(1, $respObject['data']['companyId']);
        $this->assertEquals($data['name'], $respObject['data']['name']);

        # Check that the name really was updated in the db
        $statement = $db->query('SELECT name FROM companies WHERE "companyId"=1');
        $afterUpdate = $statement->fetch();
        $this->assertEquals($data['name'], $afterUpdate['name']);
    }

}
