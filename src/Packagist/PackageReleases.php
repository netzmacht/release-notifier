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

namespace App\Packagist;

use Composer\Semver\Semver;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\Http\ClientInterface;
use Zend\Feed\Reader\Reader;

/**
 * Class PackageReleases
 */
final class PackageReleases
{
    /**
     * Http client.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * PackageReleases constructor.
     *
     * @param ClientInterface $client Http client.
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get packages since a defined time.
     *
     * @param string             $package  The full package name, e.g. vendor/package.
     * @param \DateTimeInterface $dateTime The date since when the packages should be collected.
     *
     * @return iterable
     */
    public function since(string $package, \DateTimeInterface $dateTime): iterable
    {
        Reader::setHttpClient($this->client);

        $feedUrl     = sprintf('feeds/package.%s.rss', $package);
        $releases    = [];
        $channel     = Reader::import($feedUrl);
        $previousMap = $this->buildPreviousMap($channel);

        /** @var \Zend\Feed\Reader\Entry\Rss $item */
        foreach ($channel as $item) {
            if ($item->getDateCreated() <= $dateTime) {
                continue;
            }

            $version    = explode(' ', $item->getId(), 2)[1];
            $releases[] = Release::create(
                $item->getId(),
                $item->getLink(),
                $previousMap[$version]
            );
        }

        return $releases;
    }

    /**
     * Build a previous versions map.
     *
     * @param FeedInterface $channel The feed channel.
     *
     * @return array
     */
    private function buildPreviousMap(FeedInterface $channel): array
    {
        $map      = [];
        $versions = [];

        /** @var \Zend\Feed\Reader\Entry\Rss $item */
        foreach ($channel as $item) {
            $version       = explode(' ', $item->getId(), 2)[1];
            $versions[]    = $version;
            $map[$version] = null;
        }

        $versions = Semver::sort($versions);

        $length = count($versions);
        for ($index = 1; $index < $length; $index++) {
            $map[$versions[$index]] = Version::fromString($versions[($index - 1)]);
        }

        return $map;
    }
}
