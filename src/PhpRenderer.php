<?php

declare(strict_types=1);

namespace Slon\Renderer;

use RuntimeException;
use Slon\Renderer\Contract\ExtensionInterface;
use Slon\Renderer\Contract\RendererInterface;
use Slon\Renderer\Exception\NotFoundViewException;
use Slon\Renderer\Exception\RendererException;

use function array_key_exists;
use function is_callable;
use function is_file;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function sprintf;

final class PhpRenderer implements RendererInterface
{
    private array $extensions = [];

    /**
     * @throws RendererException
     */
    public function addExtension(ExtensionInterface $extension): void
    {
        if (!is_callable($extension)) {
            throw new RendererException(sprintf(
                'Renderer extension "%s" must implements __invoke method',
                $extension::class,
            ));
        }
        
        $this->extensions[$extension->getName()] = $extension;
    }
    
    public function getExtension(): string
    {
        return 'php';
    }

    /**
     * @throws NotFoundViewException
     */
    public function render(string $view, array $vars = []): string
    {
        if (!is_file($view)) {
            throw new NotFoundViewException(sprintf(
                'View "%s" not found',
                $view,
            ));
        }
        
        extract($vars);
        unset($vars);
        
        try {
            ob_start();
            require $view;
            $contents = ob_get_contents();
        } finally {
            ob_end_clean();
        }
        
        return $contents;
    }
    
    /**
     * @throws RuntimeException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (array_key_exists($name, $this->extensions)) {
            return $this->extensions[$name](...$arguments);
        }
        
        throw new RuntimeException(sprintf(
            'Undefined method %s::%s()',
            static::class,
            $name,
        ));
    }
}
