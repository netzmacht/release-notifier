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

namespace App\Console\Command;

use App\History\LastRunInformation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Webmozart\PathUtil\Path;

/**
 * Class AbstractCommand
 */
abstract class AbstractConfigBasedCommand extends Command
{
    /**
     * Last run information.
     *
     * @var LastRunInformation
     */
    protected $lastRunInformation;

    /**
     * AbstractConfigBasedCommand constructor.
     *
     * @param LastRunInformation $lastRunInformation Last run information.
     * @param null|string        $name               Command name.
     */
    public function __construct(LastRunInformation $lastRunInformation, ?string $name = null)
    {
        parent::__construct($name);

        $this->lastRunInformation = $lastRunInformation;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument(
            'config',
            InputArgument::REQUIRED,
            'Path to the config.php file.'
        );

        $this->addOption(
            'since',
            's',
            InputOption::VALUE_REQUIRED,
            'Optional time reference, parseable by \DateTimeImmutable'
        );

        $this->addOption(
            'ignore-last-run',
            'i',
            InputOption::VALUE_NONE,
            'If true the last run is ignored.'
        );
    }

    /**
     * Load the configuration.
     *
     * @param InputInterface $input The console input.
     *
     * @return array
     */
    protected function loadConfig(InputInterface $input): array
    {
        return include $this->getConfigFileArgument($input);
    }

    /**
     * Get the normalized config file argument.
     *
     * @param InputInterface $input The input.
     *
     * @return string
     */
    protected function getConfigFileArgument(InputInterface $input): string
    {
        $configFile = Path::normalize($input->getArgument('config'));

        if (!Path::isAbsolute($configFile)) {
            $configFile = Path::makeAbsolute($configFile, Path::normalize(getcwd()));
        }

        return Path::canonicalize($configFile);
    }

    /**
     * Get the sinc date.
     *
     * @param InputInterface $input The console input.
     *
     * @return \DateTimeInterface
     */
    protected function getSince(InputInterface $input): \DateTimeInterface
    {
        $lastRun = null;

        if (!$input->getOption('ignore-last-run')) {
            $lastRun = $this->lastRunInformation->get($this->getConfigFileArgument($input));
        }

        if ($input->getOption('since')) {
            $since = new \DateTimeImmutable($input->getOption('since'));

            if ($since > $lastRun) {
                return $since;
            }

            return $lastRun;
        }

        if ($lastRun) {
            return $lastRun;
        }

        $dateTime = new \DateTimeImmutable();
        $dateTime = $dateTime->setTime(0, 0);

        return $dateTime;
    }
}
