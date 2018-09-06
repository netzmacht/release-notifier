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

declare(strict_types=1);

namespace App\Publisher;

/**
 * Interface PackageConfiguration
 */
final class PublisherConfiguration
{
    /**
     * Name of the publisher.
     *
     * @var string
     */
    private $name;

    /**
     * Name of the type.
     *
     * @var string
     */
    private $type;

    /**
     * Configuration.
     *
     * @var array
     */
    private $config;

    /**
     * Condition.
     *
     * Callable has to have following signature: function(Release $release): bool
     *
     * @var callable|null
     */
    private $condition;

    /**
     * PublisherConfiguration constructor.
     *
     * @param string        $name      Name of the publisher.
     * @param string        $type      Name of the type.
     * @param array         $config    Configuration.
     * @param callable|null $condition Condition.
     */
    public function __construct(string $name, string $type, array $config = [], ?callable $condition = null)
    {
        $this->name      = $name;
        $this->type      = $type;
        $this->config    = $config;
        $this->condition = $condition;
    }

    /**
     * Create configuration from array.
     *
     * @param array $config The publisher configuration.
     *
     * @return PublisherConfiguration
     */
    public static function fromArray(array $config): self
    {
        return new self(
            $config['name'],
            $config['type'],
            $config['config'],
            $config['condition'] ?? null
        );
    }

    /**
     * Name of the publisher.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the type of the publisher.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get a config value.
     *
     * @param string $key The config key.
     *
     * @return mixed
     */
    public function config(string $key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    /**
     * Return a callable which might check if a release is supported.
     *
     * Callable has to have following signature: function(Release $release): bool
     *
     * @return callable|null
     */
    public function condition(): ?callable
    {
        return $this->condition;
    }
}
