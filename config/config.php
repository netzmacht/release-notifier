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

use App\Console\Command\ListReleasesCommand;
use App\Console\Command\PublishReleaseNotesCommand;
use App\Publisher\Tapatalk\TapatalkPublisherFactory;

return (function () {
    $uid            = posix_getuid();
    $shellUser      = posix_getpwuid($uid);
    $applicationDir = $shellUser['dir'] . '/.packagist-release-publisher';

    return [
        'application' => [
            'name' => 'packagist-release-publisher',
            'version' => '0.2.0',
            'commands' => [
                PublishReleaseNotesCommand::class,
                ListReleasesCommand::class
            ]
        ],
        'packagist' => [
            'url' => 'https://packagist.org',
        ],
        'publishers' => [
            'factories' => [
                TapatalkPublisherFactory::class
            ]
        ],
        'lastRunFile'  => $applicationDir . '/lastrun.json',
    ];
})();
