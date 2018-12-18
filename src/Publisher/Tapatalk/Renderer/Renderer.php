<?php

/**
 * Release notifier.
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
 * Renderer renders the subject and body for a release.
 */
interface Renderer
{
    /**
     * Render the subject.
     *
     * @param Release $release The release.
     * @param array   $options Rendering options.
     *
     * @return string
     */
    public function renderSubject(Release $release, array $options = []): ?string;

    /**
     * Render the body.
     *
     * @param Release $release The release.
     * @param array   $options Rendering options.
     *
     * @return string
     */
    public function renderBody(Release $release, array $options = []): string;
}
