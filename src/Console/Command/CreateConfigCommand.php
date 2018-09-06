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

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\PathUtil\Path;

/**
 * Class CreateConfigCommand
 */
final class CreateConfigCommand extends Command
{
    /**
     * File system.
     *
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Path to the boilerplate file.
     *
     * @var string
     */
    private $configBoilerplate;

    /**
     * CreateConfigCommand constructor.
     *
     * @param Filesystem $fileSystem        File system.
     * @param string     $configBoilerplate Path to the boilerplate file.
     */
    public function __construct(Filesystem $fileSystem, string $configBoilerplate)
    {
        parent::__construct('create-config');

        $this->fileSystem        = $fileSystem;
        $this->configBoilerplate = $configBoilerplate;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Create base structure of the config file');

        $this->addArgument(
            'config',
            InputArgument::REQUIRED,
            'Path to the config.php file.'
        );

        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_NONE,
            'If set an existing configuration file is overridden.'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If config file already exists and force option is not set.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $this->getConfigFileArgument($input);
        $force      = $input->getOption('force');

        if ($this->fileSystem->exists($configFile)) {
            if ($force) {
                $this->fileSystem->remove($configFile);
            } else {
                throw new \RuntimeException(sprintf('Config file "%s" already exists', $configFile));
            }
        }

        $this->fileSystem->copy($this->configBoilerplate, $configFile);
        $output->writeln(sprintf('Configuration boilerplate created at "%s".', $configFile));
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
}
