<?php

/**
 * Release notifier.
 *
 * @package    release-notifier
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace App\Publisher;

use App\Package\Release;

/**
 * Class DebuggingPublisher
 *
 * @package App\Publisher
 */
final class NoOpPublisher implements Publisher
{
    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Release $release): void
    {
    }
}
