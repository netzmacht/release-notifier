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

namespace App\Package;

/**
 * Interface Releases
 */
interface Releases
{
    /**
     * Get all release of a package since a defined date.
     *
     * @param string             $package  The package name.
     * @param \DateTimeInterface $dateTime Get only packages newer than the date.
     *
     * @return ReleaseIterator
     */
    public function since(string $package, \DateTimeInterface $dateTime): ReleaseIterator;
}
