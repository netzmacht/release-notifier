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

namespace Netzmacht\ReleaseNotifier\Publisher\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Netzmacht\ReleaseNotifier\Package\Release;
use Netzmacht\ReleaseNotifier\Publisher\Publisher;
use function sprintf;

final class StatusPublisher implements Publisher
{
    /**
     * Name of the publisher. Used to match against packages.
     *
     * @var string
     */
    private $name;

    /**
     * Twitter connection.
     *
     * @var TwitterOAuth
     */
    private $connection;

    /**
     * Package configuration.
     *
     * @var array
     */
    private $configuration;

    /**
     * Condition which checks if package should be published.
     *
     * @var callable|null
     */
    private $condition;

    /**
     * StatusPublisher constructor.
     *
     * @param string        $name          Name of the publisher. Used to match against packages.
     * @param TwitterOAuth  $connection    Twitter connection.
     * @param array         $configuration Package configuration.
     * @param callable|null $condition     Condition which checks if package should be published.
     */
    public function __construct(
        string $name,
        TwitterOAuth $connection,
        array $configuration,
        ?callable $condition = null
    ) {
        $this->name          = $name;
        $this->connection    = $connection;
        $this->configuration = $configuration;
        $this->condition     = $condition;

    }

    public function supports(Release $release): bool
    {
        $found = false;

        foreach ($this->configuration as $package) {
            if ($package['package'] === $release->name()) {
                $found = isset($package['publishers'][$this->name]);
                break;
            }
        }

        if (!$found) {
            return false;
        }

        if ($this->condition) {
            return ($this->condition)($release);
        }

        return true;
    }

    public function publish(Release $release): int
    {
        // TODO: Use a renderer
        $status = [
            'status' => sprintf(
                '%s %s got released. %s',
                $release->name(),
                $release->version()->__toString(),
                $release->link()
            ),
        ];

        $this->connection->post('status/update', $status);

        return 1;
    }
}
