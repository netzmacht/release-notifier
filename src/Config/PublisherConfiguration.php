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

namespace App\Config;

use App\Renderer\Renderer;

/**
 * Interface PackageConfiguration
 */
interface PublisherConfiguration
{
    /**
     * Get the package name.
     *
     * @return string
     */
    public function packageName(): string;

    /**
     * Get the forum id.
     *
     * @return int
     */
    public function forumId(): int;

    /**
     * Get the custom renderer.
     *
     * @return Renderer|null
     */
    public function renderer(): ?Renderer;
}
