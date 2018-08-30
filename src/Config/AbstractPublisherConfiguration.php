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

use App\Renderer\Renderer;

/**
 * Class AbstractPublisherConfiguration
 */
abstract class AbstractPublisherConfiguration implements PublisherConfiguration
{
    /**
     * The package name.
     *
     * @var string
     */
    private $packageName;

    /**
     * The forum id.
     *
     * @var int
     */
    private $forumId;

    /**
     * A custom renderer.
     *
     * @var Renderer|null
     */
    private $renderer;

    /**
     * AbstractPackagePublisherConfiguration constructor.
     *
     * @param string        $packageName The package name.
     * @param int           $forumId     The forum id.
     * @param Renderer|null $renderer    A custom renderer.
     */
    protected function __construct(string $packageName, int $forumId, ?Renderer $renderer = null)
    {
        $this->packageName = $packageName;
        $this->forumId     = $forumId;
        $this->renderer    = $renderer;
    }

    /**
     * Craete configuration from array.
     *
     * @param array $config The config array.
     *
     * @return PublisherConfiguration
     */
    public static function fromArray(array $config): PublisherConfiguration
    {
        return new static($config['package'], $config['forumId'], $config['renderer']);
    }

    /**
     * Get the package name.
     *
     * @return string
     */
    public function packageName(): string
    {
        return $this->packageName;
    }

    /**
     * Get the forum id.
     *
     * @return int
     */
    public function forumId(): int
    {
        return $this->forumId;
    }

    /**
     * Get the custom renderer.
     *
     * @return Renderer|null
     */
    public function renderer(): ?Renderer
    {
        return $this->renderer;
    }
}
