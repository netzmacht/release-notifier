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
 * Class Version
 */
final class Version
{
    /**
     * The version number.
     *
     * @var array
     */
    private $versionNumber;

    /**
     * The pre release suffix.
     *
     * @var null|string
     */
    private $preReleaseSuffix;

    /**
     * UnknownFormattedVersion constructor.
     *
     * @param array       $versionNumber    The version number as array.
     * @param null|string $preReleaseSuffix The pre release suffix.
     */
    private function __construct(array $versionNumber, ?string $preReleaseSuffix = null)
    {
        $this->versionNumber    = $versionNumber;
        $this->preReleaseSuffix = $preReleaseSuffix;
    }

    /**
     * Create version from string.
     *
     * @param string $version The version as string.
     *
     * @return Version
     *
     * @throws \InvalidArgumentException If an unsupported version string is given.
     */
    public static function fromString(string $version): Version
    {
        if (!preg_match('/([0-9\.]+)\-?(.*)/', $version, $matches)) {
            throw new \InvalidArgumentException('Unsupported version format.');
        }

        $versionNumber = explode('.', $matches[1]);

        return new static($versionNumber, $matches[2]);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $formatted = implode('.', $this->versionNumber);

        if ($this->preReleaseSuffix) {
            return $formatted . '-' . $this->preReleaseSuffix;
        }

        return $formatted;
    }

    /**
     * Check if version is a semantic version.
     *
     * @return bool
     */
    public function isSemantic(): bool
    {
        return count($this->versionNumber) === 3;
    }

    /**
     * Check if version has a pre release suffix.
     *
     * @return bool
     */
    public function hasPreReleaseSuffix(): bool
    {
        return !empty($this->preReleaseSuffix);
    }

    /**
     * Check if version is a major version number.
     *
     * @return bool
     */
    public function isMajor(): bool
    {
        if ($this->hasPreReleaseSuffix()) {
            return false;
        }

        $length = count($this->versionNumber);
        for ($index = 1; $index < $length; $index++) {
            if ($this->versionNumber[$index] > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if version is a minor version number.
     *
     * @return bool
     */
    public function isMinor(): bool
    {
        if ($this->hasPreReleaseSuffix()) {
            return false;
        }

        if (!isset($this->versionNumber[1]) || $this->versionNumber[1] === 0) {
            return false;
        }

        $length = count($this->versionNumber);
        for ($index = 2; $index < $length; $index++) {
            if ($this->versionNumber[$index] > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if version is a patch version number.
     *
     * @return bool
     */
    public function isPatch(): bool
    {
        if ($this->hasPreReleaseSuffix()) {
            return false;
        }

        if (!isset($this->versionNumber[2]) || $this->versionNumber[2] === 0) {
            return false;
        }

        $length = count($this->versionNumber);
        for ($index = 3; $index < $length; $index++) {
            if ($this->versionNumber[$index] > 0) {
                return false;
            }
        }

        return true;
    }
}
