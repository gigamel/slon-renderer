<?php

declare(strict_types=1);

namespace Slon\Renderer;

use Slon\Renderer\Contract\RenderCompositeInterface;
use Slon\Renderer\Contract\RendererInterface;
use Slon\Renderer\Exception\NotFoundViewException;
use Slon\Renderer\Exception\RendererException;
use SplFileInfo;

use function array_key_exists;
use function is_dir;
use function is_file;
use function sprintf;

final class RenderComposite implements RenderCompositeInterface
{
    private readonly string $viewsDir;
    
    /** @var array<string, RendererInterface> */
    private array $renderers = [];
    
    /**
     * @throws RendererException
     */
    public function __construct(
        string $viewsDir,
    ) {
        $this->viewsDir = rtrim($viewsDir, '/');
        if (!is_dir($viewsDir)) {
            throw new RendererException(sprintf(
                'Views directory "%s" does not exists',
                $viewsDir,
            ));
        }
    }

    public function setRenderer(RendererInterface $renderer): void
    {
        $this->renderers[$renderer->getExtension()] = $renderer;
    }

    /**
     * @throws NotFoundViewException
     * @throws RendererException
     */
    public function render(string $view, array $vars = []): string
    {
        if (!is_file($view)) {
            throw new NotFoundViewException(sprintf(
                'View "%s" not found',
                $view,
            ));
        }

        $info = new SplFileInfo($view);
        if (array_key_exists($info->getExtension(), $this->renderers)) {
            return $this->renderers[$info->getExtension()]->render(
                $info->getRealPath(),
                $vars,
            );
        }

        throw new RendererException(sprintf(
            'Renderer "%s" does not support extension "%s"',
            static::class,
            $info->getExtension(),
        ));
    }
}