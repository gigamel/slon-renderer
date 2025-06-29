<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface RenderCompositeInterface extends RenderableInterface
{
    public function setRenderer(RendererInterface $renderer): void;
}