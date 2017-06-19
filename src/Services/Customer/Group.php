<?php

namespace Deseco\Economic\Services\Customer;

use Exception;
use Deseco\Economic\Services\RestService;
use Deseco\Economic\Contracts\ServiceInterface;
use Deseco\Economic\Contracts\ClientInterface as Client;

class Group extends RestService implements ServiceInterface
{
    /**
     * Client
     */
    protected $client;

    /**
     * \Deseco\Economic\Economic
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('rest');
        $this->manager= $client;
    }

    /**
     * Get all items
     *
     * @return array
     */
    public function all()
    {
        $result = $this->get('customer-groups');

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Find item by name
     *
     * @param stirng $name
     *
     * @return object
     */
    public function findByName($name)
    {
        $result = $this->get('customer-groups?filter=name$eq:' . $name);

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Create customer group
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data = [])
    {
        $groups = $this->all();
        $number = $groups ? $this->last($groups)->customerGroupNumber + 1 : 1;

        $body = [
            'account' => [
                'accountNumber' => $this->manager->account->findBy('AthliitAccount', 'name')->accountNumber
            ],
            'customerGroupNumber' => $number,
            'name' => $data['name']
        ];

        $group = $this->post('customer-groups', ['body' => json_encode($body)]);

        if (! $group) {
            throw \Exception('Group could not be created.');
        }

        return $group;
    }
}
