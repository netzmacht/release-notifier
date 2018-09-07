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

use function pathinfo;
use function sprintf;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class History
 */
final class History
{
    private const HISTORY_FILE = '%s/.%s.history.json';

    /**
     * File system information.
     *
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * LastRunInformation constructor.
     *
     * @param Filesystem $fileSystem The file system.
     */
    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Get the stored last run information for the given configuration file.
     *
     * @param string $configurationFile The configuration file.
     *
     * @return ConfigHistory
     */
    public function get(string $configurationFile): ConfigHistory
    {
        $pathInfo = pathinfo($configurationFile);
        $fileName = sprintf(static::HISTORY_FILE, $pathInfo['dirname'], $pathInfo['basename']);

        return new ConfigHistory($this->fileSystem, $fileName);
    }
}
