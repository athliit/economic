<?php

namespace Deseco\Economic\Services\Department;

use Deseco\Economic\Contracts\ClientInterface as Client;
use Deseco\Economic\Contracts\ServiceInterface;
use Deseco\Economic\Services\RestService;

/**
 * Class Department
 * @package Deseco\Economic\Services\Department
 */
class Department extends RestService implements ServiceInterface
{
    /**
     * @var
     */
    protected $client;

    /**
     * @var \Deseco\Economic\Contracts\ClientInterface
     */
    protected $manager;

    /**
     * Department constructor.
     *
     * @param \Deseco\Economic\Contracts\ClientInterface $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('rest');
        $this->manager = $client;
    }

    /**
     * @param int $limit
     *
     * @return bool|mixed
     */
    public function all($limit = 1000)
    {
        $result = $this->get('departments?pageSize=' . $limit);

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * @param $name
     *
     * @return bool|mixed
     */
    public function findByName($name)
    {
        $result = $this->get('departments?filter=name$like:' . preg_replace('/[()*$,\[\]]/', '', $name));

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }
}
