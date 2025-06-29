<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface RendererInterface extends RenderableInterface, ExtensibleInterface
{
    public function getExtension(): string;
}
