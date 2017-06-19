<?php

namespace Deseco\Economic\Services\Unit;

use Exception;
use Deseco\Economic\Services\RestService;
use Deseco\Economic\Contracts\ServiceInterface;
use Deseco\Economic\Contracts\ClientInterface as Client;

class Unit extends RestService implements ServiceInterface
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
     * Get all products
     *
     * @return array
     */
    public function all()
    {
        $result = $this->get('units');

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
        $result = $this->get('units?filter=name$like:' . preg_replace('/[()*$,\[\]]/', '', $name));

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }

    /**
     * Create unit
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data = [])
    {
        $body = [
            'name' => $data['name'],
        ];

        $unit = $this->post('units', ['body' => json_encode($body)]);

        if (! $unit) {
            throw \Exception('Unit could not be created.');
        }

        return $unit;
    }
}
