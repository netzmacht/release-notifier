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

namespace Netzmacht\ReleaseNotifier\Publisher;

use Netzmacht\ReleaseNotifier\Package\Release;
use function array_key_exists;

/**
 * Abstract publisher providing a default implementation for the supports method
 */
abstract class AbstractPublisher implements Publisher
{
    /**
     * Name of the publisher. Used to match against packages.
     *
     * @var string
     */
    private $name;

    /**
     * Package configuration.
     *
     * @var array
     */
    private $configuration;

    /**
     * Condition which checks if package should be published.
     *
     * @var callable|null
     */
    private $condition;

    /**
     * AbstractPublisher constructor.
     *
     * @param string        $name          Name of the publisher. Used to match against packages.
     * @param array         $configuration Package configuration.
     * @param callable|null $condition     Condition which checks if package should be published.
     */
    public function __construct(string $name, array $configuration, ?callable $condition)
    {
        $this->name          = $name;
        $this->configuration = $configuration;
        $this->condition     = $condition;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        $configuration = $this->packageConfiguration($release);

        if ($configuration === null) {
            return false;
        }

        if ($this->condition) {
            return ($this->condition)($release);
        }

        return true;
    }

    /**
     * Get the name of the publisher.
     *
     * @return string
     */
    protected function name(): string
    {
        return $this->name;
    }

    /**
     * Get the package configuration.
     *
     * @param Release $release Package release.
     *
     * @return array|null
     */
    protected function packageConfiguration(Release $release): ?array
    {
        foreach ($this->configuration as $package) {
            if (isset($package['publishers'][$this->name]) && $package['package'] === $release->name()) {
                return $package;
            }
        }

        return null;
    }

    /**
     * Get the publisher configuration.
     *
     * @param Release $release Package release.
     *
     * @return array|null
     */
    protected function publisherConfiguration(Release $release): ?array
    {
        $configuration = $this->packageConfiguration($release);
        if ($configuration === null) {
            return null;
        }

        return $configuration['publishers'][$this->name];
    }

    /**
     * Get the render options from the package configuration.
     *
     * @param Release $release Package release.
     *
     * @return array
     */
    protected function renderOptions(Release $release): array
    {
        $configuration = $this->packageConfiguration($release);
        if (!$configuration) {
            return [];
        }

        if (array_key_exists('options', $configuration['publishers'][$this->name])) {
            return $configuration['publishers'][$this->name]['options'];
        }

        if (array_key_exists('options', $configuration)) {
            return $configuration['options'];
        }

        return [];
    }
}
