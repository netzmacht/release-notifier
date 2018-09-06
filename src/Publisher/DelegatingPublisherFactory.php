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
 * Class DelegatingPublisherFactory
 */
final class DelegatingPublisherFactory implements PublisherFactory
{
    /**
     * Publisher factories.
     *
     * @var PublisherFactory[]
     */
    private $factories;

    /**
     * DelegatingPublisherFactory constructor.
     *
     * @param PublisherFactory[] $factories Publisher factories.
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $type): bool
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException When a publisher could not be created.
     */
    public function create(PublisherConfiguration $configuration, array $packages): Publisher
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($configuration->type())) {
                return $factory->create($configuration, $packages);
            }
        }

        throw new \RuntimeException(
            sprintf('Could not create publisher. Unsupported publisher type "%s"', $configuration->type())
        );
    }
}
