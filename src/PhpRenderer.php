<?php

declare(strict_types=1);

namespace Slon\Renderer;

use RuntimeException;
use Slon\Renderer\Contract\ExtensionInterface;
use Slon\Renderer\Contract\RendererInterface;
use Slon\Renderer\Exception\NotFoundViewException;

use function array_key_exists;
use function is_file;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function sprintf;
use function str_ends_with;

class PhpRenderer implements RendererInterface
{
    protected array $extensions = [];

    public function addExtension(ExtensionInterface $extension): void
    {
        $this->extensions[$extension->getName()] = $extension;
    }
    
    public function supports(string $view): bool
    {
        return str_ends_with($view, '.php');
    }
    
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
