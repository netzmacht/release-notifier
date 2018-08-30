<?php

/**
 * packagist-release-publisher.
 *
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus
 * @license    LGPL-3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace App\Command;

use App\Packagist\PackageReleases;
use App\Publisher\Publisher;
use App\Release\Release;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PublishReleaseNoteCommand
 */
final class PublishReleaseNoteCommand extends Command
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var PackageReleases
     */
    private $packageReleases;

    /**
     * PublishReleaseNoteCommand constructor.
     *
     * @param Publisher $publisher
     */
    public function __construct(Publisher $publisher, Filesystem $filesystem, PackageReleases $packageReleases)
    {
        parent::__construct('publish-release-note');

        $this->publisher       = $publisher;
        $this->filesystem      = $filesystem;
        $this->packageReleases = $packageReleases;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption(
            'since',
            's',
            InputOption::VALUE_OPTIONAL,
            'Optional time reference, parseable by \DateTimeImmutable'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $since   = $this->getSince($input);
        $count   = 0;

        foreach ($this->packageReleases->since($since) as $release) {
            $this->publisher->publish($release);
            $count++;

            $this->logReleasedPublished($output, $release);
        }

        $this->logSummary($output, $count);
    }

    /**
     * @param InputInterface $input
     *
     * @return \DateTimeInterface
     * @throws \Exception
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

    private function logReleasedPublished(OutputInterface $output, Release $release): void
    {
        $output->writeln(sprintf('Release "%s" published', $release), OutputInterface::VERBOSITY_VERBOSE);
    }

    private function logSummary(OutputInterface $output, int $count): void
    {
        if ($count) {
            $output->writeln(sprintf('%s release note published.', $count), OutputInterface::VERBOSITY_NORMAL);
        } else {
            $output->writeln(sprintf('No releases found.', $count), OutputInterface::VERBOSITY_VERBOSE);
        }
    }
}
