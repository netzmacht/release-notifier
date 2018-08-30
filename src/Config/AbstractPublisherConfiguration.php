<?php

/**
 * packagist-release-publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace App\Config;

use App\Renderer\Renderer;

abstract class AbstractPublisherConfiguration implements PublisherConfiguration
{
    /**
     * @var string
     */
    private $packageName;

    /**
     * @var int
     */
    private $forumId;

    /**
     * @var Renderer|null
     */
    private $renderer;

    /**
     * AbstractPackagePublisherConfiguration constructor.
     *
     * @param string        $packageName
     * @param int           $forumId
     * @param Renderer|null $renderer
     */
    protected function __construct(string $packageName, int $forumId, ?Renderer $renderer)
    {
        $this->packageName = $packageName;
        $this->forumId     = $forumId;
        $this->renderer    = $renderer;
    }

    /**
     * @param array $config
     *
     * @return PublisherConfiguration
     */
    public static function fromArray(array $config): PublisherConfiguration
    {
        return new static($config['package'], $config['forumId'], $config['renderer']);
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function forumId(): int
    {
        return $this->forumId;
    }

    public function renderer(): ?Renderer
    {
        return $this->renderer;
    }
}
