<?php

namespace Deseco\Economic;

use SoapClient;
use GuzzleHttp\Client;
use Deseco\Economic\Contracts\ClientInterface;

class Economic implements ClientInterface
{
    /**
     * Client grant token
     */
    protected $grantToken;

    /**
     * Developer app token
     */
    protected $appToken;

    /**
     * SOAP Connection
     */
    protected $soap;

    /**
     * Rest Connection
     */
    protected $rest;

    /**
     * E-conomics soap API url
     * @var string
     */
    protected $soapApiUrl = 'https://api.e-conomic.com/secure/api1/EconomicWebservice.asmx?WSDL';

    /**
     * E-conomics rest API url
     * @var string
     */
    protected $restApiUrl = 'https://restapi.e-conomic.com/';

    /**
     * Array with debug options
     * @var array
     */
    protected $debug = ["trace" => 1, "exceptions" => 1];

    /**
     * Economic config
     */
    protected $config;

    /**
     * Services
     *
     * @var array
     */
    protected $services = [];

    /**
     * Connect to api
     *
     * @return \Deseco\Economic\Economic
     */
    public function connect()
    {
        $this->soap = $this->getSoapClient();
        $this->rest = $this->getRestClient();

        return $this;
    }

    /**
     * Set grant token
     *
     * @param mixed $grantTokenOrUser
     *
     * @return \Deseco\Economic\Economic
     */
    public function setGrantToken($grantToken)
    {
        $this->grantToken = $grantToken;

        return $this;
    }

    /**
     * Set set app token
     *
     * @param string app token
     *
     * @return \Deseco\Economic\Economic
     */
    public function setAppToken($appToken)
    {
        $this->appToken = $appToken;

        return $this;
    }

    /**
     * Sets the configuration.
     *
     * @param array $config
     *
     * @return \Deseco\Economic\Economic
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Return client
     *
     * @return mixed
     */
    public function getClient($type)
    {
        switch ($type) {
            case 'soap':
                return $this->soap;
                break;
            case 'rest':
                return $this->rest;
                break;
            default:
                return $this->rest;
        }
    }

    /**
     * Make service
     *
     * @param string $service
     *
     * @return mixed
     */
    public function make($service)
    {
        $key = EconomicServiceFactory::getKey($service);

        if (array_key_exists($key, $this->services)) {
            return $this->services[$key];
        }

        $this->services[$key] = EconomicServiceFactory::create($service, $this);

        return $this->services[$key];
    }

    /**
     * Gets the rest client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getRestClient()
    {
        $client = new Client([
            'base_uri' => $this->restApiUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-AppSecretToken' => $this->appToken,
                'X-AgreementGrantToken' => $this->grantToken,
            ],
        ]);

        return $client;
    }

    /**
     * Setup connection
     *
     * @return SoapClient
     */
    protected function getSoapClient()
    {
        $client = new SoapClient($this->soapApiUrl, $this->debug);
        $client->ConnectWithToken([
            'token' => $this->grantToken,
            'appToken' => $this->appToken,
        ]);

        return $client;
    }

    /**
     * Create services on the fly
     *
     * @param <type> $value The value
     *
     * @return <type> ( description_of_the_return_value )
     */
    public function __get($value)
    {
        $parts = explode('_', snake_case($value));

        return $this->make(implode('.', $parts));
    }
}
