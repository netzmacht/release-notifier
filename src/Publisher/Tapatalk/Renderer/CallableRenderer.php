<?php

/**
 * Packagist release publisher
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace App\Publisher\Tapatalk\Renderer;

use App\Packagist\Release;

/**
 * Class CallableRenderer
 *
 * @package App\Renderer
 */
final class CallableRenderer implements Renderer
{
    /**
     * Subject renderer.
     *
     * @var callable
     */
    private $subjectRenderer;

    /**
     * Body callable.
     *
     * @var callable
     */
    private $bodyRenderer;

    /**
     * CallableRenderer constructor.
     *
     * @param callable $subjectRenderer Subject renderer.
     * @param callable $bodyRenderer    Body callable.
     */
    public function __construct(callable $subjectRenderer, callable $bodyRenderer)
    {
        $this->subjectRenderer = $subjectRenderer;
        $this->bodyRenderer    = $bodyRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function renderSubject(Release $release): string
    {
        return (string) ($this->subjectRenderer)($release);
    }

    /**
     * {@inheritdoc}
     */
    public function renderBody(Release $release): string
    {
        return (string) ($this->bodyRenderer)($release);
    }
}
