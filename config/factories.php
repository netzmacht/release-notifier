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

use App\Publisher\DelegatingPublisherFactory;
use App\Publisher\PublisherFactory;
use App\Publisher\Tapatalk\TapatalkPublisherFactory;
use App\Rss\Http\Client\GuzzleClientAdapter;
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\Filesystem\Filesystem;
use Zend\Feed\Reader\Http\ClientInterface as FeedRederHttpClient;

return (function () {
    $config    = require __DIR__ . '/config.php';
    $factories = [];

    // Publisher Factories.
    $factories[TapatalkPublisherFactory::class] = function () {
        return new TapatalkPublisherFactory();
    };

    $factories[PublisherFactory::class] = function () use ($factories) {
        return new DelegatingPublisherFactory(
            [
                $factories[TapatalkPublisherFactory::class]()
            ]
        );
    };

    // Package releases.
    $factories[FeedRederHttpClient::class] = function () use ($config) {
        return new GuzzleClientAdapter(
            new HttpClient(['base_uri' => $config['packagistUrl']])
        );
    };

    $factories[\App\Packagist\PackageReleases::class] = function () use ($factories) {
        return new \App\Packagist\PackageReleases(
            $factories[FeedRederHttpClient::class]()
        );
    };

    // File system.
    $factories[Filesystem::class] = function () {
        return new Filesystem();
    };

    return $factories;
})();
