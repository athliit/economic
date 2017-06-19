<?php

namespace Deseco\Economic\Services\Product;

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
        $this->manager = $client;
    }

    /**
     * Get all resources
     *
     * @return array
     */
    public function all()
    {
        $result = $this->get('product-groups');

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Find group by name
     *
     * @param string $name
     *
     * @return object
     */
    public function findByName($name)
    {
        $result = $this->get('product-groups?filter=name$eq:' . $name);

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Create group by soap, rest api dosen't support this
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data = [])
    {
        $client = $this->manager->getClient('soap');

        $group = $client->ProductGroup_Create([
            'number' => 10001,
            'name' => $data['name'],
            'accountForVatLiableDebtorInvoicesCurrentHandle' => [
                'Number' => $this->manager->account->findBy('AthliitAccount', 'name')->accountNumber
            ]
        ]);

        return $group;
    }
}
