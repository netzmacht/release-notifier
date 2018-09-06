<?php

/**
 * Release notifier.
 *
 * @package    release-notifier
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

namespace App\Publisher;

/**
 * Class PublisherFactory
 */
interface PublisherFactory
{
    /**
     * Check if factory supports publisher type.
     *
     * @param string $type The publisher type.
     *
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * Create a publisher from its configuration.
     *
     * @param PublisherConfiguration $configuration Publisher configuration.
     * @param array                  $packages      Package configurations.
     *
     * @return Publisher
     *
     * @throws \RuntimeException When publisher type is not supported.
     */
    public function create(PublisherConfiguration $configuration, array $packages): Publisher;
}
