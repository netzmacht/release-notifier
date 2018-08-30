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

namespace App\Config;

use App\Renderer\Renderer;

/**
 * Interface PackageConfiguration
 */
interface PublisherConfiguration
{
    public function packageName(): string;

    public function forumId(): int;

    public function renderer(): ?Renderer;
}
