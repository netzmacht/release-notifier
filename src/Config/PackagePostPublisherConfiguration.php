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

use App\Publisher\Tapatalk\Renderer\Renderer;

/**
 * Class PackagePostPublisherConfiguration
 */
final class PackagePostPublisherConfiguration extends AbstractPublisherConfiguration
{
    /**
     * The topic id.
     *
     * @var int
     */
    private $topicId;

    /**
     * AbstractPackagePublisherConfiguration constructor.
     *
     * @param string        $packageName The package name.
     * @param int           $forumId     The forum id.
     * @param int           $topicId     The topic id.
     * @param Renderer|null $renderer    A custom renderer.
     */
    public function __construct(string $packageName, int $forumId, int $topicId, ?Renderer $renderer = null)
    {
        parent::__construct($packageName, $forumId, $renderer);

        $this->topicId = $topicId;
    }

    /**
     * Create configuration from array.
     *
     * @param array $config The config array.
     *
     * @return AbstractPublisherConfiguration
     */
    public static function fromArray(array $config): PublisherConfiguration
    {
        return new static($config['package'], $config['forumId'], $config['topicId'], $config['renderer']);
    }

    /**
     * Get postId.
     *
     * @return int
     */
    public function topicId(): int
    {
        return $this->topicId;
    }
}
