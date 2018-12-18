<?php

/**
 * Release notifier
 *
 * @package    release-notifier
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/release-notifier/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\ReleaseNotifier\Publisher\Tapatalk\Renderer;

use Netzmacht\ReleaseNotifier\Package\Release;

/**
 * Class CallableRenderer
 */
final class CallbackRenderer implements Renderer
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
    public function renderSubject(Release $release, array $options = []): string
    {
        return (string) ($this->subjectRenderer)($release, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function renderBody(Release $release, array $options = []): string
    {
        return (string) ($this->bodyRenderer)($release, $options);
    }
}
