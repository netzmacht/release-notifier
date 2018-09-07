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

namespace Netzmacht\ReleaseNotifier\Publisher\Tapatalk;

use Netzmacht\ReleaseNotifier\Publisher\PublisherConfiguration;
use Netzmacht\ReleaseNotifier\Publisher\Publisher;
use Netzmacht\ReleaseNotifier\Publisher\PublisherFactory;
use Netzmacht\ReleaseNotifier\Publisher\Tapatalk\Renderer\CallbackRenderer;
use Netzmacht\ReleaseNotifier\Publisher\Tapatalk\Renderer\Renderer;
use Netzmacht\Tapatalk\Client as TapatalkApiClient;
use Netzmacht\Tapatalk\Factory;

/**
 * Class PublisherFactory
 */
final class TapatalkPublisherFactory implements PublisherFactory
{
    /**
     * Check if factory supports publisher type.
     *
     * @param string $type The publisher type.
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        switch ($type) {
            case TopicPublisher::class:
            case PostPublisher::class:
                return true;

            default:
                return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException When publisher type is not supported.
     */
    public function create(PublisherConfiguration $configuration, array $packages): Publisher
    {
        switch ($configuration->type()) {
            case TopicPublisher::class:
            case PostPublisher::class:
                return $this->createPublisher($configuration->type(), $configuration, $packages);

            default:
                throw new \RuntimeException('Unsupported publisher type "%s".', $configuration->type());
        }
    }

    /**
     * Create the topic publisher.
     *
     * @param string                 $publisherClass Class of the publisher.
     * @param PublisherConfiguration $configuration  The publisher configuration.
     * @param array                  $packages       Packages configuration.
     *
     * @return AbstractPublisher
     */
    private function createPublisher(
        string $publisherClass,
        PublisherConfiguration $configuration,
        array $packages
    ): AbstractPublisher {
        $tapatalk = $this->createTapatalkApiClient($configuration);
        $renderer = $this->createRenderer($configuration);

        return new $publisherClass(
            $configuration->name(),
            $tapatalk,
            $renderer,
            $packages,
            $configuration->condition()
        );
    }

    /**
     * Create the tapatalk client api based on the publisher configuration.
     *
     * @param PublisherConfiguration $configuration The publisher configuration.
     *
     * @return TapatalkApiClient
     */
    private function createTapatalkApiClient(PublisherConfiguration $configuration): TapatalkApiClient
    {
        return Factory::connect(
            $configuration->config('url'),
            $configuration->config('user'),
            $configuration->config('password')
        );
    }

    /**
     * Create the renderer.
     *
     * @param PublisherConfiguration $configuration The publisher configuration.
     *
     * @return Renderer
     */
    private function createRenderer(PublisherConfiguration $configuration): Renderer
    {
        return new CallbackRenderer(
            $configuration->config('renderer')['subject'],
            $configuration->config('renderer')['body']
        );
    }
}
