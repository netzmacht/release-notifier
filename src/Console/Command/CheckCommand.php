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

declare(strict_types=1);

namespace App\Console\Command;

use App\History\LastRunInformation;
use App\Packagist\PackageReleases;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckCommand
 *
 * @package App\Console\Command
 */
final class CheckCommand extends AbstractConfigBasedCommand
{
    /**
     * Package releases.
     *
     * @var PackageReleases
     */
    private $packageReleases;

    /**
     * CheckCommand constructor.
     *
     * @param PackageReleases    $packageReleases    Package Releases.
     * @param LastRunInformation $lastRunInformation Last run information.
     */
    public function __construct(PackageReleases $packageReleases, LastRunInformation $lastRunInformation)
    {
        parent::__construct($lastRunInformation, 'check');

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
        $config = $this->loadConfig($input);
        $since  = $this->getSince($input);
        $total  = 0;

        foreach ($config['packages'] as $package) {
            $releases = $this->packageReleases->since($package['package'], $since);
            $count    = count($releases);
            $total   += $count;

            if ($count) {
                $output->writeln(sprintf('%s releases of %s', $count, $package['package']));
            }

            foreach ($releases as $release) {
                $output->writeln(
                    sprintf(' - %s released (%s)', $release->version(), $release->date()->format(DATE_ATOM)),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }
        }

        $output->writeln(sprintf('%s releases found since %s', $total, $since->format(DATE_ATOM)));
    }
}
