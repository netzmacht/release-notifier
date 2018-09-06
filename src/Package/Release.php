<?php

/**
 * Release notifier.
 *
 * @package    release-notifier
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/tapatalk-client-api/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace App\Package;

use DateTimeInterface;

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
     * Release date.
     *
     * @var DateTimeInterface
     */
    private $date;

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
     * Previous version.
     *
     * @var Version|null
     */
    private $previous;

    /**
     * Release constructor.
     */
    private function __construct()
    {
    }

    /**
     * Create from string and link.
     *
     * @param string             $release  The release as string.
     * @param string             $link     The link.
     * @param \DateTimeImmutable $date     The release date.
     * @param Version|null       $previous Previous version.
     *
     * @return Release
     */
    public static function create(
        string $release,
        string $link,
        \DateTimeImmutable $date,
        ?Version $previous = null
    ): self {
        preg_match('#([^/]+)/([^/]+)\s(.*)#', $release, $matches);
        $release = new static();

        $release->vendor   = $matches[1];
        $release->package  = $matches[2];
        $release->version  = Version::fromString($matches[3]);
        $release->link     = $link;
        $release->previous = $previous;
        $release->date     = $date;

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
     * The release date.
     *
     * @return \DateTimeInterface
     */
    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Get previous version.
     *
     * @return Version|null
     */
    public function previous(): ?Version
    {
        return $this->previous;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('%s/%s %s', $this->vendor, $this->package, $this->version);
    }
}
