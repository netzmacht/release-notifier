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

namespace Netzmacht\ReleaseNotifier\Publisher;

use function array_keys;
use function array_map;
use function implode;
use function sprintf;

/**
 * Class ConnectionState
 */
final class ConnectionState
{
    /**
     * Name of the publisher.
     *
     * @var string
     */
    private $publisherName;

    /**
     * Connection state.
     *
     * @var bool
     */
    private $connected;

    /**
     * Connection state details.
     *
     * @var array
     */
    private $details;

    /**
     * Error message.
     *
     * @var string|null
     */
    private $error;

    /**
     * Connection state constructor.
     *
     * @param string      $publisherName Name of the publisher.
     * @param bool        $connected     Connection state.
     * @param array       $details       Connection state details.
     * @param string|null $error         Error message.
     */
    private function __construct(string $publisherName, bool $connected, array $details, ?string $error = null)
    {
        $this->publisherName = $publisherName;
        $this->connected     = $connected;
        $this->details       = $details;
        $this->error         = $error;
    }

    /**
     * Create connection state for a valid connection.
     *
     * @param string $publisherName Name of the publisher.
     * @param array  $details       Connection state details.
     *
     * @return self
     */
    public static function connected(string $publisherName, array $details = []): self
    {
        return new self($publisherName, true, $details);
    }

    /**
     * Create connection state for a failed connection.
     *
     * @param string $publisherName Name of the publisher.
     * @param string $error         Error message.
     * @param array  $details       Connection state details.
     *
     * @return self
     */
    public static function failed(string $publisherName, string $error, array $details = []): self
    {
        return new self($publisherName, false, $details, $error);
    }

    /**
     * Get the error state.
     *
     * @return bool
     */
    public function error(): bool
    {
        return !$this->connected;
    }

    /**
     * Convert to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->connected) {
            return sprintf(
                'Connection of publisher "%s" established. Details: [%s]',
                $this->publisherName,
                $this->detailsToString()
            );
        }

        return sprintf(
            'Connection of publisher "%s" failed with error "%s". Details: [%s]',
            $this->publisherName,
            $this->error,
            $this->detailsToString()
        );
    }

    /**
     * Convert the details information to string.
     *
     * @return string
     */
    private function detailsToString(): string
    {
        $details = implode(
            ', ',
            array_map(
                function ($value, $key) {
                    return $key . ': ' . $value;
                },
                $this->details,
                array_keys($this->details)
            )
        );

        return $details;
    }
}
