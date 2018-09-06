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

namespace App\Package;

use ArrayIterator;
use Countable;
use DateTimeInterface;
use IteratorAggregate;

/**
 * Class ReleaseIterator
 *
 * @package App\Package
 */
class ReleaseIterator implements IteratorAggregate, Countable
{
    /**
     * Last modified date of the package release information.
     *
     * @var DateTimeInterface
     */
    private $lastModified;

    /**
     * List of releases.
     *
     * @var Release[]|array
     */
    private $releases;

    /**
     * ReleaseIterator constructor.
     *
     * @param DateTimeInterface $lastModified Last modified date of the package release information.
     * @param Release[]|array   $releases     List of releases.
     */
    public function __construct($releases, DateTimeInterface $lastModified)
    {
        $this->lastModified = $lastModified;
        $this->releases     = $releases;
    }

    /**
     * Get the iterator.
     *
     * @return \Iterator<Release>
     */
    public function getIterator(): \Iterator
    {
        return new ArrayIterator($this->releases);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->releases);
    }
}
