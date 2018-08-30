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

namespace App\Publisher\Tapatalk;

use App\Config\PackagePostPublisherConfiguration;
use App\Config\PublisherConfiguration;
use Netzmacht\Tapatalk\Client;

/**
 * Class PostPublisher
 *
 * @package App\Publisher\Tapatalk
 */
final class PostPublisher extends AbstractPublisher
{
    protected const CONFIGURATION_CLASS = PackagePostPublisherConfiguration::class;

    /**
     * @param Client                                                   $client
     * @param PackagePostPublisherConfiguration|PublisherConfiguration $configuration
     * @param string                                                   $subject
     * @param string                                                   $body
     */
    protected function createEntry(
        Client $client,
        PublisherConfiguration $configuration,
        string $subject,
        string $body
    ): void {
        $client->posts()->replyTo($configuration->forumId(), $configuration->topicId(), $subject, $body);
    }
}
