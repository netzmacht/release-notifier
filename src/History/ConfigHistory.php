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

namespace Netzmacht\ReleaseNotifier\History;

use function file_get_contents;
use function json_encode;
use function json_decode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ConfigHistory
 */
final class ConfigHistory
{
    /**
     * The file system.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * The config history file.
     *
     * @var string
     */
    private $historyFileName;

    /**
     * The package information.
     *
     * @var array
     */
    private $information;

    /**
     * ConfigHistory constructor.
     *
     * @param Filesystem $filesystem      The file system.
     * @param string     $historyFileName The history file name.
     */
    public function __construct(Filesystem $filesystem, string $historyFileName)
    {
        $this->filesystem      = $filesystem;
        $this->historyFileName = $historyFileName;
    }

    /**
     * Get last run information for a package.
     *
     * @param string $package The full package name.
     *
     * @return LastRun|null
     */
    public function get(string $package): ?LastRun
    {
        $this->load();

        if (isset($this->information[$package])) {
            return $this->information[$package];
        }

        return null;
    }

    /**
     * Update the last run information.
     *
     * @param string  $package The full package name.
     * @param LastRun $lastRun THe last run information.
     *
     * @return void;
     */
    public function update(string $package, LastRun $lastRun): void
    {
        $this->load();

        $this->information[$package] = $lastRun->toArray();
        $this->filesystem->dumpFile($this->historyFileName, json_encode($this->information));
    }

    /**
     * Load the last run information.
     *
     * @return void
     *
     * @throws \RuntimeException When json data could not be converted.
     */
    private function load(): void
    {
        if ($this->information !== null) {
            return;
        }

        $this->information = [];

        if (!$this->filesystem->exists($this->historyFileName)) {
            return;
        }

        $content     = file_get_contents($this->historyFileName);
        $information = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Loading last run information failed with json_error:' . json_last_error());
        }

        foreach ($information as $package => $history) {
            $this->information[$package] = LastRun::fromArray($history);
        }
    }
}
