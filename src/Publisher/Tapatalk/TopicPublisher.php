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

namespace Netzmacht\ReleaseNotifier\Publisher\Tapatalk;

use Netzmacht\Tapatalk\Client;

/**
 * Class TopicPublisher
 */
final class TopicPublisher extends AbstractPublisher
{
    /**
     * {@inheritdoc}
     */
    protected function createEntry(
        Client $client,
        array $configuration,
        string $subject,
        string $body
    ): void {
        $result = $client->topics()->createNewTopic($configuration['forumId'], $subject, $body);

        if (isset($configuration['sticky'])) {
            $client->moderation()->stickTopic((string) $result['topicId']);
        }
    }
}
