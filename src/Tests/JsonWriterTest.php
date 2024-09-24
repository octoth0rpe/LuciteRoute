<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Response;
use Lucite\Route\JsonWriter;

final class JsonWriterTest extends TestCase
{
    public function testWritesContentTypeCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->success();
        $contentTypeHeader = $result->getHeader(('content-type'));
        $this->assertEquals('application/json', $contentTypeHeader[0]);
    }

    public function testWritesSuccessWithoutDataCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->success();
        $body = $result->getBody()->__toString();
        $this->assertEquals('{"success":true,"warnings":[]}', $body);
    }

    public function testWritesSuccessWithWarningsCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->withWarnings(['warning1', 'warning2'])->success();
        $body = $result->getBody()->__toString();
        $this->assertEquals('{"success":true,"warnings":["warning1","warning2"]}', $body);
    }

    public function testWritesSuccessWithDataCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->success(['test' => 'value']);
        $body = $result->getBody()->__toString();
        $this->assertEquals('{"success":true,"warnings":[],"data":{"test":"value"}}', $body);
    }

    public function testWritesFailureWithErrorsCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->failure(['errField' => 'errValue']);
        $body = $result->getBody()->__toString();
        $this->assertEquals('{"success":false,"warnings":[],"errors":{"errField":"errValue"}}', $body);
    }

    public function testWritesFailureStatusCodeCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->failure(['errField' => 'errValue']);
        $this->assertEquals(422, $result->getStatusCode());
    }

    public function testWritesFailureWithCustopmStatusCodeCorrectly(): void
    {
        $response = new Response();
        $writer = new JsonWriter($response);
        $result = $writer->failure(['errField' => 'errValue'], 403);
        $this->assertEquals(403, $result->getStatusCode());
    }
}
