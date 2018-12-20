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

use function array_map;
use Netzmacht\ReleaseNotifier\Package\Release;

/**
 * Class DelegatingPublisher
 */
final class DelegatingPublisher implements Publisher
{
    /**
     * Release not publisher.
     *
     * @var Publisher[]
     */
    private $publisher;

    /**
     * DelegatingPublisher constructor.
     *
     * @param Publisher[] $publisher
     */
    public function __construct(array $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        foreach ($this->publisher as $publisher) {
            if ($publisher->supports($release)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Release $release): int
    {
        $count = 0;

        foreach ($this->publisher as $publisher) {
            if ($publisher->supports($release)) {
                $count += $publisher->publish($release);
            }
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(): array
    {
        return array_merge(
            ...array_map(
                function (Publisher $publisher) {
                    return $publisher->connect();
                },
                $this->publisher
            )
        );
    }
}
