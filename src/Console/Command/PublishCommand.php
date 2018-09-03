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
use App\Publisher\DelegatingPublisher;
use App\Publisher\Publisher;
use App\Publisher\PublisherConfiguration;
use App\Publisher\PublisherFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PublishReleaseNoteCommand
 */
final class PublishCommand extends AbstractConfigBasedCommand
{
    /**
     * Publisher factory.
     *
     * @var PublisherFactory
     */
    private $publisherFactory;

    /**
     * Package releases.
     *
     * @var PackageReleases
     */
    private $packageReleases;

    /**
     * PublishReleaseNoteCommand constructor.
     *
     * @param PublisherFactory   $publisherFactory   The publisher factory.
     * @param PackageReleases    $packageReleases    Package releases.
     * @param LastRunInformation $lastRunInformation Last run information.
     */
    public function __construct(
        PublisherFactory $publisherFactory,
        PackageReleases $packageReleases,
        LastRunInformation $lastRunInformation
    ) {
        parent::__construct($lastRunInformation, 'publish');

        $this->publisherFactory = $publisherFactory;
        $this->packageReleases  = $packageReleases;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setDescription('Publish new released packages based on a configuration file');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $since     = $this->getSince($input);
        $total     = 0;
        $config    = $this->loadConfig($input);
        $publisher = $this->createPublisher($config);

        $output->writeln(sprintf('Check packages released since %s:', $since->format(DATE_ATOM)));

        foreach ($config['packages'] as $package) {
            $releases = $this->packageReleases->since($package['package'], $since);
            $count    = count($releases);
            $total   += $total;

            $output->writeln(sprintf('%s releases of %s', $count, $package['package']));

            foreach ($releases as $release) {
                $publisher->publish($release);

                $output->writeln(
                    sprintf(' - %s release published', $release->version()),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }
        }

        $output->writeln(sprintf('%s releases published', $total));

        $this->updateLastRun($this->getConfigFileArgument($input), $input->getOption('ignore-last-run'));
    }

    /**
     * Create the publisher.
     *
     * @param array $config The configuration.
     *
     * @return Publisher
     */
    private function createPublisher(array $config): Publisher
    {
        $publishers = [];

        foreach ($config['publishers'] as $publisherConfig) {
            $publishers[] = $this->publisherFactory->create(
                PublisherConfiguration::fromArray($publisherConfig),
                $config['packages']
            );
        }

        return new DelegatingPublisher($publishers);
    }

    /**
     * Update the last run information.
     *
     * @param string $configurationFile The configuration file.
     * @param bool   $ignore            If true the last run is ignored.
     *
     * @return void
     */
    private function updateLastRun(string $configurationFile, bool $ignore): void
    {
        if ($ignore) {
            return;
        }

        $this->lastRunInformation->update($configurationFile, new \DateTimeImmutable());
    }
}
