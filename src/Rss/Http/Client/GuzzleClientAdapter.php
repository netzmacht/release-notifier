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

namespace App\Rss\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Zend\Feed\Reader\Http\ClientInterface as FeedReaderHttpClientInterface;
use Zend\Feed\Reader\Http\Psr7ResponseDecorator;

/**
 * Class GuzzleClient
 */
final class GuzzleClientAdapter implements FeedReaderHttpClientInterface
{
    /**
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * @param GuzzleClientInterface|null $client
     */
    public function __construct(GuzzleClientInterface $client)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function get($uri)
    {
        return new Psr7ResponseDecorator(
            $this->client->request('GET', $uri)
        );
    }
}
