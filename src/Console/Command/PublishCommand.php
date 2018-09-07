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
use Netzmacht\ReleaseNotifier\History\LastRun;
use Netzmacht\ReleaseNotifier\Package\Releases;
use Netzmacht\ReleaseNotifier\Publisher\DelegatingPublisher;
use Netzmacht\ReleaseNotifier\Publisher\Publisher;
use Netzmacht\ReleaseNotifier\Publisher\PublisherConfiguration;
use Netzmacht\ReleaseNotifier\Publisher\PublisherFactory;
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
     * @var Releases
     */
    private $packageReleases;

    /**
     * PublishReleaseNoteCommand constructor.
     *
     * @param PublisherFactory $publisherFactory The publisher factory.
     * @param Releases         $packageReleases  Package releases.
     * @param History          $history          Last run information.
     */
    public function __construct(
        PublisherFactory $publisherFactory,
        Releases $packageReleases,
        History $history
    ) {
        parent::__construct($history, 'publish');

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
        $configFile = $this->getConfigFileArgument($input);
        $config     = $this->loadConfig($input);
        $history    = $this->history->get($configFile);
        $publisher  = $this->createPublisher($config);
        $total      = 0;

        foreach ($config['packages'] as $package) {
            $lastRun  = $history->get($configFile, $package['package']);
            $since    = $this->getSince($input, $lastRun);
            $releases = $this->packageReleases->since($package['package'], $since);

            $count  = count($releases);
            $total += $count;

            $output->writeln(
                sprintf(
                    '%s New releases of "%s" since %s',
                    $count,
                    $package['package'],
                    $since->format(DATE_ATOM)
                )
            );

            foreach ($releases as $release) {
                $publisher->publish($release);

                $output->writeln(
                    sprintf(' - %s release published', $release->version()),
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }

            if (!$input->getOption('ignore-last-run')) {
                $history->update($package['package'], LastRun::now($releases->lastModified()));
            }
        }

        $output->writeln(sprintf('%s releases published', $total));
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
}
