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

namespace App\Renderer;

use App\Release\Release;

/**
 * Interface Renderer
 */
interface Renderer
{
    public function renderSubject(Release $release): ?string;

    public function renderBody(Release $release): string;
}
