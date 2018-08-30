<?php

/**
 * Packagist release publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace App\Renderer;

use App\Release\Release;

/**
 * Renderer renders the subject and body for a release.
 */
interface Renderer
{
    /**
     * Render the subject.
     *
     * @param Release $release The release.
     *
     * @return string
     */
    public function renderSubject(Release $release): ?string;

    /**
     * Render the body.
     *
     * @param Release $release The release.
     *
     * @return string
     */
    public function renderBody(Release $release): string;
}
