<?php

namespace SI\API\Fortnox;

use GuzzleHttp\Client;

/**
 * Class AbstractResourceCollection
 *
 * A collection of resources.
 *
 * @package SI\API\Fortnox
 */
abstract class AbstractResourceCollection
{
    /**
     * @var array Collection data.
     */
    protected $data;

    /**
     * @var Client HTTP client needed to query the Fortnox API.
     */
    protected $httpClient;

    /**
     * @var array Collection metadata.
     */
    protected $metadata;

    /**
     * AbstractResourceCollection constructor.
     *
     * @param string $rawResourcesCollection Encoded collection.
     * @param Client $httpClient             HTTP client needed to query the Fortnox API.
     */
    public function __construct($rawResourcesCollection, Client $httpClient)
    {
        $resourcesCollection = json_decode($rawResourcesCollection, true);

        $this->data       = $resourcesCollection[(new \ReflectionClass($this))->getShortName()];
        $this->httpClient = $httpClient;
    }

    /**
     * Return the collected data.
     *
     * @return array
     */
    public function getCollection()
    {
        return $this->data;
    }

    /**
     * Include additional resources.
     * 
     * @param array $rawResourcesCollection
     *
     * @return $this
     */
    public function includeResources($rawResourcesCollection)
    {
        $resourcesCollection = json_decode($rawResourcesCollection, true);

        $this->data = array_merge(
            $this->data,
            $resourcesCollection[(new \ReflectionClass($this))->getShortName()]
        );

        return $this;
    }

    /**
     * Remember: the number of requests made will be as many as the number of
     *           resources fetched.
     *
     * @param callable $callback A callback that will be executed after every
     *                 resource has been fetched.
     * @return array
     */
    abstract public function fetchDetails(callable $callback = null);
}