<?php

/**
 * @package    packagist-release-publisher
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 netzmacht David Molineus. All rights reserved
 * @filesource
 *
 */

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class AbstractCommand
 */
abstract class AbstractCommand extends Command
{
    /**
     * @param InputInterface $input
     *
     * @return array
     */
    protected function loadConfig(InputInterface $input): array
    {
        return include getcwd() . '/' . $input->getArgument('config');
    }
}
