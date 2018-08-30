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

namespace App\Publisher\Tapatalk;

use App\Config\Configuration;
use App\Config\PublisherConfiguration;
use App\Publisher\Publisher;
use App\Release\Release;
use App\Renderer\Renderer;
use Netzmacht\Tapatalk\Client;

/**
 * Class AbstractPublisher
 *
 * @package App\Publisher\Tapatalk
 */
abstract class AbstractPublisher implements Publisher
{
    protected const CONFIGURATION_CLASS = PublisherConfiguration::class;

    /**
     * Tapatalk client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Publishing configuration.
     *
     * @var Configuration
     */
    private $configuration;

    /**
     * Release renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * PostPublisher constructor.
     *
     * @param Client        $tapatalk      The tapatalk api client.
     * @param Configuration $configuration The package configuration.
     * @param Renderer      $renderer      The renderer.
     */
    public function __construct(Client $tapatalk, Configuration $configuration, Renderer $renderer)
    {
        $this->client        = $tapatalk;
        $this->configuration = $configuration;
        $this->renderer      = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        if (!$this->configuration->has($release->name())) {
            return false;
        }

        $configuration = $this->configuration->package($release->name());
        $configClass   = static::CONFIGURATION_CLASS;

        return $configuration instanceof $configClass;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If invalid configuration is loaded.
     */
    public function publish(Release $release): void
    {
        $configuration = $this->configuration->package($release->name());
        $configClass   = static::CONFIGURATION_CLASS;

        if (!$configuration instanceof $configClass) {
            throw new \RuntimeException(
                sprintf('%s required. "%s" given.', $configClass, get_class($configuration))
            );
        }

        $renderer = $configuration->renderer() ?: $this->renderer;
        $subject  = $renderer->renderSubject($release);
        $body     = $renderer->renderBody($release);

        $this->createEntry($this->client, $configuration, $subject, $body);
    }

    /**
     * Create an entry.
     *
     * @param Client                 $client        The tapatalk api client.
     * @param PublisherConfiguration $configuration The publisher configuration.
     * @param string                 $subject       The rendered subject.
     * @param string                 $body          The rendered body.
     *
     * @return void
     */
    abstract protected function createEntry(
        Client $client,
        PublisherConfiguration $configuration,
        string $subject,
        string $body
    ): void;
}
