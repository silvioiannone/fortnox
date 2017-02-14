<?php

namespace SI\API;

use SI\API\Fortnox\Accounts;
use SI\API\Fortnox\Articles;
use SI\API\Fortnox\Exceptions\MissingAuthorizationToken;
use SI\API\Fortnox\FinancialYears;
use SI\API\Fortnox\InvoicePayments;
use SI\API\Fortnox\Invoices;
use SI\API\Fortnox\SupplierInvoicePayments;
use SI\API\Fortnox\SupplierInvoices;
use SI\API\Fortnox\Vouchers;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractFortnox
 *
 * @package SI\API
 */
abstract class AbstractFortnox
{
    /**
     * API base URL.
     *
     * @var string
     */
    const API_BASE_URL = 'https://api.fortnox.se/3';

    /**
     * The client ID.
     *
     * @var string
     */
    protected $clientId = '';

    /**
     * The client secret.
     *
     * @var string
     */
    protected $clientSecret = '';

    /**
     * Authorization code.
     *
     * @var string
     */
    protected $authorizationToken;

    /**
     * HTTP Client.
     *
     * @var Client
     */
    protected $http;

    /**
     * Client settings
     *
     * @var array
     */
    protected $settings;

    /**
     * AbstractFortnox constructor.
     *
     * @param array $settings An array containing the configuration.
     *
     * @throws \Exception If the configuration file doesn't exist.
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;

        // If an authorization token is present in the settings...
        if(isset($this->settings['authorizationToken']) && $this->settings['authorizationToken'])
        {
            //...then fetch an authorization token
            $this->getAccessToken();
        }

        if($this->settings['accessToken'])
        {
            $this->initHTTPClient();
        }
    }

    /**
     * Send a GET request to the API.
     *
     * @param string $URI        API endpoint (excluding the API base path).
     * @param array  $parameters GET parameters if any.
     *
     * @return ResponseInterface
     */
    public function get($URI, $parameters = [])
    {
        $apiURL = self::API_BASE_URL.$URI;

        $apiURL .= ($parameters) ? '&'.http_build_query($parameters) : '';

        return $this->http->get($apiURL);
    }

    /**
     * Access the accounts resource.
     *
     * @return Accounts
     */
    public function accounts()
    {
        return new Accounts($this->http);
    }

    /**
     * Access the articles resource.
     *
     * @return Articles
     */
    public function articles()
    {
        return new Articles($this->http);
    }

    /**
     * Access the financial years resource.
     *
     * @return FinancialYears
     */
    public function financialYears()
    {
        return new FinancialYears($this->http);
    }

    /**
     * Access the invoice payments resource.
     *
     * @return InvoicePayments
     */
    public function invoicePayments()
    {
        return new InvoicePayments($this->http);
    }

    /**
     * Access the invoices resource.
     *
     * @return Invoices
     */
    public function invoices()
    {
        return new Invoices($this->http);
    }

    /**
     * Access the supplier invoice payments.
     *
     * @return SupplierInvoicePayments
     */
    public function supplierInvoicePayments()
    {
        return new SupplierInvoicePayments($this->http);
    }

    /**
     * Access the supplier invoices resource.
     * 
     * @return SupplierInvoices
     */
    public function supplierInvoices()
    {
        return new SupplierInvoices($this->http);
    }

    /**
     * Access the vouchers resource.
     * 
     * @return Vouchers
     */
    public function vouchers()
    {
        return new Vouchers($this->http);
    }

    /**
     * Get the access token needed to use the API.
     *
     * @throws \Exception If the access token is invalid.
     *
     * @return void
     */
    protected function getAccessToken()
    {
        if(!$this->settings['authorizationToken'] && !isset($this->settings['accessToken']))
        {
            throw new MissingAuthorizationToken('An authorization token must be set in order to' .
                'fetch the access token');
        }

        $client = new Client([
            'headers' => [
                'Authorization-Code' => $this->settings['authorizationToken'],
                'Client-Secret'      => $this->clientSecret,
                'Content-Type'       => 'application/json',
                'Accept'             => 'application/json'
            ]
        ]);

        $response = $client->get(self::API_BASE_URL);
        $accessToken = json_decode($response->getBody()->getContents(), true)
            ['Authorization']['AccessToken'];

        $this->settings['accessToken'] = $accessToken;

        // Remove the authorization token from the settings
        unset($this->settings['authorizationToken']);

        // Save the settings in order to store the access token.
        $this->saveSettings();
    }

    /**
     * Initialize the client that will send the requests to the AbstractFortnox API
     *
     * @return void
     */
    protected function initHTTPClient()
    {
        $this->http = new Client([
            'headers' => [
                'Access-Token'  => $this->settings['accessToken'] ?? '',
                'Client-Secret' => $this->clientSecret,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ]
        ]);
    }

    /**
     * Override this function to decide how the settings should be saved.
     *
     * This is important because once the authorization is used in order to fetch an access token it
     * cannot be used again and the access token (stored in the settings) must be saved.
     *
     * @return void
     */
    abstract protected function saveSettings();
}