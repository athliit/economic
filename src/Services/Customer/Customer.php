<?php

namespace Athliit\Economic\Services\Customer;

use Exception;
use Athliit\Economic\Services\RestService;
use Athliit\Economic\Contracts\ServiceInterface;
use Athliit\Economic\Contracts\ClientInterface as Client;

class Customer extends RestService implements ServiceInterface
{
    /**
     * Client
     */
    protected $client;

    /**
     * Economic
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient('rest');
        $this->manager    = $client;
    }

    /**
     * Get all customers
     *
     * @return array
     */
    public function all()
    {
        $result = $this->get('customers');

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Find customer by email
     *
     * @param string $email
     *
     * @return object
     */
    public function findByEmail($email)
    {
        $result = $this->get('customers?filter=email$eq:' . $email);

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Create customer
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data = [])
    {
        $vatZone = $this->first($this->get('vat-zones'));
        $paymentTerm = $this->first($this->get('payment-terms'));
        $athlitGroup = $this->manager->customerGroup->findBy('AthliitCustomerGroup', 'name');

        if (! $vatZone || ! $paymentTerm || ! $athlitGroup) {
            throw new Exception('Not enaough data.');
        }

        $body = [
            'currency' => 'DKK',
            'name' => $data['name'],
            'email' => $data['email'],
            'customerGroup' => [
                'customerGroupNumber' => $athlitGroup->customerGroupNumber,
                'self' => $athlitGroup->self
            ],
            'paymentTerms' => [
                'paymentTermsNumber' => $paymentTerm->paymentTermsNumber,
                'self' =>  $paymentTerm->self
            ],
            'vatZone' => [
                'vatZoneNumber' => $vatZone->vatZoneNumber,
                'self' => $vatZone->self,
            ]
        ];

        $customer = $this->post('customers', ['body' => json_encode($body)]);

        if (! $customer) {
            throw Exception('Group could not be created.');
        }

        return $customer;
    }
}
