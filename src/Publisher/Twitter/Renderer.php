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

namespace Netzmacht\ReleaseNotifier\Publisher\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Netzmacht\ReleaseNotifier\Package\Release;

/**
 * Interface Renderer renders a twitter status
 */
interface Renderer
{
    /**
     * Render the twitter status array.
     *
     * As minimum requirement the status key has to be defined in the return value. You're able to upload media and
     * add them as well if required.
     *
     * @param Release      $release    The release.
     * @param TwitterOAuth $connection Twitter API connection.
     * @param array        $options    Rendering options.
     *
     * @return array
     */
    public function renderStatus(Release $release, TwitterOAuth $connection, array $options = []): array;
}
