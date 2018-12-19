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
use Netzmacht\ReleaseNotifier\Publisher\Publisher;
use Netzmacht\ReleaseNotifier\Publisher\PublisherConfiguration;
use Netzmacht\ReleaseNotifier\Publisher\PublisherFactory;

/**
 * Publisher factory for twitter related publishers.
 */
final class TwitterPublisherFactory implements PublisherFactory
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return $type === StatusPublisher::class;
    }

    /**
     * {@inheritdoc}
     */
    public function create(PublisherConfiguration $configuration, array $packages): Publisher
    {
        $connection = $this->createConnection($configuration);
        $renderer   = $configuration->config('renderer');

        Assert::that($renderer)->isInstanceOf(Renderer::class);

        return new StatusPublisher(
            $configuration->name(),
            $connection,
            $renderer,
            $packages,
            $configuration->condition()
        );
    }

    /**
     * Create twitter API connection.
     *
     * @param PublisherConfiguration $configuration Publisher configuration.
     *
     * @return TwitterOAuth
     */
    private function createConnection(PublisherConfiguration $configuration): TwitterOAuth
    {
        $consumerKey       = $configuration->config('consumer_key');
        $consumerSecret    = $configuration->config('consumer_secret');
        $accessToken       = $configuration->config('access_token');
        $accessTokenSecret = $configuration->config('access_token_secret');

        Assert::that($consumerKey)->string();
        Assert::that($consumerSecret)->string();
        Assert::that($accessToken)->string();
        Assert::that($accessTokenSecret)->string();

        return new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    }
}
