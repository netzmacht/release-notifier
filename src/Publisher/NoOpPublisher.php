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
 * Class DebuggingPublisher
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
    public function publish(Release $release): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(): array
    {
        return [];
    }
}
