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

namespace App\Release;

/**
 * Class Release
 *
 * @package App\Release
 */
final class Release
{
    /**
     * @var string
     */
    private $vendor;

    /**
     * @var string
     */
    private $package;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $link;

    /**
     * @param string $release
     *
     * @param string $link
     *
     * @return Release
     */
    public static function fromStringAndLink(string $release, string $link): self
    {
        preg_match('#([^/]+)/([^/]+)\s(.*)#', $release, $matches);
        $release = new static();

        $release->vendor  = $matches[1];
        $release->package = $matches[2];
        $release->version = $matches[3];
        $release->link    = $link;

        return $release;
    }

    /**
     * Get vendor.
     *
     * @return string
     */
    public function vendor(): string
    {
        return $this->vendor;
    }

    /**
     * Get package.
     *
     * @return string
     */
    public function package(): string
    {
        return $this->package;
    }

    public function name(): string
    {
        return $this->vendor . '/' . $this->package;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * Get link.
     *
     * @return string
     */
    public function link(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s/%s %s', $this->vendor, $this->package, $this->version);
    }
}
