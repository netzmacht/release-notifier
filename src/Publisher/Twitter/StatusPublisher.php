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
use Assert\Assert;
use Netzmacht\ReleaseNotifier\Package\Release;
use Netzmacht\ReleaseNotifier\Publisher\AbstractPublisher;

/**
 * Class StatusPublisher publishes a twitter status.
 */
final class StatusPublisher extends AbstractPublisher
{
    /**
     * Twitter connection.
     *
     * @var TwitterOAuth
     */
    private $connection;

    /**
     * Twitter status renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * StatusPublisher constructor.
     *
     * @param string        $name          Name of the publisher. Used to match against packages.
     * @param TwitterOAuth  $connection    Twitter connection.
     * @param Renderer      $renderer      Twitter status renderer.
     * @param array         $configuration Package configuration.
     * @param callable|null $condition     Condition which checks if package should be published.
     */
    public function __construct(
        string $name,
        TwitterOAuth $connection,
        Renderer $renderer,
        array $configuration,
        ?callable $condition = null
    ) {
        parent::__construct($name, $configuration, $condition);

        $this->connection = $connection;
        $this->renderer   = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Release $release): int
    {
        $status = $this->renderer->renderStatus($release, $this->connection, $this->renderOptions($release));

        Assert::that($status)->keyExists('status');
        Assert::that($status['status'])->string();

        $this->connection->post('status/update', $status);

        return 1;
    }
}
