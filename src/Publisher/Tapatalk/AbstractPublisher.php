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

namespace Netzmacht\ReleaseNotifier\Publisher\Tapatalk;

use Netzmacht\ReleaseNotifier\Publisher\AbstractPublisher as BaseAbstractPublisher;
use Netzmacht\ReleaseNotifier\Package\Release;
use Netzmacht\ReleaseNotifier\Publisher\Tapatalk\Renderer\Renderer;
use Netzmacht\Tapatalk\Client;

/**
 * Class AbstractPublisher
 */
abstract class AbstractPublisher extends BaseAbstractPublisher
{
    /**
     * Tapatalk client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Release renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * PostPublisher constructor.
     *
     * @param string        $name                 Name of the publisher. Used to match against packages.
     * @param Client        $tapatalk             The tapatalk api client.
     * @param Renderer      $renderer             The renderer.
     * @param array         $packageConfiguration The package configuration.
     * @param callable|null $condition            Condition which checks if package should be published.
     */
    public function __construct(
        string $name,
        Client $tapatalk,
        Renderer $renderer,
        array $packageConfiguration,
        ?callable $condition = null
    ) {
        parent::__construct($name, $packageConfiguration, $condition);

        $this->client   = $tapatalk;
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If invalid configuration is loaded.
     */
    public function publish(Release $release): int
    {
        $configuration = $this->packageConfiguration($release);
        if (!$configuration) {
            throw new \RuntimeException(
                sprintf('Package %s is not configured for renderer "%s".', $release->name(), self::class)
            );
        }

        $options = $this->renderOptions($release);
        $subject = $this->renderer->renderSubject($release, $options);
        $body    = $this->renderer->renderBody($release, $options);

        $this->createEntry($this->client, $configuration, $subject, $body);

        return 1;
    }

    /**
     * Create an entry.
     *
     * @param Client $client        The tapatalk api client.
     * @param array  $configuration The Package configuration.
     * @param string $subject       The rendered subject.
     * @param string $body          The rendered body.
     *
     * @return void
     */
    abstract protected function createEntry(
        Client $client,
        array $configuration,
        string $subject,
        string $body
    ): void;
}
