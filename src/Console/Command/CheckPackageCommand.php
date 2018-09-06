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

namespace App\Console\Command;

use App\Package\Releases;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckPackageCommand
 *
 * @package App\Console\Command
 */
final class CheckPackageCommand extends Command
{
    /**
     * Package releases.
     *
     * @var Releases
     */
    private $releases;

    /**
     * CheckCommand constructor.
     *
     * @param Releases $packageReleases Package Releases.
     */
    public function __construct(Releases $packageReleases)
    {
        parent::__construct('check-package');

        $this->releases = $packageReleases;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Check if an arbitrary package has new releases');

        $this->addArgument(
            'package',
            InputArgument::REQUIRED,
            'Package and publisher configuration file.'
        );

        $this->addOption(
            'since',
            's',
            InputOption::VALUE_OPTIONAL
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package  = $input->getArgument('package');
        $since    = $this->getSince($input);
        $releases = $this->releases->since($package, $since);
        $count    = count($releases);

        if ($count) {
            $output->writeln(sprintf('%s releases of %s since %s', $count, $package, $since->format(DATE_ATOM)));
        }

        foreach ($releases as $release) {
            $output->writeln(
                sprintf(' - %s released (%s)', $release->version(), $release->date()->format(DATE_ATOM)),
                OutputInterface::VERBOSITY_VERBOSE
            );
        }
    }

    /**
     * Get the sinc date.
     *
     * @param InputInterface $input The console input.
     *
     * @return \DateTimeInterface
     */
    private function getSince(InputInterface $input): \DateTimeInterface
    {
        if ($input->getOption('since')) {
            return new \DateTimeImmutable($input->getOption('since'));
        }

        $dateTime = new \DateTimeImmutable();
        $dateTime = $dateTime->setTime(0, 0);

        return $dateTime;
    }
}
