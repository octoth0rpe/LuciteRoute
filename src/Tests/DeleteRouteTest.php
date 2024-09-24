<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use Slim\Psr7\Response;

final class DeleteRouteTest extends TestWithMocks
{
    public function testDelete(): void
    {
        $container = $this->setupContainer();
        $db = $container->get('db');

        # Check that there are 2 rows before deleting
        $statement = $db->query('SELECT count(name) as mycount FROM companies');
        $beforeDelete = $statement->fetch();
        $this->assertEquals(2, $beforeDelete['mycount']);

        $route = new CompanyRoute($container);
        $response = $route->delete(
            $this->createRequest(),
            new Response(),
            ['id' => 1],
        );

        # Verify the json in the response contains the updated value and no warnings
        $respObject = json_decode($response->getBody()->__toString(), true);
        $this->assertTrue($respObject['success']);
        $this->assertEquals(0, count($respObject['warnings']));

        # Check that there is only 1 row after deleting
        $statement = $db->query('SELECT count(name) as mycount FROM companies');
        $afterDelete = $statement->fetch();
        $this->assertEquals(1, $afterDelete['mycount']);
    }

}
