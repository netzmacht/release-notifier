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

namespace App\Publisher;

use App\Package\Release;

/**
 * Interface Publisher
 */
interface Publisher
{
    /**
     * Check if publisher supports the release.
     *
     * @param Release $release The release.
     *
     * @return bool
     */
    public function supports(Release $release): bool;

    /**
     * Publish the release.
     *
     * @param Release $release The release.
     *
     * @return void
     */
    public function publish(Release $release): void;
}
