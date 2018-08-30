<?php

/**
 * packagist-release-publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later
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
     * @var Renderer
     */
    private $renderer;

    /**
     * PostPublisher constructor.
     *
     * @param Client        $tapatalk
     * @param Configuration $configuration
     * @param               $renderer
     */
    public function __construct(Client $tapatalk, Configuration $configuration, Renderer $renderer)
    {
        $this->client        = $tapatalk;
        $this->configuration = $configuration;
        $this->renderer      = $renderer;
    }

    /**
     * @param Release $release
     *
     * @return bool
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
     * @param Release $release
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
     * @param Client                 $client
     * @param PublisherConfiguration $configuration
     * @param string                 $renderSubject
     * @param string                 $renderBody
     *
     * @return void
     */
    abstract protected function createEntry(
        Client $client,
        PublisherConfiguration $configuration,
        string $renderSubject,
        string $renderBody
    ): void;
}
