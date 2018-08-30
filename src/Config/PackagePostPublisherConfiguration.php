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

/**
 * Class PackagePostPublisherConfiguration
 */
final class PackagePostPublisherConfiguration extends AbstractPublisherConfiguration
{
    /**
     * @var int
     */
    private $topicId;

    /**
     * PackageTopicPublisherConfiguration constructor.
     *
     * @param string        $packageName
     * @param int           $forumId
     * @param int           $topicId
     * @param callable|null $renderer
     */
    public function __construct(string $packageName, int $forumId, int $topicId, ?callable $renderer)
    {
        parent::__construct($packageName, $forumId, $renderer);

        $this->topicId = $topicId;
    }

    /**
     * @param array $config
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
