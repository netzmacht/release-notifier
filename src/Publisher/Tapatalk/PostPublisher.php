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

namespace App\Publisher\Tapatalk;

use App\Config\PackagePostPublisherConfiguration;
use App\Config\PublisherConfiguration;
use Netzmacht\Tapatalk\Client;

/**
 * Class PostPublisher
 */
final class PostPublisher extends AbstractPublisher
{
    protected const CONFIGURATION_CLASS = PackagePostPublisherConfiguration::class;

    /**
     * {@inheritdoc}
     */
    protected function createEntry(
        Client $client,
        PublisherConfiguration $configuration,
        string $subject,
        string $body
    ): void {
        /** @var PackagePostPublisherConfiguration $configuration */
        $client->posts()->replyTo($configuration->forumId(), $configuration->topicId(), $body, $subject ?: null);
    }
}
