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

namespace App\Config;

/**
 * Class Configuration
 */
final class Configuration
{
    /**
     * Configuration packages.
     *
     * @var PublisherConfiguration[]
     */
    private $packages = [];

    /**
     * Create packages configuration from array.
     *
     * @param array $config The config array.
     *
     * @return Configuration
     *
     * @throws \RuntimeException If publisher is not supported.
     */
    public static function fromArray(array $config): self
    {
        $configuration = new self();
        $packages      = [];

        foreach ($config as $package) {
            switch ($package['publisher']) {
                case 'post':
                    $packages[$package['package']] = PackagePostPublisherConfiguration::fromArray($package);
                    break;

                case 'topic':
                    $packages[$package['package']] = TopicPublisherConfiguration::fromArray($package);
                    break;

                default:
                    throw new \RuntimeException(sprintf('Unsupported publisher "%s"', $package['publisher']));
            }
        }

        $configuration->packages = $packages;

        return $configuration;
    }

    /**
     * Check if configuration exists.
     *
     * @param string $packageName Package name.
     *
     * @return bool
     */
    public function has(string $packageName): bool
    {
        return isset($this->packages[$packageName]);
    }

    /**
     * Get the package configuration.
     *
     * @param string $packageName Package name.
     *
     * @return PublisherConfiguration
     *
     * @throws \InvalidArgumentException If unknown package name is given.
     */
    public function package(string $packageName): PublisherConfiguration
    {
        if (!isset($this->packages[$packageName])) {
            throw new \InvalidArgumentException(sprintf('Unknown package name "%s"', $packageName));
        }

        return $this->packages[$packageName];
    }

    /**
     * Get all packages.
     *
     * @return PublisherConfiguration[]|iterable
     */
    public function packages(): iterable
    {
        return $this->packages;
    }
}
