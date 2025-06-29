<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface RendererCompositeInterface extends RenderableInterface
{
    public function addRenderer(RendererInterface $renderer): void;
}