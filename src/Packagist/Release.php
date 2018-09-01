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

namespace App\Packagist;

/**
 * Class Release
 */
final class Release
{
    /**
     * Vendor name.
     *
     * @var string
     */
    private $vendor;

    /**
     * Package name.
     *
     * @var string
     */
    private $package;

    /**
     * Package version.
     *
     * @var Version
     */
    private $version;

    /**
     * Package link.
     *
     * @var string
     */
    private $link;

    /**
     * Release constructor.
     */
    private function __construct()
    {
    }

    /**
     * Create from string and link.
     *
     * @param string $release The release as string.
     * @param string $link    The link.
     *
     * @return Release
     */
    public static function fromStringAndLink(string $release, string $link): self
    {
        preg_match('#([^/]+)/([^/]+)\s(.*)#', $release, $matches);
        $release = new static();

        $release->vendor  = $matches[1];
        $release->package = $matches[2];
        $release->version = Version::fromString($matches[3]);
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

    /**
     * Get the full name of the package with vendor.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->vendor . '/' . $this->package;
    }

    /**
     * Get version.
     *
     * @return Version
     */
    public function version(): Version
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
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('%s/%s %s', $this->vendor, $this->package, $this->version);
    }
}
