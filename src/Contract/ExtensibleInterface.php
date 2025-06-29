<?php

declare(strict_types=1);

namespace Slon\Renderer\Contract;

interface ExtensibleInterface
{
    public function addExtension(ExtensionInterface $extension): void;
}
