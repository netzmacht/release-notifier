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

namespace App\Rss\Http;

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
     * The guzzle client.
     *
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * Construct.
     *
     * @param GuzzleClientInterface $client The guzzle client.
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
        return new Psr7ResponseDecorator($this->client->request('GET', $uri));
    }
}
