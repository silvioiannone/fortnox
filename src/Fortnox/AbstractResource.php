<?php

namespace SI\API\Fortnox;

use SI\API\AbstractFortnox;
use Doctrine\Common\Inflector\Inflector;
use GuzzleHttp\Client;

/**
 * Class AbstractResource
 *
 * Represents a Fortnox resource such as Vouchers etc...
 *
 * @package SI\API\Fortnox
 */
abstract class AbstractResource
{
    /**
     * @var Client HTTP client used to query Fortnox API.
     */
    protected $httpClient;

    /**
     * @var array List of parameters that will be appended to the URI when
     *            querying Fortnox.
     */
    protected $parameters = [];

    /**
     * AbstractResource constructor.
     *
     * @param Client $httpClient HTTP client used to query Fortnox API.
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Clean the raw response coming from BooEnergi.
     *
     * @param $resource
     *
     * @return array
     */
    protected function clean($resource)
    {
        $refinedResource = $resource->{ucfirst(
            Inflector::singularize($this->getResourceName())
        )};

        $arrayRefinedResource = json_decode(json_encode($refinedResource), true);

        foreach($arrayRefinedResource as $index => $value)
        {
            if($index[0] === '@') unset($arrayRefinedResource[$index]);
        }

        return $arrayRefinedResource;
    }

    /**
     * Get a single resource.
     *
     * @param int $resourceId
     *
     * @return array
     */
    public function get($resourceId)
    {
        $rawResource = json_decode($this->httpClient->get(
            AbstractFortnox::API_BASE_URL . '/' .
            strtolower($this->getResourceName()) . '/' .
            $resourceId
        )
            ->getBody()
            ->getContents());

        return $this->clean($rawResource);
    }

    /**
     * List all resources. List is a reserved keyword so we use all instead.
     *
     * @return AbstractResourceCollection
     */
    public function fetch()
    {
        $resources = $this->httpClient
            ->get(
                AbstractFortnox::API_BASE_URL . '/' . strtolower($this->getResourceName() .
                    "/" . $this->buildURIParametersString())
            )
            ->getBody()
            ->getContents();

        // Instantiate the collection
        $resourcesCollectionClass = (new \ReflectionClass($this))->getNamespaceName()
            . '\\Collections\\' . ucfirst($this->getResourceName());
        $resourceCollection = new $resourcesCollectionClass($resources, $this->httpClient);

        // Fetch the resources from the additional pages...
        $parsedResources = json_decode($resources, true);
        $totalPages = $parsedResources['MetaInformation']['@TotalPages'];
        $currentPage = $parsedResources['MetaInformation']['@CurrentPage'];

        // ...and if more resources are found add them to the collection
        while ($currentPage < $totalPages)
        {
            $this->parameters['page'] = ++$currentPage;

            $resources = $this->httpClient
                ->get(
                    AbstractFortnox::API_BASE_URL . '/' . strtolower($this->getResourceName() .
                        "/" . $this->buildURIParametersString())
                )
                ->getBody()
                ->getContents();

            $resourceCollection->includeResources($resources);
        }

        return $resourceCollection;
    }

    /**
     * Fetch the resource details.
     *
     * Note: It's much slower than 'fetch' because it needs to send a request
     * for each query.
     *
     * @param callable $callback A callback that will be executed after every
     *        resource has been fetched. It will receive the fetched resource as
     *        a paramter.
     * @return array
     */
    public function fetchDetails(callable $callback = null)
    {
        return $this->fetch()->fetchDetails($callback);
    }

    /**
     * Retrieves the resources since the provided date time.
     *
     * @param $datetime
     *
     * @return $this
     */
    public function from($datetime)
    {
        if(!$datetime) return $this;

        $this->parameters['lastmodified'] = $datetime;

        return $this;
    }

    /**
     * Include additional parameters to the already existing ones.
     *
     * @param array $parameters
     * @return $this
     */
    public function includeParameters($parameters = [])
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * Set the request parameters.
     *
     * @param array $parameters
     * @return $this
     */
    public function setParameters($parameters = [])
    {
        if($parameters)
            $this->parameters = $parameters;

        return $this;
    }

    /**
     * Builds the parameters string for the URI.
     *
     * @return string
     */
    protected function buildURIParametersString()
    {
        if(!$this->parameters) return '';

        $URIParameters = '?';

        $parametersStrings = [];
        foreach($this->parameters as $key => $value)
        {
            $parametersStrings[] = $key . "=" . $value;
        }

        $URIParameters .= implode('&', $parametersStrings);

        return $URIParameters;
    }

    /**
     * Get the resouce name. By default the default class name is used.
     */
    protected function getResourceName()
    {
        return lcfirst((new \ReflectionClass($this))->getShortName());
    }
}
