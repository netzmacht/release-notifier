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

namespace App\Publisher;

use App\Release\Release;

/**
 * Class DelegatingPublisher
 */
final class DelegatingPublisher implements Publisher
{
    /**
     * Release not publisher.
     *
     * @var Publisher[]
     */
    private $publisher;

    /**
     * DelegatingPublisher constructor.
     *
     * @param Publisher[] $publisher
     */
    public function __construct(array $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Release $release): bool
    {
        foreach ($this->publisher as $publisher) {
            if ($publisher->supports($release)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Release $release
     */
    public function publish(Release $release): void
    {
        foreach ($this->publisher as $publisher) {
            if ($publisher->supports($release)) {
                $publisher->publish($release);

                return;
            }
        }

        throw new \RuntimeException(
            sprintf('Publishing release "%s" failed. None supporting publisher found.', $release)
        );
    }
}
