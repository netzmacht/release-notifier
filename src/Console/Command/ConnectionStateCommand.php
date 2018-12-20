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
use Netzmacht\ReleaseNotifier\Publisher\DelegatingPublisher;
use Netzmacht\ReleaseNotifier\Publisher\Publisher;
use Netzmacht\ReleaseNotifier\Publisher\PublisherConfiguration;
use Netzmacht\ReleaseNotifier\Publisher\PublisherFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command checks the the connection states of required APIs for the publishing.
 */
final class ConnectionStateCommand extends AbstractConfigBasedCommand
{
    /**
     * Publisher factory.
     *
     * @var PublisherFactory
     */
    private $publisherFactory;

    /**
     * PublishReleaseNoteCommand constructor.
     *
     * @param PublisherFactory $publisherFactory The publisher factory.
     * @param History          $history          Last run information.
     */
    public function __construct(
        PublisherFactory $publisherFactory,
        History $history
    ) {
        parent::__construct($history, 'connection-state');

        $this->publisherFactory = $publisherFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Test the connection states of each publisher');

        $this->addOption(
            'wait',
            'w',
            InputOption::VALUE_REQUIRED,
            'Define a wait interval in seconds between each publishing. Maybe necessary if publishing limit is set.',
            null
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config      = $this->loadConfig($input);
        $publisher   = $this->createPublisher($config);
        $established = 0;
        $failed      = 0;

        foreach ($publisher->connect() as $connectionState) {
            if ($connectionState->error()) {
                $failed++;
            } else {
                $established++;
            }

            $output->writeln($connectionState, OutputInterface::VERBOSITY_VERBOSE);
        }

        if ($failed) {
            $output->writeln(sprintf('<error>%s connections established. %s failed.</error>', $established, $failed));

            return 1;
        }

        $output->writeln(sprintf('<info>%s connections established. %s failed.</info>', $established, $failed));

        return 0;
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
