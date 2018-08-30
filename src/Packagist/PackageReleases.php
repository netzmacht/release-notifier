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

namespace App\Packagist;

use GuzzleHttp\Client;
use App\Config\Configuration;
use App\Release\Release;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Reader;

final class PackageReleases
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * PackageReleases constructor.
     *
     * @param ClientInterface        $client
     * @param Configuration $configuration
     */
    public function __construct(ClientInterface $client, Configuration $configuration)
    {
        $this->client = $client;
        $this->configuration = $configuration;

        Reader::setHttpClient($client);
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return Release[]|iterable
     */
    public function since(\DateTimeInterface $dateTime): iterable
    {
        $releases = [];

        foreach ($this->configuration->packages() as $package) {
            $channel = Reader::import(sprintf('feeds/package.%s.rss', $package->packageName()));

            /** @var \Zend\Feed\Reader\Entry\Rss $item */
            foreach ($channel as $item) {
                if ($item->getDateCreated() > $dateTime) {
                    $releases[] = Release::fromStringAndLink($item->getId(), $item->getLink());
                }
            }
        }

        return $releases;
    }
}
