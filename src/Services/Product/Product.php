<?php

namespace Athliit\Economic\Services\Product;

use Exception;
use Athliit\Economic\Services\RestService;
use Athliit\Economic\Contracts\ServiceInterface;
use Athliit\Economic\Contracts\ClientInterface as Client;

class Product extends RestService implements ServiceInterface
{
    /**
     * Client
     */
    protected $client;

    /**
     * \Athliit\Economic\Economic
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
     * Get all products
     *
     * @return array
     */
    public function all()
    {
        $result = $this->get('products');

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Find product by name
     *
     * @param string $name
     *
     * @return object
     */
    public function findByName($name)
    {
        $result = $this->get('products?filter=name$like:' . preg_replace('/[()*$,\[\]]/', '', $name));

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Create product
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data = [])
    {
        $products = $this->all();
        $number = $products ? (int) $this->last($products)->productNumber + 1 : 1;
        $group = $this->manager->productGroup->findBy('AthliitProductGroup', 'name');
        $name = $data['name'] ? preg_replace('/[()*$,\[\]]/', '', $data['name']) : 'Unknown Name';

        if (! $group) {
            throw new Exception('Group does not exists. Can\'t create product.');
        }

        $body = [
            'name' => $name,
            'productNumber' => (string) $number,
            'productGroup' => [
                'productGroupNumber' => (int) $group->productGroupNumber,
                'self' => $group->self
            ]
        ];

        $product = $this->post('products', ['body' => json_encode($body)]);

        if (! $product) {
            throw \Exception('Product could not be created.');
        }

        return $product;
    }
}
