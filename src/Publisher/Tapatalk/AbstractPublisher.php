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

namespace App\Publisher\Tapatalk;

use App\Publisher\Publisher;
use App\Package\Release;
use App\Publisher\Tapatalk\Renderer\Renderer;
use Netzmacht\Tapatalk\Client;

/**
 * Class AbstractPublisher
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
     * Tapatalk client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Package configuration.
     *
     * @var array
     */
    private $configuration;

    /**
     * Release renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * Condition which checks if package should be published.
     *
     * @var callable|null
     */
    private $condition;

    /**
     * PostPublisher constructor.
     *
     * @param string        $name                 Name of the publisher. Used to match against packages.
     * @param Client        $tapatalk             The tapatalk api client.
     * @param Renderer      $renderer             The renderer.
     * @param array         $packageConfiguration The package configuration.
     * @param callable|null $condition            Condition which checks if package should be published.
     */
    public function __construct(
        string $name,
        Client $tapatalk,
        Renderer $renderer,
        array $packageConfiguration,
        ?callable $condition = null
    ) {
        $this->name          = $name;
        $this->client        = $tapatalk;
        $this->configuration = $packageConfiguration;
        $this->renderer      = $renderer;
        $this->condition     = $condition;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        $found = false;

        foreach ($this->configuration as $package) {
            if ($package['package'] === $release->name()) {
                $found = isset($package['publishers'][$this->name]);
                break;
            }
        }

        if (!$found) {
            return false;
        }

        if ($this->condition) {
            return ($this->condition)($release);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If invalid configuration is loaded.
     */
    public function publish(Release $release): void
    {
        $configuration = null;

        foreach ($this->configuration as $package) {
            if ($package['package'] === $release->name() && isset($package['publishers'][$this->name])) {
                $configuration = $package['publishers'][$this->name];
                break;
            }
        }

        if (!$configuration) {
            throw new \RuntimeException(
                sprintf('Package %s is not configured for renderer "%s".', $release->name(), self::class)
            );
        }

        $subject = $this->renderer->renderSubject($release);
        $body    = $this->renderer->renderBody($release);

        $this->createEntry($this->client, $configuration, $subject, $body);
    }

    /**
     * Create an entry.
     *
     * @param Client $client        The tapatalk api client.
     * @param array  $configuration The Package configuration.
     * @param string $subject       The rendered subject.
     * @param string $body          The rendered body.
     *
     * @return void
     */
    abstract protected function createEntry(
        Client $client,
        array $configuration,
        string $subject,
        string $body
    ): void;
}
