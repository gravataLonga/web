<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;

final class TwigExtensionTest extends TestCase
{
    #[Test]
    public function generate_csrf()
    {
        $container = $this->getContainer();

        /** @var \Twig\Environment $twig */
        $twig = $container->get('twig');
        /** @var \Slim\Csrf\Guard $guard */
        $guard = $container->get('csrf');

        $html = $twig->createTemplate('{{ csrf() }}')->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString($guard->getTokenName(), $html);
        $this->assertStringContainsString($guard->getTokenValue(), $html);
        $this->assertStringContainsString($guard->getTokenNameKey(), $html);
        $this->assertStringContainsString($guard->getTokenValueKey(), $html);
    }

    #[Test]
    public function can_call_assets()
    {
        $container = $this->getContainer();
        $twig = $container->get('twig');

        $html = $twig->createTemplate('{{ asset("css/app.css") }}')->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString("<link", $html);
        $this->assertStringContainsString("css/app.css", $html);

    }
}