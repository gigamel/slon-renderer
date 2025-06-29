<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface RendererInterface
{
    public function addExtension(ExtensionInterface $extension): void;
    
    public function supports(string $view): bool;
    
    public function render(string $view, array $vars = []): string;
}
