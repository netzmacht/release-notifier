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

use App\Packagist\Release;
use App\Publisher\Tapatalk\PostPublisher;

return [
    // Url to packagist. Only change if you have a custom packagist installation.
    'packagist' => [
        'url' => 'https://packagist.org',
    ],

    'publishers' => [
        // Tapatalk endpoint, usually the board url with mobiquo/mobiquo.php suffix
        [
            'name'     => 'forum-de',
            'type'     => PostPublisher::class,
            'config'   => [
                'url'       => 'https://community.contao.org/de/mobiquo/mobiquo.php',
                'user'      => '',
                'password'  => '',
                'condition' => function (Release $release): bool {
                    if ($release->version()->hasPreReleaseSuffix()) {
                        return false;
                    }

                    return true;
                },
                'renderer' => [
                    'subject' => function (Release $release): string {
                        return sprintf('Package "%s" released', $release);
                    },
                    'body' => function (Release $release): string {
                        return sprintf(
                            'Version "%s" of "%s" got released. More information at [url]%s[/url]',
                            $release->version(),
                            $release->name(),
                            $release->link()
                        );
                    }
                ]
            ]
        ],
    ],

    // List of packages. Supported publishers are "post" and "topic". For a topic, you don't have to define a topicId.
    // Custom renderer for each package are not supported right now but planned to be implemented.
    'packages'  => [
        [
            'package'    => 'netzmacht/contao-leaflet-libraries',
            'publishers' => [
                'forum-de' => [
                    'forumId'   => 169,
                    'topicId'   => 68417,
                    'renderer'  => null,
                ]
            ],
        ],
    ],
];
