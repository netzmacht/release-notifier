<?php

/**
 * packagist-release-publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace App\Renderer;

use App\Release\Release;

/**
 * Class CallableRenderer
 *
 * @package App\Renderer
 */
final class CallableRenderer implements Renderer
{
    /**
     * @var callable
     */
    private $subjectRenderer;

    /**
     * @var callable
     */
    private $bodyRenderer;

    /**
     * CallableRenderer constructor.
     *
     * @param callable $subjectRenderer
     * @param callable $bodyRenderer
     */
    public function __construct(callable $subjectRenderer, callable $bodyRenderer)
    {
        $this->subjectRenderer = $subjectRenderer;
        $this->bodyRenderer    = $bodyRenderer;
    }

    /**
     * @param Release $release
     *
     * @return string
     */
    public function renderSubject(Release $release): string
    {
        return (string) ($this->subjectRenderer)($release);
    }

    /**
     * @param Release $release
     *
     * @return string
     */
    public function renderBody(Release $release): string
    {
        return (string) ($this->bodyRenderer)($release);
    }
}
