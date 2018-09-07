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

namespace Netzmacht\ReleaseNotifier\Console\Command;

use Netzmacht\ReleaseNotifier\History\History;
use Netzmacht\ReleaseNotifier\Package\Releases;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckCommand
 */
final class CheckCommand extends AbstractConfigBasedCommand
{
    /**
     * Package releases.
     *
     * @var Releases
     */
    private $packageReleases;

    /**
     * CheckCommand constructor.
     *
     * @param Releases $packageReleases Package Releases.
     * @param History  $history         Last run information.
     */
    public function __construct(Releases $packageReleases, History $history)
    {
        parent::__construct($history, 'check');

        $this->packageReleases = $packageReleases;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setDescription('Check if new releases are available based on a configuration file');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config     = $this->loadConfig($input);
        $configFile = $this->getConfigFileArgument($input);
        $history    = $this->history->get($configFile);
        $total      = 0;

        foreach ($config['packages'] as $package) {
            $lastRun  = $history->get($package['package']);
            $since    = $this->getSince($input, $lastRun);
            $releases = $this->packageReleases->since($package['package'], $since);
            $count    = count($releases);
            $total   += $count;

            if ($count) {
                $output->writeln(
                    sprintf('%s releases of %s since %s', $count, $package['package'], $since->format(DATE_ATOM))
                );
            }

            foreach ($releases as $release) {
                $output->writeln(
                    sprintf(' - %s released (%s)', $release->version(), $release->date()->format(DATE_ATOM)),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }
        }

        $output->writeln(sprintf('%s releases found', $total));
    }
}
