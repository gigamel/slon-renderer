<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface RenderableInterface
{
    public function render(string $view, array $vars = []): string;
}
