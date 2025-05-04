<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;

final class HomeControllerTest extends TestCase
{
    #[Test]
    public function homepage ()
    {
        $app = $this->createApp();

        $response = $app->handle($this->createRequest("GET", ""));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
    }
}