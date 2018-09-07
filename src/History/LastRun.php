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

namespace Netzmacht\ReleaseNotifier\History;

/**
 * Class LastRun
 */
final class LastRun
{
    /**
     * Last run date.
     *
     * @var \DateTimeImmutable
     */
    private $lastRun;

    /**
     * Last modified information of the release source.
     *
     * @var \DateTimeImmutable
     */
    private $lastModified;

    /**
     * LastRun constructor.
     *
     * @param \DateTimeImmutable $lastRun      Last run date.
     * @param \DateTimeImmutable $lastModified Last modified information of the release source.
     */
    public function __construct(\DateTimeImmutable $lastRun, \DateTimeImmutable $lastModified)
    {
        $this->lastRun      = $lastRun;
        $this->lastModified = $lastModified;
    }

    /**
     * Create last run with current timestamp and last modified date.
     *
     * @param \DateTimeImmutable $lastModified The last modified date.
     *
     * @return LastRun
     */
    public static function now(\DateTimeImmutable $lastModified): self
    {
        return new self(new \DateTimeImmutable(), $lastModified);
    }

    /**
     * Get last run data.
     *
     * @return \DateTimeImmutable
     */
    public function lastRun(): \DateTimeImmutable
    {
        return $this->lastRun;
    }

    /**
     * Get last modified date.
     *
     * @return \DateTimeImmutable
     */
    public function lastModified(): \DateTimeImmutable
    {
        return $this->lastModified;
    }

    /**
     * Check if other date is in between the last modified and last run information.
     *
     * If this is the case the release source wasn't in sync during the last run and releases were added later on.
     *
     * @param \DateTimeImmutable $other The other date.
     *
     * @return bool
     */
    public function isInBetween(\DateTimeImmutable $other): bool
    {
        // Other was before the last modified setting of the last run.
        if ($other <= $this->lastModified) {
            return false;
        }

        // Other was before the last run. There are probably other releases in the meantime.
        if ($other < $this->lastRun) {
            return true;
        }

        return false;
    }
}
