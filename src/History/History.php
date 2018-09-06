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

namespace App\History;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class LastRunInformation
 */
final class History
{
    /**
     * File system information.
     *
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Map of configuration file name and the latest run time.
     *
     * @var \DateTimeInterface[]
     */
    private $information;

    /**
     * File name.
     *
     * @var string
     */
    private $fileName;

    /**
     * LastRunInformation constructor.
     *
     * @param Filesystem $fileSystem The file system.
     * @param string     $fileName   The last run file.
     */
    public function __construct(Filesystem $fileSystem, string $fileName)
    {
        $this->fileSystem = $fileSystem;
        $this->fileName   = $fileName;
    }

    /**
     * Get the stored last run information for the given configuration file.
     *
     * @param string $configurationFile The configuration file.
     *
     * @return \DateTimeInterface|null
     */
    public function get(string $configurationFile): ?\DateTimeInterface
    {
        $this->load();

        if (!isset($this->information[$configurationFile])) {
            return null;
        }

        return new \DateTimeImmutable($this->information[$configurationFile]);
    }

    /**
     * Update the last run information.
     *
     * @param string             $configurationFile The configuration file.
     * @param \DateTimeInterface $dateTime          The date time.
     *
     * @return void
     */
    public function update(string $configurationFile, \DateTimeInterface $dateTime): void
    {
        $this->load();

        $this->information[$configurationFile] = $dateTime->format(DATE_ATOM);
        $this->fileSystem->dumpFile($this->fileName, \json_encode($this->information));
    }

    /**
     * Load the last run information.
     *
     * @return void
     *
     * @throws \RuntimeException When information could not be serialized as json string.
     */
    private function load(): void
    {
        if ($this->information !== null) {
            return;
        }

        if (!$this->fileSystem->exists($this->fileName)) {
            $this->fileSystem->dumpFile($this->fileName, '{}');
            $this->information = [];

            return;
        }

        $content           = file_get_contents($this->fileName);
        $this->information = \json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Loading last run information failed with json_error:' . json_last_error());
        }
    }
}
