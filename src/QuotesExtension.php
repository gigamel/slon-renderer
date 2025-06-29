<?php

declare(strict_types=1);

namespace Slon\Renderer;

use const ENT_QUOTES;

use Slon\Renderer\Contract\ExtensionInterface;

use function htmlspecialchars;

final class QuotesExtension implements ExtensionInterface
{
    public function getName(): string
    {
        return 'quotes';
    }
    
    public function __invoke(string $message): string
    {
        return htmlspecialchars($message, ENT_QUOTES);
    }
}
