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

namespace Netzmacht\ReleaseNotifier\Publisher;

use Netzmacht\ReleaseNotifier\Package\Release;

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
     * Publish the release and return the number of created publishes.
     *
     * @param Release $release The release.
     *
     * @return int
     */
    public function publish(Release $release): int;
}
