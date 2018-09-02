<?php
/*
/**
 * Packagist release publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

return (function () {
    $uid            = posix_getuid();
    $shellUser      = posix_getpwuid($uid);
    $applicationDir = $shellUser['dir'] . '/.packagist-release-publisher';

    return [
        'lastRunFile'  => $applicationDir . '/lastrun.json',
        'packagistUrl' => 'https://packagist.org',
    ];
})();
