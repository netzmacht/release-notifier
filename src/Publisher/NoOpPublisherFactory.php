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

namespace App\Publisher;

/**
 * Class NoOpPublisherFactory
 *
 * @package App\Publisher
 */
final class NoOpPublisherFactory implements PublisherFactory
{
    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        return $type === NoOpPublisher::class;
    }

    /**
     * {@inheritdoc}
     */
    public function create(PublisherConfiguration $configuration, array $packages): Publisher
    {
        return new NoOpPublisher();
    }
}
