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

use Netzmacht\ReleaseNotifier\Console\Command\CheckCommand;
use Netzmacht\ReleaseNotifier\Console\Command\CheckPackageCommand;
use Netzmacht\ReleaseNotifier\Console\Command\CreateConfigCommand;
use Netzmacht\ReleaseNotifier\Console\Command\PublishCommand;
use Netzmacht\ReleaseNotifier\Publisher\NoOpPublisherFactory;
use Netzmacht\ReleaseNotifier\Publisher\Tapatalk\TapatalkPublisherFactory;

return (function () {
    return [
        'application' => [
            'name' => 'release-notifier',
            'version' => '0.3.1',
            'commands' => [
                CreateConfigCommand::class,
                CheckCommand::class,
                CheckPackageCommand::class,
                PublishCommand::class,
            ]
        ],
        'packagist' => [
            'url' => 'https://packagist.org',
        ],
        'publishers' => [
            'factories' => [
                TapatalkPublisherFactory::class,
                NoOpPublisherFactory::class
            ]
        ],
        'paths' => [
            'boilerplate' => __DIR__ . '/boilerplate.php.dist',
        ],
    ];
})();
