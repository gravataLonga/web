<?php declare(strict_types=1);

namespace Gravatalonga;

use Gravatalonga\Twig\Config;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    private static bool $viteClient = false;

    private static bool $viteIsRunning = false;

    private string $viteUrl;

    private ?Manifest $manifest;

    private mixed $csrf;

    private bool $isProduction;

    public function __construct(
        private readonly ContainerInterface $container
    )
    {
        $this->isProduction = $this->container->get('app.env') === 'production';
        $this->viteUrl = $this->container->get('vite.url');
        self::$viteIsRunning = $this->viteIsRunning();
        try {
            $this->manifest = $this->container->get(Manifest::class);
        } catch (\Exception $e) {
            $this->manifest = null;
        }

        $this->csrf = $this->container->get('csrf');
    }

    public function getGlobals(): array
    {
        return [
            'config' => new Config($this->container, ['app.debug', 'app.name', 'app.url', 'app.env', 'path.public', 'vite.url', 'vite.manifest']),
        ];
    }

    public function getFunctions (): array
    {
        return [
            new TwigFunction('asset', [$this, 'asset'], ['is_safe' => ['html']]),
            new TwigFunction('csrf', [$this, 'csrf'], ['is_safe' => ['html']]),
        ];
    }

    public function asset(string $entry): string
    {
        if (self::$viteIsRunning) {
            return $this->getHtmlForEntrypoint($this->viteUrl . '/' . $entry);
        }

        if (is_null($this->manifest)) {
            return $this->getHtmlForEntrypoint($entry, null);
        }

        $entrypoint = $this->manifest->getEntrypoint($entry);

        if (! $entrypoint) {
            return '';
        }

        $url = $entrypoint['url'];
        $hash = $entrypoint['hash'];

        return $this->getHtmlForEntrypoint($url, $hash);
    }

    private function getHtmlForEntrypoint(string $url, ?string $hash = null): string
    {
        $asset = '';

        if (str_contains($url, '.css')) {
            $asset .= '<link rel="stylesheet" href="' . $url . '" ' . (! $hash ? '' : 'integrity="' . $hash) . '" />';
        }

        if (str_contains($url, '.js')) {
            $asset .= '<script type="module" src="' . $url . '"></script>';
        }

        if (self::$viteIsRunning && ! self::$viteClient) {
            self::$viteClient = true;
            $asset = '<script type="module" src="' . $this->viteUrl . '/@vite/client"></script>' . $asset;
        }

        return $asset;
    }

    private function viteIsRunning(): bool
    {
        if ($this->isProduction) {
            return false;
        }

        $handle = curl_init($this->viteUrl . '/@vite/client');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_exec($handle);
        $error = curl_errno($handle);
        curl_close($handle);

        return !$error;
    }

    public function csrf(): string
    {
        $token = $this->csrf->generateToken();
        $keys = array_keys($token);

        return implode(' ', array_map(function ($key) use ($token) {
            return '<input type="hidden" name="' . $key . '" value="' . $token[$key] . '" />';
        }, $keys));
    }
}