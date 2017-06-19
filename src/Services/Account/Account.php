<?php

namespace Athliit\Economic\Services\Account;

use Exception;
use Athliit\Economic\Services\RestService;
use Athliit\Economic\Contracts\ServiceInterface;
use Athliit\Economic\Contracts\ClientInterface as Client;

class Account extends RestService implements ServiceInterface
{
    /**
     * Client
     */
    protected $client;

    /**
     * Manager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param Client $client The client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('rest');
        $this->manager = $client;
    }

    /**
     * Get all items
     *
     * @return array
     */
    public function all($limit = 1000)
    {
        $result = $this->get('accounts?pageSize=' . $limit);

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Find account by type
     *
     * @param string $type
     *
     * @return object
     */
    public function findByType($type)
    {
        $result = $this->get('accounts?filter=accountType$eq:' . $type);

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Find account by name
     *
     * @param string $name
     *
     * @return object
     */
    public function findByName($name)
    {
        $result = $this->get('accounts?filter=name$like:' . $name);

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    public function create($data = [])
    {
        $client = $this->manager->getClient('soap');
        $accounts = $this->all();

        return $client->Account_Create([
            'number' => $accounts ? (int) $this->last($accounts)->accountNumber + 1 : 1,
            'name' => $data['name'],
            'type' => $data['type'],
        ])->Account_CreateResult;
    }
}
