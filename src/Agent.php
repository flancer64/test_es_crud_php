<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2020
 */

namespace Flancer64\Test\Es;

use Elasticsearch\ClientBuilder;

class Agent
{
    private const SIZE_DEF = 10000;
    /** @var \Elasticsearch\Client */
    private $client;
    /** @var string */
    private $index;

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    public function indexGetInfo($index = null)
    {
        $params = ['index' => $index ?? $this->index];
        $settings = $this->client->indices()->getSettings($params);
        $mapping = $this->client->indices()->getMapping($params);
        return [$settings, $mapping];
    }

    public function indexGetItems($index = null)
    {
        $params = [
            'index' => $index ?? $this->index,
            'size' => self::SIZE_DEF,
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];
        return $this->client->search($params);
    }

    public function initClient($host = 'localhost', $port = 9200, $scheme = 'http')
    {
        $hostLocal = ['host' => $host, 'scheme' => $scheme, 'port' => $port];
        $this->client = ClientBuilder::create()
            ->setHosts([$hostLocal])
            ->build();
    }

    public function itemAdd($id, $data)
    {
        $params = [
            'index' => $this->index,
            'id' => $id,
            'body' => $data
        ];
        return $this->client->index($params);
    }

    public function itemDelete($id)
    {
        $params = [
            'index' => $this->index,
            'id' => $id
        ];
        return $this->client->delete($params);
    }

    public function itemGet($id)
    {
        $result = null;
        $params = [
            'index' => $this->index,
            'id' => $id
        ];
        try {
            $result = $this->client->get($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            // stealth 'not found' exception
        }
        return $result;
    }

    public function itemUpdate($id, $data)
    {
        $params = [
            'index' => $this->index,
            'id' => $id,
            'body' => [
                'doc' => $data
            ]
        ];
        return $this->client->update($params);
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index): void
    {
        $this->index = $index;
    }
}